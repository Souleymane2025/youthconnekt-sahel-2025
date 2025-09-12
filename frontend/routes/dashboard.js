const express = require('express');
const router = express.Router();
const dashboardController = require('../controllers/dashboardController');

// Routes pour le dashboard
router.get('/', dashboardController.index);
router.get('/participants', dashboardController.participants);
router.get('/badges', dashboardController.badges);
router.get('/emails', dashboardController.emails);
router.get('/blogs', dashboardController.blogs);

// Actions pour les participants
router.put('/participants/:id/status', dashboardController.updateParticipantStatus);
router.get('/participants/:id/pdf', dashboardController.downloadParticipantPDF);
router.get('/participants/:id/badge', dashboardController.downloadParticipantBadge);
router.get('/participants/:id/view', dashboardController.viewParticipant);

module.exports = router;
