const express = require('express');
const router = express.Router();
const partnerFormController = require('../controllers/partnerFormController');

// Routes pour les formulaires
router.get('/partner', partnerFormController.showPartnerForm);
router.get('/sponsor', partnerFormController.showSponsorForm);
router.get('/stand', partnerFormController.showStandForm);

// Routes pour la soumission
router.post('/partner', partnerFormController.submitPartnerForm);
router.post('/sponsor', partnerFormController.submitSponsorForm);
router.post('/stand', partnerFormController.submitStandForm);

module.exports = router;

