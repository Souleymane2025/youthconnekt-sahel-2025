const net = require('net');

// Fonction pour vérifier si un port est disponible
function isPortAvailable(port) {
    return new Promise((resolve) => {
        const server = net.createServer();
        
        server.listen(port, () => {
            server.once('close', () => {
                resolve(true);
            });
            server.close();
        });
        
        server.on('error', () => {
            resolve(false);
        });
    });
}

// Fonction pour trouver un port disponible
async function findAvailablePort(startPort = 3000) {
    for (let port = startPort; port < startPort + 100; port++) {
        if (await isPortAvailable(port)) {
            return port;
        }
    }
    throw new Error('Aucun port disponible trouvé');
}

// Fonction pour tuer les processus sur un port
function killProcessOnPort(port) {
    const { exec } = require('child_process');
    
    return new Promise((resolve, reject) => {
        // Sur Windows
        if (process.platform === 'win32') {
            exec(`netstat -ano | findstr :${port}`, (error, stdout) => {
                if (error) {
                    resolve(false);
                    return;
                }
                
                const lines = stdout.split('\n');
                const pids = [];
                
                lines.forEach(line => {
                    const parts = line.trim().split(/\s+/);
                    if (parts.length >= 5 && parts[1].includes(`:${port}`)) {
                        pids.push(parts[4]);
                    }
                });
                
                if (pids.length === 0) {
                    resolve(false);
                    return;
                }
                
                // Tuer les processus
                const killPromises = pids.map(pid => {
                    return new Promise((killResolve) => {
                        exec(`taskkill /PID ${pid} /F`, (killError) => {
                            killResolve(!killError);
                        });
                    });
                });
                
                Promise.all(killPromises).then(() => {
                    resolve(true);
                });
            });
        } else {
            // Sur Unix/Linux/Mac
            exec(`lsof -ti:${port}`, (error, stdout) => {
                if (error) {
                    resolve(false);
                    return;
                }
                
                const pids = stdout.trim().split('\n').filter(pid => pid);
                if (pids.length === 0) {
                    resolve(false);
                    return;
                }
                
                const killPromises = pids.map(pid => {
                    return new Promise((killResolve) => {
                        exec(`kill -9 ${pid}`, (killError) => {
                            killResolve(!killError);
                        });
                    });
                });
                
                Promise.all(killPromises).then(() => {
                    resolve(true);
                });
            });
        }
    });
}

module.exports = {
    isPortAvailable,
    findAvailablePort,
    killProcessOnPort
};

