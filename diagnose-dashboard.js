// Script de diagnostic pour le dashboard
const fs = require('fs');
const path = require('path');

console.log('🔍 Diagnostic du Dashboard YouthConnekt Sahel 2025');
console.log('================================================');

// Vérifier les fichiers
const filesToCheck = [
    'frontend/views/pages/admin-dashboard-excellent.ejs',
    'frontend/app.js',
    'frontend/server.js',
    'frontend/routes/admin.js',
    'frontend/controllers/adminController.js'
];

console.log('\n📁 Vérification des fichiers:');
filesToCheck.forEach(file => {
    if (fs.existsSync(file)) {
        console.log(`✅ ${file} - Existe`);
    } else {
        console.log(`❌ ${file} - Manquant`);
    }
});

// Vérifier le contenu du dashboard
console.log('\n📄 Analyse du dashboard:');
const dashboardFile = 'frontend/views/pages/admin-dashboard-excellent.ejs';
if (fs.existsSync(dashboardFile)) {
    const content = fs.readFileSync(dashboardFile, 'utf8');
    
    // Vérifier les sections
    const sections = ['messages-section', 'blog-section', 'newsletter-section', 'badges-section', 'exports-section'];
    sections.forEach(section => {
        if (content.includes(section)) {
            console.log(`✅ Section ${section} - Trouvée`);
        } else {
            console.log(`❌ Section ${section} - Manquante`);
        }
    });
    
    // Vérifier les fonctions
    const functions = ['loadMessagesData', 'loadBlogData', 'loadNewsletterData', 'loadBadgesData', 'loadExportsData'];
    functions.forEach(func => {
        if (content.includes(func)) {
            console.log(`✅ Fonction ${func} - Trouvée`);
        } else {
            console.log(`❌ Fonction ${func} - Manquante`);
        }
    });
    
    // Vérifier la taille du fichier
    const stats = fs.statSync(dashboardFile);
    console.log(`📊 Taille du fichier: ${(stats.size / 1024).toFixed(2)} KB`);
}

// Vérifier les routes admin
console.log('\n🛣️ Vérification des routes admin:');
const adminRoutesFile = 'frontend/routes/admin.js';
if (fs.existsSync(adminRoutesFile)) {
    const content = fs.readFileSync(adminRoutesFile, 'utf8');
    const routes = ['/admin/api/messages', '/admin/api/blogs', '/admin/api/newsletters', '/admin/api/badges', '/admin/api/export'];
    routes.forEach(route => {
        if (content.includes(route)) {
            console.log(`✅ Route ${route} - Trouvée`);
        } else {
            console.log(`❌ Route ${route} - Manquante`);
        }
    });
}

console.log('\n🎯 Recommandations:');
console.log('1. Vérifiez que le serveur frontend est démarré: node server.js');
console.log('2. Accédez à http://localhost:3000/admin/login');
console.log('3. Connectez-vous avec: admin@youthconnekt-sahel.org / admin123');
console.log('4. Testez chaque section du dashboard');

console.log('\n✨ Diagnostic terminé!');
