const apiService = require('../services/apiService');

const participantController = {
    showRegistrationForm: (req, res) => {
        console.log('[REGISTRATION] GET /participants/register');
        try {
            res.render('pages/registration', {
                title: 'Inscription - YouthConnekt Sahel 2025',
                page: 'registration'
            });
        } catch (e) {
            console.error('[REGISTRATION] render error', e);
            res.status(500).send('Erreur rendu inscription');
        }
    },

    register: async (req, res) => {
        try {
            console.log('[REGISTRATION] POST payload:', req.body);

            // Forward registration to backend API
            const payloadRaw = req.body || {};
            // normalize keys: camelCase -> snake_case to match backend expected fields
            const payload = {};
            Object.keys(payloadRaw).forEach(k => {
                const snake = k.replace(/([A-Z])/g, '_$1').toLowerCase();
                payload[snake] = payloadRaw[k];
            });

            // If files were uploaded (via multer), forward as multipart/form-data to backend
            let apiResp;
            if (req.files && Object.keys(req.files).length) {
                const axios = require('axios');
                const FormData = require('form-data');
                const fs = require('fs');
                const form = new FormData();
                // append text fields
                Object.keys(payload).forEach(k => {
                    // ensure arrays (interests) are handled
                    if (Array.isArray(payload[k])) {
                        payload[k].forEach(v => form.append(k, v));
                    } else {
                        form.append(k, payload[k]);
                    }
                });
                // append files
                Object.keys(req.files).forEach(field => {
                    const arr = req.files[field];
                    if (Array.isArray(arr)) {
                        arr.forEach(fileObj => {
                            form.append(field, fs.createReadStream(fileObj.path), { filename: fileObj.filename });
                        });
                    }
                });

                try {
                    const resp = await axios.post((process.env.API_BASE_URL || 'http://localhost:8000/api') + '/participants', form, { headers: form.getHeaders() });
                    apiResp = resp && resp.data ? resp.data : resp;
                } catch (e) {
                    // convert to structured error for fallback
                    apiResp = { success: false, error: true, message: e && e.message, code: e && e.code, details: e };
                }
            } else {
                apiResp = await apiService.registerParticipant(payload);
            }

            // If apiService returned a structured error, treat as backend unreachable
            if (apiResp && apiResp.error) {
                const pending = Object.assign({}, payload, { savedAt: new Date().toISOString(), apiError: apiResp });
                // include any uploaded file paths so retry can resend them
                if (req.files && Object.keys(req.files).length) {
                    pending._files = [];
                    Object.keys(req.files).forEach(field => {
                        req.files[field].forEach(f => {
                            pending._files.push({ field, path: f.path, filename: f.filename });
                        });
                    });
                }
                if (req.app && Array.isArray(req.app.locals._participantsPending)) {
                    if (req.app.locals._participantsPending.length > 2000) req.app.locals._participantsPending.shift();
                    req.app.locals._participantsPending.push(pending);
                }
                return res.status(202).json({ success: true, message: 'Inscription reçue et mise en file (backend indisponible). Vous recevrez un email de confirmation une fois traitée.' });
            }

            // Optionally keep a local copy for temporary frontend stores
            if (req.app && req.app.locals && Array.isArray(req.app.locals._newsletterStore) && payload.email) {
                req.app.locals._newsletterStore.push({ email: payload.email, date: new Date().toISOString() });
            }

            const message = (apiResp && (apiResp.message || apiResp.msg)) ? (apiResp.message || apiResp.msg) : 'Inscription réussie !';
            res.json({ success: true, message, data: apiResp && apiResp.data ? apiResp.data : apiResp });
        } catch (error) {
            console.error('[REGISTRATION] ERROR', error);
            // If backend is unreachable (ECONNREFUSED), save the participant locally and return success-like response
            const isConnRefused = error && (error.code === 'ECONNREFUSED' || (error.errors && error.errors.some(e=>e && e.code === 'ECONNREFUSED')));
            if (isConnRefused && req.app && Array.isArray(req.app.locals._participantsPending)) {
                const pending = Object.assign({}, req.body || {}, { savedAt: new Date().toISOString() });
                // keep only limited size
                if (req.app.locals._participantsPending.length > 2000) req.app.locals._participantsPending.shift();
                req.app.locals._participantsPending.push(pending);
                return res.status(202).json({ success: true, message: 'Inscription reçue et mise en file (backend indisponible). Vous recevrez un email de confirmation une fois traitée.' });
            }

            const status = error && error.response && error.response.status ? error.response.status : 500;
            const message = error && error.response && error.response.data && error.response.data.message ? error.response.data.message : 'Erreur lors de l\'inscription';
            res.status(status).json({ success: false, message });
        }
    },

    nationalRegistration: (req, res) => {
        res.render('pages/registration', {
            title: 'Inscription Nationale - YouthConnekt Sahel 2025',
            page: 'registration',
            registrationType: 'national'
        });
    },

    internationalRegistration: (req, res) => {
        res.render('pages/registration', {
            title: 'Inscription Internationale - YouthConnekt Sahel 2025',
            page: 'registration',
            registrationType: 'international'
        });
    },

    showCompleteRegistrationForm: (req, res) => {
        res.render('pages/registration-complete', {
            title: 'Inscription Complète - YouthConnekt Sahel 2025',
            page: 'registration',
            registrationType: req.query.type || 'national'
        });
    },

    registerComplete: async (req, res) => {
        try {
            console.log('[REGISTRATION COMPLETE] Request body:', req.body);
            console.log('[REGISTRATION COMPLETE] Content-Type:', req.get('Content-Type'));
            
            // Generate unique participant ID
            const participantId = `FYCS2025${Date.now().toString().slice(-6)}${Math.floor(Math.random() * 1000).toString().padStart(3, '0')}`;
            
            // Prepare data for backend - handle both form data and JSON
            const participantData = {
                first_name: req.body.first_name,
                last_name: req.body.last_name,
                email: req.body.email,
                phone: req.body.phone,
                whatsapp: req.body.whatsapp,
                birth_date: req.body.birth_date,
                gender: req.body.gender,
                country: req.body.country,
                manual_country: req.body.manual_country,
                city: req.body.city,
                province: req.body.province,
                occupation: req.body.occupation,
                organization: req.body.organization,
                interests: req.body.interests,
                experience: req.body.experience,
                motivation: req.body.motivation,
                handicap: req.body.handicap,
                handicap_type: req.body.handicap_type,
                handicap_accommodation: req.body.handicap_accommodation,
                registration_type: req.body.registration_type,
                terms: req.body.terms,
                newsletter: req.body.newsletter === 'on' || req.body.newsletter === true,
                photos: req.body.photos === 'on' || req.body.photos === true
            };
            
            // Remove undefined values
            Object.keys(participantData).forEach(key => {
                if (participantData[key] === undefined) {
                    delete participantData[key];
                }
            });
            
            console.log('[REGISTRATION COMPLETE] Prepared data:', participantData);

            // Handle file uploads
            if (req.files) {
                if (req.files.photo && req.files.photo[0]) {
                    participantData.photo_path = `/uploads/${req.files.photo[0].filename}`;
                }
                if (req.files.passport && req.files.passport[0]) {
                    participantData.passport_path = `/uploads/${req.files.passport[0].filename}`;
                }
                if (req.files.cv && req.files.cv[0]) {
                    participantData.cv_path = `/uploads/${req.files.cv[0].filename}`;
                }
            }

            // Send to backend API
            const apiService = require('../services/apiService');
            const apiResp = await apiService.registerParticipant(participantData);

            console.log('[REGISTRATION COMPLETE] API Response:', apiResp);

            // Check if API returned an error (structured error response)
            if (apiResp && apiResp.error) {
                // If it's a 409 conflict (duplicate email), return 409 status
                if (apiResp.message && apiResp.message.includes('déjà utilisée')) {
                    return res.status(409).json({
                        success: false,
                        message: apiResp.message
                    });
                }
                return res.status(400).json({
                    success: false,
                    message: apiResp.message || 'Erreur lors de l\'inscription'
                });
            }

            // If we get here, the API call was successful
            // Check if apiResp has an id (successful creation)
            if (apiResp && (apiResp.id || apiResp.success !== false)) {
                res.json({
                    success: true,
                    message: 'Inscription réussie ! Vous recevrez un email de confirmation.',
                    participant_id: participantId,
                    data: apiResp
                });
            } else {
                // Unexpected response format
                res.status(500).json({
                    success: false,
                    message: 'Réponse inattendue du serveur. Veuillez réessayer.'
                });
            }

        } catch (error) {
            console.error('[REGISTRATION COMPLETE] ERROR', error);
            
            // Provide more specific error messages
            let errorMessage = 'Erreur lors de l\'inscription';
            if (error.message) {
                if (error.message.includes('ECONNREFUSED')) {
                    errorMessage = 'Impossible de contacter le serveur backend. Veuillez réessayer plus tard.';
                } else if (error.message.includes('timeout')) {
                    errorMessage = 'Délai d\'attente dépassé. Veuillez réessayer.';
                } else {
                    errorMessage = error.message;
                }
            }
            
            res.status(500).json({
                success: false,
                message: errorMessage
            });
        }
    }
};

module.exports = participantController;