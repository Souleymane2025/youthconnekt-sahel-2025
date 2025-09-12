// Jeu de données mock (à remplacer plus tard par API Laravel)
const mockParticipants = [
    { id: 1, name: 'Amina Saleh', country: 'Tchad', type: 'national', status: 'confirmé' },
    { id: 2, name: 'Issa Traoré', country: 'Mali', type: 'international', status: 'en attente' },
    { id: 3, name: 'Fatou Diop', country: 'Sénégal', type: 'international', status: 'confirmé' },
    { id: 4, name: 'Mahamat Adam', country: 'Tchad', type: 'national', status: 'confirmé' },
    { id: 5, name: 'Zeinab Ibrahim', country: 'Niger', type: 'international', status: 'en attente' }
];

const mockBadges = [];
// Email store will be read from app.locals if available
function getEmails(req){
    return (req.app && req.app.locals._emailsStore) ? req.app.locals._emailsStore : [];
}
function getBlogs(req){
    return (req.app && req.app.locals._blogsStore) ? req.app.locals._blogsStore : [];
}

function buildStats() {
    const total = mockParticipants.length;
    const national = mockParticipants.filter(p => p.type === 'national').length;
    const international = mockParticipants.filter(p => p.type === 'international').length;
    const confirmed = mockParticipants.filter(p => p.status === 'confirmé').length;
    return {
        totalParticipants: total,
        national,
        international,
        confirmed,
        badges: mockBadges.length,
        emailsSent: (global._emailsCountOverride ?? 0) || (typeof buildStats.emailsLength === 'number' ? buildStats.emailsLength : 0),
        completionRate: total ? Math.round((confirmed / total) * 100) : 0
    };
}

const apiService = require('../services/apiService');

const dashboardController = {
    index: async (req, res) => {
        try {
            const stats = buildStats();
            res.render('pages/dashboard', {
                title: 'Dashboard - YouthConnekt Sahel 2025',
                stats,
                page: 'dashboard'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/dashboard', { title: 'Dashboard - YouthConnekt Sahel 2025', stats: {}, page: 'dashboard' });
        }
    },

    participants: async (req, res) => {
        try {
            // Force fetch real participants from backend API
            let participants = [];
            let apiConnected = false;
            
            try {
                console.log('🔄 Tentative de connexion à l\'API backend...');
                const apiData = await apiService.getParticipants(req.query.page || 1, req.query.status || 'all');
                
                console.log('📊 Données API reçues:', JSON.stringify(apiData, null, 2));
                
                if (apiData && Array.isArray(apiData.data)) {
                    participants = apiData.data;
                    apiConnected = true;
                    console.log(`✅ API connectée - ${participants.length} participants récupérés`);
                } else if (Array.isArray(apiData)) {
                    participants = apiData;
                    apiConnected = true;
                    console.log(`✅ API connectée - ${participants.length} participants récupérés`);
                } else if (apiData && apiData.data && Array.isArray(apiData.data)) {
                    participants = apiData.data;
                    apiConnected = true;
                    console.log(`✅ API connectée - ${participants.length} participants récupérés`);
                } else {
                    console.log('⚠️ Format de données API inattendu:', typeof apiData, apiData);
                    // Fallback to mock data if API format is unexpected
                    participants = mockParticipants;
                }
            } catch (apiError) {
                console.warn('⚠️ Échec de connexion à l\'API backend:', apiError && apiError.message ? apiError.message : apiError);
                // Use mock data as fallback
                participants = mockParticipants;
            }
            
            // Always show pending registrations (frontend queue) regardless of API status
            try {
                const pending = (req.app && Array.isArray(req.app.locals._participantsPending)) ? req.app.locals._participantsPending : [];
                if (pending.length) {
                    console.log(`📋 ${pending.length} participants en file d'attente détectés`);
                    const pendingMapped = pending.map((p, idx) => ({
                        id: `queued-${idx}-${p.savedAt || Date.now()}`,
                        participant_id: `QUEUED-${idx}`,
                        first_name: p.first_name || p.firstName || '',
                        last_name: p.last_name || p.lastName || '',
                        name: ((p.first_name || p.firstName || '') + ' ' + (p.last_name || p.lastName || '')).trim() || 'Inscrit (en file)',
                        country: p.country || p.pays || 'N/A',
                        city: p.city || p.ville || 'N/A',
                        type: p.registration_type || p.type || p.registrationType || 'national',
                        registration_type: p.registration_type || p.type || p.registrationType || 'national',
                        status: 'En file d\'attente',
                        email: p.email || 'N/A',
                        whatsapp: p.whatsapp || p.whatsapp_opt || 'N/A',
                        queuedAt: p.savedAt || new Date().toISOString(),
                        raw: p
                    }));
                    // prepend pending so they appear first
                    participants = pendingMapped.concat(participants);
                    console.log(`✅ ${pendingMapped.length} participants en file ajoutés au dashboard`);
                }
            } catch (e) {
                console.error('Failed to map pending participants for dashboard view', e);
            }

            res.render('pages/dashboard-participants', {
                title: 'Participants - Dashboard',
                participants,
                stats: buildStats(),
                page: 'dashboard',
                apiConnected: apiConnected,
                totalParticipants: participants.length
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/dashboard-participants', { title: 'Participants - Dashboard', participants: [], stats: {}, page: 'dashboard' });
        }
    },

    badges: async (req, res) => {
        try {
            res.render('pages/dashboard-badges', {
                title: 'Badges - Dashboard',
                badges: mockBadges,
                stats: buildStats(),
                page: 'dashboard'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/dashboard-badges', { title: 'Badges - Dashboard', badges: [], stats: {}, page: 'dashboard' });
        }
    },

    emails: async (req, res) => {
        try {
            const emails = getEmails(req);
            buildStats.emailsLength = emails.length;
            res.render('pages/dashboard-emails', { title:'Emails - Dashboard', emails, stats: buildStats(), page:'dashboard' });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/dashboard-emails', { title: 'Emails - Dashboard', emails: [], stats: {}, page: 'dashboard' });
        }
    },

    blogs: async (req, res) => {
        try {
            const blogs = getBlogs(req).slice().sort((a,b)=> new Date(b.createdAt)-new Date(a.createdAt));
            res.render('pages/dashboard-blogs', {
                title: 'Blogs - Dashboard',
                blogs,
                stats: buildStats(),
                page: 'dashboard'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/dashboard-blogs', { title:'Blogs - Dashboard', blogs: [], stats:{}, page:'dashboard' });
        }
    },

    updateParticipantStatus: async (req, res) => {
        try {
            const { id } = req.params;
            const { status } = req.body;
            
            // Call backend API to update status
            const response = await fetch(`http://localhost:8000/api/participants/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            });
            
            if (response.ok) {
                res.json({ success: true, message: 'Statut mis à jour avec succès' });
            } else {
                throw new Error('Erreur lors de la mise à jour du statut');
            }
        } catch (error) {
            console.error('Erreur updateParticipantStatus:', error);
            res.status(500).json({ success: false, message: error.message });
        }
    },

    downloadParticipantPDF: async (req, res) => {
        try {
            const { id } = req.params;
            
            // Redirect to backend API for PDF download
            res.redirect(`http://localhost:8000/api/participants/${id}/pdf`);
        } catch (error) {
            console.error('Erreur downloadParticipantPDF:', error);
            res.status(500).json({ success: false, message: error.message });
        }
    },

    downloadParticipantBadge: async (req, res) => {
        try {
            const { id } = req.params;
            
            // Redirect to backend API for badge download
            res.redirect(`http://localhost:8000/api/participants/${id}/badge`);
        } catch (error) {
            console.error('Erreur downloadParticipantBadge:', error);
            res.status(500).json({ success: false, message: error.message });
        }
    },

    viewParticipant: async (req, res) => {
        try {
            const { id } = req.params;
            
            // Redirect to backend API for participant view
            res.redirect(`http://localhost:8000/api/participants/${id}`);
        } catch (error) {
            console.error('Erreur viewParticipant:', error);
            res.status(500).json({ success: false, message: error.message });
        }
    }
};

module.exports = dashboardController;