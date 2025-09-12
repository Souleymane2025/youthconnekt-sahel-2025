const express = require('express');
const router = express.Router();
const sponsorController = require('../controllers/sponsorController');

router.get('/', sponsorController.index);

module.exports = router;