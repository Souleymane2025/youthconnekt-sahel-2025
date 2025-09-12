const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
const invitationController = require('../controllers/invitationController');

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

// Page de gestion des participants
router.get('/participants', authenticateAdmin, adminController.participantsPage);

// Pages de gestion
router.get('/messages', authenticateAdmin, adminController.messagesPage);
router.get('/blogs', authenticateAdmin, adminController.blogsPage);
router.get('/partners', authenticateAdmin, adminController.partnersPage);

// API pour les messages
router.get('/api/messages', authenticateAdmin, adminController.getMessages);

// API pour les blogs
router.get('/api/blogs', authenticateAdmin, adminController.getBlogs);
router.post('/api/blogs', authenticateAdmin, adminController.createBlog);
router.put('/api/blogs/:id', authenticateAdmin, adminController.updateBlog);
router.delete('/api/blogs/:id', authenticateAdmin, adminController.deleteBlog);
router.post('/api/blogs/:id/publish', authenticateAdmin, adminController.publishBlog);

// API pour les newsletters
router.get('/api/newsletters', authenticateAdmin, adminController.getNewsletters);
router.post('/api/newsletters', authenticateAdmin, adminController.createNewsletter);

// API pour les badges
router.get('/api/badges', authenticateAdmin, adminController.getBadges);
router.post('/api/badges/generate', authenticateAdmin, adminController.generateBadges);

// API pour les partenaires
router.get('/api/partners', authenticateAdmin, adminController.getPartners);
router.put('/api/partners/:id/status', authenticateAdmin, adminController.updatePartnerStatus);

// API pour les sponsors
router.get('/api/sponsors', authenticateAdmin, adminController.getSponsors);
router.put('/api/sponsors/:id/status', authenticateAdmin, adminController.updateSponsorStatus);

// API pour les stands
router.get('/api/stands', authenticateAdmin, adminController.getStands);
router.put('/api/stands/:id/status', authenticateAdmin, adminController.updateStandStatus);

// API pour les invitations
router.post('/api/invitations/send', authenticateAdmin, invitationController.sendInvitation);
router.get('/api/invitations/stats', authenticateAdmin, invitationController.getInvitationStats);

module.exports = router;