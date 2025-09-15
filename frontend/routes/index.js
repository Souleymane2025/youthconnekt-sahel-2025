const express = require('express');
const router = express.Router();
const homeController = require('../controllers/homeController');

// Route principale
router.get('/', homeController.index);

// Routes des pages principales
router.get('/about', homeController.about);
router.get('/program', homeController.program);
router.get('/speakers', homeController.speakers);
router.get('/registration', homeController.registration);
router.get('/registration-success', homeController.registrationSuccess);

// Routes pour les blogs
router.get('/blog', homeController.blog);
router.get('/blog/:id', homeController.blogPost);

// Route pour la page de contact
router.get('/contact', homeController.contact);

// Route pour envoyer un message de contact
router.post('/contact', homeController.sendContactMessage);

// Route des partenaires
router.get('/partners', (req, res) => {
    const partners = require('../config/partners');
    res.render('pages/partners', {
        title: 'Partenaires - YouthConnekt Sahel 2025',
        page: 'partners',
        partners: partners
    });
});

module.exports = router;