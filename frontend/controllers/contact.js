const express = require('express');
const path = require('path');
const bodyParser = require('body-parser');
const cors = require('cors');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

// View engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Routes
app.use('/', require('./routes/index'));
app.use('/participants', require('./routes/participants'));
app.use('/media', require('./routes/media'));
app.use('/partners', require('./routes/partners'));
app.use('/sponsors', require('./routes/sponsors'));
app.use('/exhibitions', require('./routes/exhibitions'));
app.use('/blog', require('./routes/blog'));
app.use('/contact', require('./routes/contact'));
app.use('/dashboard', require('./routes/dashboard'));

// Error handling
app.use((req, res) => {
    res.status(404).render('pages/404');
});

app.listen(PORT, () => {
    console.log(`YouthConnekt Sahel 2025 Frontend running on port ${PORT}`);
});

const express = require('express');
const router = express.Router();
const contactController = require('../controllers/contactController');

router.get('/', contactController.index);
router.post('/', contactController.store);

module.exports = router;