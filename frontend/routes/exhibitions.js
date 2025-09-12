const express = require('express');
const router = express.Router();
const exhibitionController = require('../controllers/exhibitionController');

router.get('/', exhibitionController.index);

module.exports = router;