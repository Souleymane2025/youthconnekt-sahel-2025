const dataService = require('../services/dataService');
const laravelApi = require('../services/laravelApiService');
const partnerService = require('../services/partnerService');
const path = require('path');
const fs = require('fs').promises;

const adminController = {
    // Page de login
    loginPage: (req, res) => {
        res.render('pages/admin-login', {
            title: 'Connexion Admin - YouthConnekt Sahel 2025',
            error: req.query.error
        });
    },

    // Connexion admin
    login: (req, res) => {
        const { username, password } = req.body;
        
        if (username === 'admin' && password === 'admin123') {
            // Créer une session admin
            req.session.isAuthenticated = true;
            req.session.adminToken = 'admin_token_2025';
            req.session.adminUser = 'admin';
            
            // Définir un cookie admin
            res.cookie('adminToken', 'admin_token_2025', { 
                httpOnly: true, 
                secure: false, 
                maxAge: 24 * 60 * 60 * 1000 // 24 heures
            });
            res.cookie('adminUser', 'admin', { 
                httpOnly: false, 
                secure: false, 
                maxAge: 24 * 60 * 60 * 1000 
            });
            
            res.json({
                success: true,
                message: 'Connexion réussie',
                token: 'admin_token_2025',
                redirect: '/admin/dashboard'
            });
        } else {
            res.status(401).json({
                success: false,
                message: 'Nom d\'utilisateur ou mot de passe incorrect'
            });
        }
    },

    // Déconnexion admin
    logout: (req, res) => {
        req.session.destroy();
        res.clearCookie('adminToken');
        res.clearCookie('adminUser');
        res.redirect('/admin/login');
    },

    // Dashboard principal
    dashboard: (req, res) => {
        try {
            // Récupérer les statistiques des partenaires
            const partnerStats = partnerService.getStats();
            
            console.log('🎯 Dashboard: Utilisation du template admin-dashboard-new');
            res.render('pages/admin-dashboard-new', {
                title: 'Dashboard Admin - YouthConnekt Sahel 2025',
                user: req.adminUser || 'admin',
                partnerStats: partnerStats
            });
        } catch (error) {
            console.error('❌ Erreur dashboard:', error);
            console.log('🎯 Dashboard (catch): Utilisation du template admin-dashboard-new');
            res.render('pages/admin-dashboard-new', {
                title: 'Dashboard Admin - YouthConnekt Sahel 2025',
                user: req.adminUser || 'admin',
                partnerStats: {
                    partners: { total: 0, pending: 0, approved: 0, rejected: 0 },
                    sponsors: { total: 0, pending: 0, approved: 0, rejected: 0 },
                    stands: { total: 0, pending: 0, approved: 0, rejected: 0 }
                }
            });
        }
    },

    // Page de gestion des participants
    participantsPage: (req, res) => {
        try {
            // Récupérer les participants depuis le service de données
            const participants = dataService.getParticipants();
            
            res.render('pages/admin-participants-simple', {
                title: 'Gestion des Participants - Dashboard Admin',
                user: req.adminUser || 'admin',
                participants: participants || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des participants:', error);
            res.render('pages/admin-participants-simple', {
                title: 'Gestion des Participants - Dashboard Admin',
                user: req.adminUser || 'admin',
                participants: [],
                error: 'Erreur lors du chargement des participants'
            });
        }
    },

    // Obtenir les statistiques
    getStats: async (req, res) => {
        try {
            const axios = require('axios');
            const API_BASE_URL = process.env.API_BASE_URL || 'http://localhost:8000/api';
            
            // Récupérer les statistiques depuis l'API Laravel
            let apiStats = {};
            try {
                const response = await axios.get(`${API_BASE_URL}/admin/stats`);
                apiStats = response.data.data;
            } catch (apiError) {
                console.error('Erreur API Laravel:', apiError.message);
                // Fallback vers les données locales
                const partnerStats = partnerService.getStats();
                apiStats = {
                    participants: {
                        total: 0,
                        pending: 0,
                        confirmed: 0,
                        rejected: 0
                    },
                    blogs: {
                        total: 0,
                        published: 0,
                        draft: 0
                    },
                    partners: partnerStats.partners,
                    sponsors: partnerStats.sponsors,
                    stands: partnerStats.stands,
                    messages: {
                        total: 0,
                        unread: 0,
                        read: 0
                    }
                };
            }
            
            res.json({
                success: true,
                stats: apiStats
            });
        } catch (error) {
            console.error('Erreur récupération stats:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des statistiques'
            });
        }
    },

    // Page de gestion des messages
    messagesPage: (req, res) => {
        try {
            const messages = dataService.getMessages();
            res.render('pages/admin-messages-new', {
                title: 'Gestion des Messages - Dashboard Admin',
                user: req.adminUser || 'admin',
                messages: messages || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des messages:', error);
            res.render('pages/admin-messages-new', {
                title: 'Gestion des Messages - Dashboard Admin',
                user: req.adminUser || 'admin',
                messages: [],
                error: 'Erreur lors du chargement des messages'
            });
        }
    },

    // Page de gestion des blogs
    blogsPage: (req, res) => {
        try {
            const blogs = dataService.getBlogs();
            res.render('pages/admin-blogs', {
                title: 'Gestion des Blogs - Dashboard Admin',
                user: req.adminUser || 'admin',
                blogs: blogs || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des blogs:', error);
            res.render('pages/admin-blogs', {
                title: 'Gestion des Blogs - Dashboard Admin',
                user: req.adminUser || 'admin',
                blogs: [],
                error: 'Erreur lors du chargement des blogs'
            });
        }
    },

    // Page de gestion des partenaires
    partnersPage: (req, res) => {
        try {
            const partners = dataService.getPartners();
            res.render('pages/admin-partners', {
                title: 'Gestion des Partenaires - Dashboard Admin',
                user: req.adminUser || 'admin',
                partners: partners || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des partenaires:', error);
            res.render('pages/admin-partners', {
                title: 'Gestion des Partenaires - Dashboard Admin',
                user: req.adminUser || 'admin',
                partners: [],
                error: 'Erreur lors du chargement des partenaires'
            });
        }
    },

    // Page de gestion des newsletters
    newslettersPage: (req, res) => {
        try {
            const newsletters = dataService.getNewsletters();
            res.render('pages/admin-newsletters', {
                title: 'Gestion des Newsletters - Dashboard Admin',
                user: req.adminUser || 'admin',
                newsletters: newsletters || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des newsletters:', error);
            res.render('pages/admin-newsletters', {
                title: 'Gestion des Newsletters - Dashboard Admin',
                user: req.adminUser || 'admin',
                newsletters: [],
                error: 'Erreur lors du chargement des newsletters'
            });
        }
    },

    // Page de gestion des badges
    badgesPage: (req, res) => {
        try {
            const badges = dataService.getBadges();
            res.render('pages/admin-badges', {
                title: 'Gestion des Badges - Dashboard Admin',
                user: req.adminUser || 'admin',
                badges: badges || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des badges:', error);
            res.render('pages/admin-badges', {
                title: 'Gestion des Badges - Dashboard Admin',
                user: req.adminUser || 'admin',
                badges: [],
                error: 'Erreur lors du chargement des badges'
            });
        }
    },

    // Page de gestion des sponsors
    sponsorsPage: (req, res) => {
        try {
            const sponsors = dataService.getSponsors();
            res.render('pages/admin-sponsors', {
                title: 'Gestion des Sponsors - Dashboard Admin',
                user: req.adminUser || 'admin',
                sponsors: sponsors || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des sponsors:', error);
            res.render('pages/admin-sponsors', {
                title: 'Gestion des Sponsors - Dashboard Admin',
                user: req.adminUser || 'admin',
                sponsors: [],
                error: 'Erreur lors du chargement des sponsors'
            });
        }
    },

    // Page de gestion des stands
    standsPage: (req, res) => {
        try {
            const stands = dataService.getStands();
            res.render('pages/admin-stands', {
                title: 'Gestion des Stands - Dashboard Admin',
                user: req.adminUser || 'admin',
                stands: stands || []
            });
        } catch (error) {
            console.error('Erreur lors du chargement des stands:', error);
            res.render('pages/admin-stands', {
                title: 'Gestion des Stands - Dashboard Admin',
                user: req.adminUser || 'admin',
                stands: [],
                error: 'Erreur lors du chargement des stands'
            });
        }
    },

    // Page des exports
    exportsPage: (req, res) => {
        try {
            const participants = dataService.getParticipants();
            const partners = dataService.getPartners();
            const sponsors = dataService.getSponsors();
            const stands = dataService.getStands();
            
            res.render('pages/admin-exports', {
                title: 'Exports - Dashboard Admin',
                user: req.adminUser || 'admin',
                stats: {
                    participants: participants.length,
                    partners: partners.length,
                    sponsors: sponsors.length,
                    stands: stands.length
                }
            });
        } catch (error) {
            console.error('Erreur lors du chargement des exports:', error);
            res.render('pages/admin-exports', {
                title: 'Exports - Dashboard Admin',
                user: req.adminUser || 'admin',
                stats: { participants: 0, partners: 0, sponsors: 0, stands: 0 },
                error: 'Erreur lors du chargement des exports'
            });
        }
    },

    // Page des paramètres
    settingsPage: (req, res) => {
        try {
            res.render('pages/admin-settings', {
                title: 'Paramètres - Dashboard Admin',
                user: req.adminUser || 'admin',
                settings: {
                    eventName: 'YouthConnekt Sahel 2025',
                    eventDate: '2025-03-15',
                    eventLocation: 'N\'Djamena, Tchad',
                    maxParticipants: 1000,
                    registrationOpen: true
                }
            });
        } catch (error) {
            console.error('Erreur lors du chargement des paramètres:', error);
            res.render('pages/admin-settings', {
                title: 'Paramètres - Dashboard Admin',
                user: req.adminUser || 'admin',
                error: 'Erreur lors du chargement des paramètres'
            });
        }
    },

    // Obtenir les participants depuis la base de données Laravel
    getParticipants: async (req, res) => {
        try {
            console.log('🔄 Récupération des participants depuis Laravel API...');
            const participants = await laravelApi.getParticipants();
            
            console.log(`✅ ${participants.length} participants récupérés depuis la base de données`);
            
            res.json({
                success: true,
                data: participants,
                source: 'laravel_database'
            });
        } catch (error) {
            console.error('❌ Erreur récupération participants:', error);
            
            // Fallback vers les données locales en cas d'erreur
            try {
                const localParticipants = dataService.getParticipants();
                console.log('⚠️ Utilisation des données locales en fallback');
                
                res.json({
                    success: true,
                    data: localParticipants,
                    source: 'local_fallback',
                    warning: 'Connexion à la base de données échouée, utilisation des données locales'
                });
            } catch (fallbackError) {
                res.status(500).json({
                    success: false,
                    message: 'Erreur lors de la récupération des participants'
                });
            }
        }
    },

    // Mettre à jour le statut d'un participant dans la base de données Laravel
    updateParticipantStatus: async (req, res) => {
        try {
            const { id } = req.params;
            const { status } = req.body;
            
            console.log(`🔄 Mise à jour du statut du participant ${id} vers ${status}`);
            
            const result = await laravelApi.updateParticipantStatus(id, status);
            
            res.json({
                success: true,
                message: 'Statut du participant mis à jour avec succès',
                data: result.data || result,
                source: 'laravel_database'
            });
        } catch (error) {
            console.error('❌ Erreur mise à jour statut participant:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la mise à jour du statut'
            });
        }
    },

    // Supprimer un participant de la base de données Laravel
    deleteParticipant: async (req, res) => {
        try {
            const { id } = req.params;
            
            console.log(`🔄 Suppression du participant ${id}`);
            
            const result = await laravelApi.deleteParticipant(id);
            
            res.json({
                success: true,
                message: 'Participant supprimé avec succès',
                source: 'laravel_database'
            });
        } catch (error) {
            console.error('❌ Erreur suppression participant:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la suppression du participant'
            });
        }
    },

    // Supprimer tous les participants de la base de données Laravel
    clearAllParticipants: async (req, res) => {
        try {
            console.log('🔄 Suppression de tous les participants');
            
            const result = await laravelApi.clearAllParticipants();
            
            res.json({
                success: true,
                message: result.message || 'Tous les participants supprimés avec succès',
                deleted_count: result.deleted_count || 0,
                source: 'laravel_database'
            });
        } catch (error) {
            console.error('❌ Erreur suppression tous participants:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la suppression de tous les participants'
            });
        }
    },

    // Créer un nouveau participant dans la base de données Laravel
    createParticipant: async (req, res) => {
        try {
            const participantData = req.body;
            
            console.log('🔄 Création d\'un nouveau participant:', participantData);
            
            const result = await laravelApi.createParticipant(participantData);
            
            res.json({
                success: true,
                message: 'Participant créé avec succès',
                data: result.data || result,
                source: 'laravel_database'
            });
        } catch (error) {
            console.error('❌ Erreur création participant:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la création du participant'
            });
        }
    },

    // Mettre à jour complètement un participant
    updateParticipant: async (req, res) => {
        try {
            const { id } = req.params;
            const participantData = req.body;
            
            console.log(`🔄 Mise à jour complète du participant ${id}:`, participantData);
            
            const result = await laravelApi.updateParticipant(id, participantData);
            
            res.json({
                success: true,
                message: 'Participant mis à jour avec succès',
                data: result.data || result,
                source: 'laravel_database'
            });
        } catch (error) {
            console.error('❌ Erreur mise à jour participant:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la mise à jour du participant'
            });
        }
    },

    // Envoyer une invitation par email (VRAIE API Laravel)
    sendInvitation: async (req, res) => {
        try {
            const { participantId } = req.body;
            
            console.log(`🔄 Envoi d'invitation OFFICIELLE au participant ${participantId}`);
            
            // Appeler la VRAIE API Laravel pour l'envoi d'email
            const result = await laravelApi.sendInvitation(participantId);
            
            console.log(`✅ Invitation officielle envoyée avec succès !`);
            console.log(`📧 Email avec signature de la présidente envoyé`);
            console.log(`🎫 Badge numérique généré`);
            
            res.json({
                success: true,
                message: `Invitation officielle envoyée avec succès ! Email avec signature de la présidente du Comité d'Organisation.`,
                data: result.data || result,
                source: 'laravel_email_system'
            });
        } catch (error) {
            console.error('❌ Erreur envoi invitation:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de l\'envoi de l\'invitation officielle: ' + error.message
            });
        }
    },

    // Envoyer des invitations en masse (VRAIE API Laravel)
    sendBulkInvitations: async (req, res) => {
        try {
            const { participantIds } = req.body;
            
            console.log(`🔄 Envoi d'invitations OFFICIELLES en masse à ${participantIds.length} participants`);
            
            // Appeler la VRAIE API Laravel pour l'envoi en masse
            const result = await laravelApi.sendBulkInvitations(participantIds);
            
            console.log(`✅ ${result.data.total_sent} invitations officielles envoyées avec succès !`);
            console.log(`📧 Emails avec signature de la présidente envoyés`);
            console.log(`🎫 Badges numériques générés`);
            
            res.json({
                success: true,
                message: `${result.data.total_sent} invitations officielles envoyées avec succès ! Emails avec signature de la présidente du Comité d'Organisation.`,
                data: result.data,
                source: 'laravel_email_system'
            });
        } catch (error) {
            console.error('❌ Erreur envoi invitations en masse:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de l\'envoi des invitations officielles en masse: ' + error.message
            });
        }
    },

    // Exporter les participants
    exportParticipants: async (req, res) => {
        try {
            const { format = 'csv' } = req.query;
            
            console.log(`🔄 Export des participants au format ${format}`);
            
            const participants = await laravelApi.getParticipants();
            
            if (format === 'csv') {
                // Générer le CSV
                const csvHeader = 'ID,Prénom,Nom,Email,Téléphone,Pays,Ville,Type,Statut,Organisation,Profession,Expérience,Date de création\n';
                const csvRows = participants.map(p => 
                    `${p.id},"${p.first_name}","${p.last_name}","${p.email}","${p.phone}","${p.country}","${p.city}","${p.type}","${p.status}","${p.organization || ''}","${p.occupation || ''}","${p.experience || 0}","${p.created_at}"`
                ).join('\n');
                
                const csvContent = csvHeader + csvRows;
                
                res.setHeader('Content-Type', 'text/csv');
                res.setHeader('Content-Disposition', `attachment; filename="participants_youthconnekt_${new Date().toISOString().split('T')[0]}.csv"`);
                res.send(csvContent);
                
            } else if (format === 'json') {
                res.setHeader('Content-Type', 'application/json');
                res.setHeader('Content-Disposition', `attachment; filename="participants_youthconnekt_${new Date().toISOString().split('T')[0]}.json"`);
                res.json({
                    exportDate: new Date().toISOString(),
                    totalParticipants: participants.length,
                    participants: participants
                });
                
            } else {
                res.status(400).json({
                    success: false,
                    message: 'Format d\'export non supporté. Utilisez "csv" ou "json"'
                });
            }
        } catch (error) {
            console.error('❌ Erreur export participants:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de l\'export des participants'
            });
        }
    },

    // Obtenir les messages
    getMessages: (req, res) => {
        try {
            const messages = dataService.getMessages();
            res.json({
                success: true,
                data: messages
            });
        } catch (error) {
            console.error('Erreur récupération messages:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des messages'
            });
        }
    },

    // Obtenir les blogs
    getBlogs: (req, res) => {
        try {
            const blogs = dataService.getBlogs();
            res.json({
                success: true,
                data: blogs
            });
        } catch (error) {
            console.error('Erreur récupération blogs:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des blogs'
            });
        }
    },

    // Créer un blog
    createBlog: (req, res) => {
        try {
            const blog = dataService.addBlog(req.body);
            res.json({
                success: true,
                message: 'Blog créé avec succès',
                data: blog
            });
        } catch (error) {
            console.error('Erreur création blog:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la création du blog'
            });
        }
    },

    // Mettre à jour un blog
    updateBlog: (req, res) => {
        try {
            const { id } = req.params;
            const updatedBlog = dataService.updateBlog(id, req.body);
            
            if (updatedBlog) {
                res.json({
                    success: true,
                    message: 'Blog mis à jour avec succès',
                    data: updatedBlog
                });
            } else {
                res.status(404).json({
                    success: false,
                    message: 'Blog non trouvé'
                });
            }
        } catch (error) {
            console.error('Erreur mise à jour blog:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la mise à jour du blog'
            });
        }
    },

    // Supprimer un blog
    deleteBlog: (req, res) => {
        try {
            const { id } = req.params;
            const success = dataService.deleteBlog(id);
            
            if (success) {
                res.json({
                    success: true,
                    message: 'Blog supprimé avec succès'
                });
            } else {
                res.status(404).json({
                    success: false,
                    message: 'Blog non trouvé'
                });
            }
        } catch (error) {
            console.error('Erreur suppression blog:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la suppression du blog'
            });
        }
    },

    // Publier un blog
    publishBlog: (req, res) => {
        try {
            const { id } = req.params;
            const updatedBlog = dataService.updateBlog(id, { 
                status: 'published', 
                published_at: new Date().toISOString() 
            });
            
            if (updatedBlog) {
                res.json({
                    success: true,
                    message: 'Blog publié avec succès',
                    data: updatedBlog
                });
            } else {
                res.status(404).json({
                    success: false,
                    message: 'Blog non trouvé'
                });
            }
        } catch (error) {
            console.error('Erreur publication blog:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la publication du blog'
            });
        }
    },

    // Obtenir les newsletters
    getNewsletters: (req, res) => {
        try {
            const newsletters = dataService.getNewsletters();
            res.json({
                success: true,
                data: newsletters
            });
        } catch (error) {
            console.error('Erreur récupération newsletters:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des newsletters'
            });
        }
    },

    // Créer une newsletter
    createNewsletter: (req, res) => {
        try {
            const newsletter = dataService.addNewsletter(req.body);
            res.json({
                success: true,
                message: 'Newsletter créée avec succès',
                data: newsletter
            });
        } catch (error) {
            console.error('Erreur création newsletter:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la création de la newsletter'
            });
        }
    },

    // Obtenir les badges
    getBadges: (req, res) => {
        try {
            const badges = dataService.getBadges();
            res.json({
                success: true,
                data: badges
            });
        } catch (error) {
            console.error('Erreur récupération badges:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des badges'
            });
        }
    },

    // Générer des badges
    generateBadges: (req, res) => {
        try {
            const badge = dataService.addBadge(req.body);
            res.json({
                success: true,
                message: 'Badge généré avec succès',
                data: badge
            });
        } catch (error) {
            console.error('Erreur génération badge:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la génération du badge'
            });
        }
    },

    // Obtenir les partenaires
    getPartners: (req, res) => {
        try {
            const partners = partnerService.getAllPartners();
            res.json({
                success: true,
                data: partners
            });
        } catch (error) {
            console.error('Erreur récupération partenaires:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des partenaires'
            });
        }
    },

    // Mettre à jour le statut d'un partenaire
    updatePartnerStatus: (req, res) => {
        try {
            const { id } = req.params;
            const { status } = req.body;
            const updatedPartner = partnerService.updatePartnerStatus(id, status);
            
            if (updatedPartner) {
                res.json({
                    success: true,
                    message: 'Statut du partenaire mis à jour',
                    data: updatedPartner
                });
            } else {
                res.status(404).json({
                    success: false,
                    message: 'Partenaire non trouvé'
                });
            }
        } catch (error) {
            console.error('Erreur mise à jour partenaire:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la mise à jour du partenaire'
            });
        }
    },

    // Obtenir les sponsors
    getSponsors: (req, res) => {
        try {
            const sponsors = partnerService.getAllSponsors();
            res.json({
                success: true,
                data: sponsors
            });
        } catch (error) {
            console.error('Erreur récupération sponsors:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des sponsors'
            });
        }
    },

    // Mettre à jour le statut d'un sponsor
    updateSponsorStatus: (req, res) => {
        try {
            const { id } = req.params;
            const { status } = req.body;
            const updatedSponsor = partnerService.updateSponsorStatus(id, status);
            
            if (updatedSponsor) {
                res.json({
                    success: true,
                    message: 'Statut du sponsor mis à jour',
                    data: updatedSponsor
                });
            } else {
                res.status(404).json({
                    success: false,
                    message: 'Sponsor non trouvé'
                });
            }
        } catch (error) {
            console.error('Erreur mise à jour sponsor:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la mise à jour du sponsor'
            });
        }
    },

    // Obtenir les stands
    getStands: (req, res) => {
        try {
            const stands = partnerService.getAllStands();
            res.json({
                success: true,
                data: stands
            });
        } catch (error) {
            console.error('Erreur récupération stands:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des stands'
            });
        }
    },

    // Mettre à jour le statut d'un stand
    updateStandStatus: (req, res) => {
        try {
            const { id } = req.params;
            const { status } = req.body;
            const updatedStand = partnerService.updateStandStatus(id, status);
            
            if (updatedStand) {
                res.json({
                    success: true,
                    message: 'Statut du stand mis à jour',
                    data: updatedStand
                });
            } else {
                res.status(404).json({
                    success: false,
                    message: 'Stand non trouvé'
                });
            }
        } catch (error) {
            console.error('Erreur mise à jour stand:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la mise à jour du stand'
            });
        }
    }
};

module.exports = adminController;
