const express = require('express');
const path = require('path');
const fs = require('fs');
const bodyParser = require('body-parser');
const cors = require('cors');
const cookieParser = require('cookie-parser');
const session = require('express-session');
const { findAvailablePort, killProcessOnPort } = require('./scripts/fix-port-issues');
require('dotenv').config();

// Global error handlers to capture crashes that may stop the server
process.on('uncaughtException', (err) => {
    try {
        const out = `[${new Date().toISOString()}] UNCAUGHT_EXCEPTION:\n${err && err.stack ? err.stack : String(err)}\n`;
        fs.appendFileSync(path.join(__dirname, 'server_error.log'), out);
    } catch (e) {
        // ignore
    }
    console.error('Uncaught Exception:', err);
    // exit with failure after logging
    process.exit(1);
});

process.on('unhandledRejection', (reason, promise) => {
    try {
        const out = `[${new Date().toISOString()}] UNHANDLED_REJECTION:\n${reason && reason.stack ? reason.stack : String(reason)}\n`;
        fs.appendFileSync(path.join(__dirname, 'server_error.log'), out);
    } catch (e) {
        // ignore
    }
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
});

const app = express();
const PORT = process.env.PORT || 3000;

// In-memory stores (temp avant backend Laravel)
const newsletterSubscribers = [];
const sentEmails = [];
const blogs = [
    {
        id: 1,
        title: 'YouthConnekt Sahel 2025 : Lancement officiel',
        category: 'Forum',
        author: 'Comité d\'organisation',
        excerpt: 'Annonce du lancement du forum YouthConnekt Sahel 2025 à N\'Djamena.',
        content: 'Le forum YouthConnekt Sahel 2025 réunira des jeunes innovateurs, des partenaires techniques et des décideurs publics pour co-créer des solutions durables pour la région sahélienne. Restez connectés pour le programme détaillé, l\'ouverture des inscriptions et les annonces des intervenants.',
        slug: 'youthconnekt-sahel-2025-lancement-officiel',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
    }
];

// Middleware
app.use(cors());
app.use(cookieParser());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Configuration des sessions
app.use(session({
    secret: 'youthconnekt_sahel_2025_secret_key',
    resave: false,
    saveUninitialized: false,
    cookie: {
        secure: false, // En production, mettre à true si HTTPS
        maxAge: 24 * 60 * 60 * 1000 // 24 heures
    }
}));

app.use(express.static(path.join(__dirname, 'public')));
// Serve temporary uploaded files
const uploadsDir = path.join(__dirname, 'public', 'uploads');
if (!fs.existsSync(uploadsDir)) fs.mkdirSync(uploadsDir, { recursive: true });
app.use('/uploads', express.static(uploadsDir));

// View engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Routes (debug logs to find issues on require)
try { console.log('LOADING ROUTE / -> routes/index'); app.use('/', require('./routes/index')); console.log('LOADED ROUTE /'); } catch(e){ console.error('FAILED loading /', e); throw e }
try { console.log('LOADING ROUTE /participants -> routes/participants'); app.use('/participants', require('./routes/participants')); console.log('LOADED ROUTE /participants'); } catch(e){ console.error('FAILED loading /participants', e); throw e }
try { console.log('LOADING ROUTE /media -> routes/media'); app.use('/media', require('./routes/media')); console.log('LOADED ROUTE /media'); } catch(e){ console.error('FAILED loading /media', e); throw e }
try { console.log('LOADING ROUTE /partners -> routes/partners'); app.use('/partners', require('./routes/partners')); console.log('LOADED ROUTE /partners'); } catch(e){ console.error('FAILED loading /partners', e); throw e }
try { console.log('LOADING ROUTE /sponsors -> routes/sponsors'); app.use('/sponsors', require('./routes/sponsors')); console.log('LOADED ROUTE /sponsors'); } catch(e){ console.error('FAILED loading /sponsors', e); throw e }
try { console.log('LOADING ROUTE /exhibitions -> routes/exhibitions'); app.use('/exhibitions', require('./routes/exhibitions')); console.log('LOADED ROUTE /exhibitions'); } catch(e){ console.error('FAILED loading /exhibitions', e); throw e }
try { console.log('LOADING ROUTE /blog -> routes/blog'); app.use('/blog', require('./routes/blog')); console.log('LOADED ROUTE /blog'); } catch(e){ console.error('FAILED loading /blog', e); throw e }
try { console.log('LOADING ROUTE /contact -> routes/contact'); app.use('/contact', require('./routes/contact')); console.log('LOADED ROUTE /contact'); } catch(e){ console.error('FAILED loading /contact', e); throw e }
try { console.log('LOADING ROUTE /admin -> routes/admin'); app.use('/admin', require('./routes/admin')); console.log('LOADED ROUTE /admin'); } catch(e){ console.error('FAILED loading /admin', e); throw e }
try { console.log('LOADING ROUTE /partner-forms -> routes/partner-forms'); app.use('/partner-forms', require('./routes/partner-forms')); console.log('LOADED ROUTE /partner-forms'); } catch(e){ console.error('FAILED loading /partner-forms', e); throw e }

// Route de test pour l'admin
app.get('/admin/test', (req, res) => {
    res.json({ message: 'Route admin fonctionne', timestamp: new Date().toISOString() });
});

// Newsletter endpoint temporaire
app.post('/newsletter', (req, res) => {
    const { email } = req.body;
    if(!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        return res.status(400).json({ success:false, message:'Email invalide' });
    }
    if(newsletterSubscribers.find(e => e.email === email)) {
        return res.json({ success:true, message:'Déjà abonné' });
    }
    const sub = { email, date: new Date().toISOString() };
    newsletterSubscribers.push(sub);
    sentEmails.push({ type:'newsletter_welcome', to: email, date: sub.date });
    res.json({ success:true, message:'Inscription réussie', total: newsletterSubscribers.length });
});

// Exposer stores pour dashboardController (simple)
app.locals._newsletterStore = newsletterSubscribers;
app.locals._emailsStore = sentEmails;
app.locals._blogsStore = blogs;
// Pending participants stored temporarily when backend API is unavailable
// Persistent pending participants (survive restart)
app.locals._participantsPending = [];

// Persist frontend pending to disk so Node restarts don't lose queue
const pendingDir = path.join(__dirname, 'storage');
const pendingFile = path.join(pendingDir, 'pending_frontend.json');
if (!fs.existsSync(pendingDir)) fs.mkdirSync(pendingDir, { recursive: true });
try {
    if (fs.existsSync(pendingFile)) {
        const raw = fs.readFileSync(pendingFile, 'utf8');
        app.locals._participantsPending = JSON.parse(raw || '[]') || [];
        console.log('Loaded frontend pending from', pendingFile, 'count=', app.locals._participantsPending.length);
    }
} catch (e) {
    console.warn('Failed to load pending_frontend.json', e && e.message ? e.message : e);
}

// Save helper
function saveFrontendPending() {
    try {
        fs.writeFileSync(pendingFile, JSON.stringify(app.locals._participantsPending || [], null, 2), 'utf8');
    } catch (e) {
        console.error('Failed to save pending_frontend.json', e && e.message ? e.message : e);
    }
}

// Periodic autosave so push sites don't need to call save explicitly
setInterval(() => {
    saveFrontendPending();
}, 5000);

// Save on graceful shutdown
process.on('SIGINT', () => { saveFrontendPending(); process.exit(); });
process.on('SIGTERM', () => { saveFrontendPending(); process.exit(); });

// ---- API BLOG (temporaire avant intégration Laravel) ----
function slugify(str){
    return str
        .toString()
        .normalize('NFD').replace(/\p{Diacritic}/gu,'')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g,'-')
        .replace(/^-+|-+$/g,'')
        .substring(0,120);
}

app.get('/api/blogs', (req,res)=>{
    res.json({ success:true, data: blogs });
});

// Debug route to view pending participants when backend is down
app.get('/admin/pending-registrations', (req, res) => {
    const secret = req.query.secret || '';
    const adminSecret = process.env.ADMIN_SECRET || 'dev_secret';
    const requester = req.ip || req.connection && req.connection.remoteAddress;

    // allow if secret matches or request is from localhost (127.0.0.1 / ::1)
    const isLocal = requester === '::1' || requester === '127.0.0.1' || requester === '::ffff:127.0.0.1';
    if (secret !== adminSecret && !isLocal) {
        return res.status(403).json({ success: false, message: 'Forbidden' });
    }
    res.json({ success: true, count: app.locals._participantsPending.length, data: app.locals._participantsPending });
});

// Admin endpoint to retry sending pending registrations to backend
app.post('/admin/retry-pending', async (req, res) => {
    const adminSecret = process.env.ADMIN_SECRET || 'dev_secret';
    const secret = req.query.secret || req.body.secret || '';
    if (secret !== adminSecret) return res.status(403).json({ success: false, message: 'Forbidden' });

    const pending = app.locals._participantsPending || [];
    const results = [];
    // attempt sequential retry
    const axios = require('axios');
    for (let i = 0; i < pending.length; i++) {
        const p = pending[i];
        try {
            let resp;
            if (p._files && p._files.length) {
                const FormData = require('form-data');
                const fsLocal = require('fs');
                const form = new FormData();
                Object.keys(p).forEach(k => {
                    if (k === '_files' || k === 'apiError' || k === 'savedAt') return;
                    const v = p[k];
                    if (Array.isArray(v)) v.forEach(x=> form.append(k, x)); else form.append(k, v === undefined ? '' : v);
                });
                p._files.forEach(f => {
                    try { form.append(f.field, fsLocal.createReadStream(f.path), { filename: f.filename }); } catch(e) {}
                });
                resp = await axios.post((process.env.API_BASE_URL || 'http://localhost:8000/api') + '/participants', form, { headers: form.getHeaders() });
            } else {
                resp = await axios.post((process.env.API_BASE_URL || 'http://localhost:8000/api') + '/participants', p, { headers: { 'Content-Type': 'application/json' } });
            }
            results.push({ idx: i, success: true, status: resp.status });
        } catch (e) {
            results.push({ idx: i, success: false, error: e && e.toString ? e.toString() : e });
        }
    }
    // remove successfully posted items
    app.locals._participantsPending = app.locals._participantsPending.filter((_, idx) => !results.find(r => r.idx === idx && r.success));
    res.json({ success: true, results, remaining: app.locals._participantsPending.length });
});

app.post('/api/blogs', (req,res)=>{
    const { title, category, content } = req.body;
    if(!title || !content){
        return res.status(400).json({ success:false, message:'Titre et contenu requis' });
    }
    const slug = slugify(title);
    if(blogs.find(b=>b.slug===slug)){
        return res.status(409).json({ success:false, message:'Un article avec ce titre existe déjà' });
    }
    const blog = {
        id: blogs.length? (Math.max(...blogs.map(b=>b.id))+1):1,
        title,
        category: category || 'Général',
        author: 'Admin',
        excerpt: (content.slice(0,140)+'...'),
        content,
        slug,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
    };
    blogs.push(blog);
    res.json({ success:true, data: blog });
});

app.delete('/api/blogs/:slug', (req,res)=>{
    const { slug } = req.params;
    const idx = blogs.findIndex(b=>b.slug===slug);
    if(idx === -1){
        return res.status(404).json({ success:false, message:'Article introuvable' });
    }
    const removed = blogs.splice(idx,1)[0];
    res.json({ success:true, data: removed });
});

// Error handling
app.use((req, res) => {
    res.status(404).render('pages/404');
});

// Fonction pour démarrer le serveur avec gestion des ports
async function startServer() {
    try {
        const defaultPort = process.env.PORT || 3000;
        let port = defaultPort;
        
        // Essayer de tuer les processus sur le port par défaut
        console.log(`Tentative de libération du port ${defaultPort}...`);
        await killProcessOnPort(defaultPort);
        
        // Attendre un peu pour que les processus se terminent
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // Vérifier si le port est maintenant disponible
        const { isPortAvailable } = require('./scripts/fix-port-issues');
        if (!(await isPortAvailable(defaultPort))) {
            console.log(`Port ${defaultPort} toujours occupé, recherche d'un port alternatif...`);
            port = await findAvailablePort(defaultPort);
        }
        
        app.listen(port, () => {
            console.log(`✅ YouthConnekt Sahel 2025 Frontend running on port ${port}`);
            console.log(`🌐 Access: http://localhost:${port}`);
        });
        
    } catch (error) {
        console.error('❌ Erreur lors du démarrage du serveur:', error);
        process.exit(1);
    }
}

// Démarrer le serveur
startServer();

// Admin endpoint to retry sending pending registrations to backend
app.post('/admin/retry-pending', async (req, res) => {
    const adminSecret = process.env.ADMIN_SECRET || 'dev_secret';
    const secret = req.query.secret || req.body.secret || '';
    if (secret !== adminSecret) return res.status(403).json({ success: false, message: 'Forbidden' });

    const pending = app.locals._participantsPending || [];
    const results = [];
    // attempt sequential retry
    const axios = require('axios');
    for (let i = 0; i < pending.length; i++) {
        const p = pending[i];
        try {
            let resp;
            if (p._files && p._files.length) {
                const FormData = require('form-data');
                const fsLocal = require('fs');
                const form = new FormData();
                Object.keys(p).forEach(k => {
                    if (k === '_files' || k === 'apiError' || k === 'savedAt') return;
                    const v = p[k];
                    if (Array.isArray(v)) v.forEach(x=> form.append(k, x)); else form.append(k, v === undefined ? '' : v);
                });
                p._files.forEach(f => {
                    try { form.append(f.field, fsLocal.createReadStream(f.path), { filename: f.filename }); } catch(e) {}
                });
                resp = await axios.post((process.env.API_BASE_URL || 'http://localhost:8000/api') + '/participants', form, { headers: form.getHeaders() });
            } else {
                resp = await axios.post((process.env.API_BASE_URL || 'http://localhost:8000/api') + '/participants', p, { headers: { 'Content-Type': 'application/json' } });
            }
            results.push({ idx: i, success: true, status: resp.status });
        } catch (e) {
            results.push({ idx: i, success: false, error: e && e.toString ? e.toString() : e });
        }
    }
    // remove successfully posted items
    app.locals._participantsPending = app.locals._participantsPending.filter((_, idx) => !results.find(r => r.idx === idx && r.success));
    res.json({ success: true, results, remaining: app.locals._participantsPending.length });
});