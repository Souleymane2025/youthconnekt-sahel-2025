const express = require('express');
const router = express.Router();
const blogController = require('../controllers/blogController');

// Routes pour le blog
router.get('/', blogController.index);
router.get('/:slug', blogController.show);

module.exports = router;