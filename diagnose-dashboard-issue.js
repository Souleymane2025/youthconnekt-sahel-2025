const axios = require('axios');

async function diagnoseDashboardIssue() {
    console.log('🔍 Diagnostic du problème du dashboard...\n');
    
    // Test 1: Vérifier si le backend est accessible
    console.log('1. Test de connectivité backend...');
    try {
        const response = await axios.get('http://localhost:8000/api/healthz', { timeout: 5000 });
        console.log('   ✅ Backend accessible:', response.data);
    } catch (error) {
        console.log('   ❌ Backend non accessible:', error.message);
        console.log('   💡 Assurez-vous que le backend Laravel est démarré: cd backend && php artisan serve');
        return;
    }
    
    // Test 2: Tester l'API des participants
    console.log('\n2. Test de l\'API des participants...');
    try {
        const response = await axios.get('http://localhost:8000/api/participants', { timeout: 5000 });
        console.log('   ✅ API participants accessible');
        console.log('   📊 Données reçues:', {
            total: response.data.total || response.data.data?.length || 0,
            current_page: response.data.current_page || 1,
            hasData: response.data.data && response.data.data.length > 0
        });
        
        if (response.data.data && response.data.data.length > 0) {
            console.log('   👥 Premier participant:', {
                id: response.data.data[0].id,
                name: `${response.data.data[0].first_name} ${response.data.data[0].last_name}`,
                email: response.data.data[0].email,
                status: response.data.data[0].status
            });
        }
    } catch (error) {
        console.log('   ❌ API participants non accessible:', error.message);
        if (error.response) {
            console.log('   📋 Status:', error.response.status);
            console.log('   📋 Data:', error.response.data);
        }
    }
    
    // Test 3: Tester la configuration du frontend
    console.log('\n3. Test de la configuration frontend...');
    try {
        const response = await axios.get('http://localhost:3000/dashboard/participants', { timeout: 5000 });
        console.log('   ✅ Frontend accessible');
    } catch (error) {
        console.log('   ❌ Frontend non accessible:', error.message);
        console.log('   💡 Assurez-vous que le frontend Node.js est démarré: cd frontend && npm start');
    }
    
    // Test 4: Tester l'API interne du frontend
    console.log('\n4. Test de l\'API interne du frontend...');
    try {
        const response = await axios.get('http://localhost:3000/api/participants', { timeout: 5000 });
        console.log('   ✅ API interne frontend accessible');
        console.log('   📊 Réponse:', response.data);
    } catch (error) {
        console.log('   ❌ API interne frontend non accessible:', error.message);
    }
    
    console.log('\n📋 Résumé du diagnostic:');
    console.log('========================');
    console.log('Si le backend est accessible mais le dashboard ne montre pas de données,');
    console.log('le problème est probablement dans la configuration CORS ou dans le code du dashboard.');
    console.log('\n💡 Solutions possibles:');
    console.log('1. Vérifier la configuration CORS dans le backend Laravel');
    console.log('2. Vérifier que le frontend utilise la bonne URL API');
    console.log('3. Vérifier les logs du navigateur pour les erreurs JavaScript');
    console.log('4. Tester manuellement: http://localhost:3000/dashboard/participants');
}

// Exécuter le diagnostic
diagnoseDashboardIssue().catch(console.error);

