const express = require('express');
const router = express.Router();
const participantController = require('../controllers/participantController');
const multer = require('multer');
const path = require('path');
const uploadsPath = path.join(__dirname, '..', 'public', 'uploads');
const storage = multer.diskStorage({
	destination: function (req, file, cb) {
		cb(null, uploadsPath);
	},
	filename: function (req, file, cb) {
		const unique = Date.now() + '-' + Math.round(Math.random() * 1e9);
		cb(null, unique + '-' + file.originalname.replace(/[^a-zA-Z0-9.\-_]/g, '_'));
	}
});
const upload = multer({ storage: storage });

router.get('/register', participantController.showRegistrationForm);
// accept passportImage and cinImage files
router.post('/register', upload.fields([{ name: 'passportImage' }, { name: 'cinImage' }]), participantController.register);
router.get('/national', participantController.nationalRegistration);
router.get('/international', participantController.internationalRegistration);

// New complete registration routes
router.get('/register-complete', participantController.showCompleteRegistrationForm);
router.post('/register-complete', upload.fields([
    { name: 'photo', maxCount: 1 },
    { name: 'passport', maxCount: 1 },
    { name: 'cv', maxCount: 1 }
]), participantController.registerComplete);

module.exports = router;