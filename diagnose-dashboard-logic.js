const http = require('http');

console.log('🔍 DIAGNOSTIC LOGIQUE DU DASHBOARD');
console.log('==================================');

// Test 1: Vérifier l'API backend
console.log('\n1. Test de l\'API Backend...');
const apiOptions = {
    hostname: 'localhost',
    port: 8000,
    path: '/api/participants',
    method: 'GET'
};

const apiReq = http.request(apiOptions, (res) => {
    let data = '';
    res.on('data', (chunk) => {
        data += chunk;
    });
    res.on('end', () => {
        try {
            const participants = JSON.parse(data);
            console.log('✅ API Backend accessible');
            console.log(`📊 Participants reçus: ${participants.total || participants.data?.length || 0}`);
            
            if (participants.data && participants.data.length > 0) {
                console.log('👤 Premier participant:', participants.data[0].first_name, participants.data[0].last_name);
                console.log('📋 Structure des données:', Object.keys(participants.data[0]));
            }
            
            // Test 2: Vérifier le dashboard
            console.log('\n2. Test du Dashboard Frontend...');
            const dashboardOptions = {
                hostname: 'localhost',
                port: 3000,
                path: '/dashboard/participants',
                method: 'GET'
            };
            
            const dashboardReq = http.request(dashboardOptions, (res) => {
                console.log('✅ Dashboard accessible');
                console.log(`📄 Status: ${res.statusCode}`);
                
                let htmlData = '';
                res.on('data', (chunk) => {
                    htmlData += chunk;
                });
                res.on('end', () => {
                    console.log('\n3. Analyse du contenu HTML...');
                    
                    // Vérifier si le tableau est présent
                    if (htmlData.includes('<table')) {
                        console.log('✅ Tableau HTML présent');
                    } else {
                        console.log('❌ Tableau HTML absent');
                    }
                    
                    // Vérifier si les données sont présentes
                    if (htmlData.includes('Aucune donnée')) {
                        console.log('❌ Message "Aucune donnée" détecté');
                    } else {
                        console.log('✅ Pas de message "Aucune donnée"');
                    }
                    
                    // Vérifier si les participants sont dans le HTML
                    if (htmlData.includes('T7 User') || htmlData.includes('Amina Saleh')) {
                        console.log('✅ Données des participants présentes dans le HTML');
                    } else {
                        console.log('❌ Données des participants absentes du HTML');
                    }
                    
                    // Vérifier la structure du HTML
                    if (htmlData.includes('participants.forEach')) {
                        console.log('✅ Code EJS de rendu présent');
                    } else {
                        console.log('❌ Code EJS de rendu absent');
                    }
                    
                    console.log('\n4. DIAGNOSTIC FINAL:');
                    console.log('===================');
                    
                    if (htmlData.includes('Aucune donnée')) {
                        console.log('🔴 PROBLÈME: Le dashboard affiche "Aucune donnée"');
                        console.log('💡 CAUSE: Le controller ne récupère pas les données de l\'API');
                        console.log('🛠️ SOLUTION: Vérifier la logique du dashboardController.js');
                    } else if (htmlData.includes('<table') && !htmlData.includes('T7 User')) {
                        console.log('🟡 PROBLÈME: Le tableau est présent mais vide');
                        console.log('💡 CAUSE: Les données ne sont pas passées au template');
                        console.log('🛠️ SOLUTION: Vérifier la variable "participants" dans le controller');
                    } else if (htmlData.includes('T7 User') || htmlData.includes('Amina Saleh')) {
                        console.log('🟢 SUCCESS: Les données sont présentes dans le HTML');
                        console.log('💡 Le problème pourrait être côté CSS ou JavaScript');
                    } else {
                        console.log('🔴 PROBLÈME: Structure HTML inattendue');
                        console.log('💡 CAUSE: Erreur dans le template EJS ou le controller');
                    }
                });
            });
            
            dashboardReq.on('error', (e) => {
                console.log('❌ Erreur Dashboard:', e.message);
            });
            
            dashboardReq.end();
            
        } catch (e) {
            console.log('❌ Erreur parsing API:', e.message);
        }
    });
});

apiReq.on('error', (e) => {
    console.log('❌ Erreur API:', e.message);
    console.log('💡 Vérifiez que le backend Laravel est démarré sur le port 8000');
});

apiReq.end();








