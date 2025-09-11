const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');

// Middleware d'authentification
const authenticateAdmin = (req, res, next) => {
    const token = req.headers.authorization?.replace('Bearer ', '') || req.body.token || req.query.token;
    const cookieToken = req.cookies?.adminToken;
    const sessionToken = req.session?.adminToken;
    const isSessionAuthenticated = req.session?.isAuthenticated;
    
    console.log('🔒 Middleware auth - Tokens reçus:', {
        headerToken: token ? 'présent' : 'absent',
        cookieToken: cookieToken ? 'présent' : 'absent',
        sessionToken: sessionToken ? 'présent' : 'absent',
        isSessionAuth: isSessionAuthenticated,
        path: req.path
    });
    
    // Vérification du token, cookie ou session
    if (token === 'admin_token_2025' || token === 'youthconnekt_admin_secure' || 
        cookieToken === 'admin_token_2025' ||
        (sessionToken === 'admin_token_2025' && isSessionAuthenticated)) {
        req.adminUser = req.cookies?.adminUser || req.session?.adminUser || 'admin';
        console.log('✅ Authentification réussie pour:', req.adminUser);
        next();
    } else {
        console.log('❌ Authentification échouée');
        // Pour les requêtes GET (comme le dashboard), rediriger vers login au lieu de retourner JSON
        if (req.method === 'GET' && !req.path.includes('/api/')) {
            return res.redirect('/admin/login');
        }
        res.status(401).json({ success: false, message: 'Token invalide' });
    }
};

// Page de login
router.get('/login', adminController.loginPage);

// Connexion admin
router.post('/login', adminController.login);

// Déconnexion admin
router.get('/logout', adminController.logout);

// Dashboard (protégé)
router.get('/dashboard', authenticateAdmin, adminController.dashboard);

module.exports = router;


