const express = require('express');
const router = express.Router();
const partnerController = require('../controllers/partnerController');

router.get('/', partnerController.index);

module.exports = router;