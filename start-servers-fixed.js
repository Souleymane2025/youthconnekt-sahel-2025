const { spawn } = require('child_process');
const path = require('path');

console.log('🚀 Démarrage du système YouthConnekt Sahel 2025...\n');

// Fonction pour démarrer un serveur
function startServer(name, command, args, cwd, port) {
    console.log(`📡 Démarrage du serveur ${name} sur le port ${port}...`);
    
    const server = spawn(command, args, {
        cwd: cwd,
        stdio: 'inherit',
        shell: true,
        env: { ...process.env, PORT: port }
    });

    server.on('error', (err) => {
        console.error(`❌ Erreur lors du démarrage de ${name}:`, err);
    });

    server.on('close', (code) => {
        console.log(`🔴 Serveur ${name} fermé avec le code ${code}`);
    });

    return server;
}

// Démarrer le backend Laravel sur le port 8000
const backendServer = startServer(
    'Backend Laravel', 
    'php', 
    ['artisan', 'serve', '--host=0.0.0.0', '--port=8000'], 
    path.join(__dirname, 'backend'),
    8000
);

// Attendre un peu avant de démarrer le frontend
setTimeout(() => {
    // Démarrer le frontend Express sur le port 3000
    const frontendServer = startServer(
        'Frontend Express', 
        'node', 
        ['server.js'], 
        path.join(__dirname, 'frontend'),
        3000
    );
}, 3000);

// Gestion de l'arrêt propre
process.on('SIGINT', () => {
    console.log('\n🛑 Arrêt du système...');
    process.exit(0);
});

console.log('\n✅ Système démarré !');
console.log('📊 Dashboard: http://localhost:3000');
console.log('🔧 API Backend: http://localhost:8000');
console.log('📱 Dashboard Direct: dashboard-direct-simple.html');
console.log('\nAppuyez sur Ctrl+C pour arrêter le système.');
