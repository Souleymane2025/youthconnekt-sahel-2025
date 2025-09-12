const apiService = require('../services/apiService');
const dataService = require('../services/dataService');

const homeController = {
    // Page d'accueil
    index: async (req, res) => {
        try {
            // Récupérer les blogs publiés
            const allBlogs = dataService.getBlogs();
            const publishedBlogs = allBlogs.filter(blog => blog.status === 'published').slice(0, 3);
            
            res.render('pages/home', {
                title: 'YouthConnekt Sahel 2025 - Forum de la Jeunesse',
                sponsors: [],
                partners: [],
                blogs: publishedBlogs,
                page: 'home'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/home', {
                title: 'YouthConnekt Sahel 2025',
                sponsors: [],
                partners: [],
                blogs: [],
                page: 'home'
            });
        }
    },

    // Page à propos
    about: (req, res) => {
        res.render('pages/about', {
            title: 'À propos - YouthConnekt Sahel 2025',
            page: 'about'
        });
    },

    // Page programme
    program: (req, res) => {
        res.render('pages/program', {
            title: 'Programme - YouthConnekt Sahel 2025',
            page: 'program'
        });
    },

    // Page inscription
    registration: (req, res) => {
        res.render('pages/registration', {
            title: 'Inscription - YouthConnekt Sahel 2025',
            page: 'registration'
        });
    },

    // Page intervenants
    speakers: (req, res) => {
        res.render('pages/speakers', {
            title: 'Nos Intervenants - YouthConnekt Sahel 2025',
            page: 'speakers'
        });
    },

    // Page de confirmation d'inscription
    registrationSuccess: (req, res) => {
        res.render('pages/registration-success', {
            title: 'Inscription Réussie - YouthConnekt Sahel 2025',
            page: 'registration-success'
        });
    },

    // Page blog - liste des articles
    blog: (req, res) => {
        try {
            const allBlogs = dataService.getBlogs();
            const publishedBlogs = allBlogs.filter(blog => blog.status === 'published');
            
            res.render('pages/blog', {
                title: 'Blog - YouthConnekt Sahel 2025',
                blogs: publishedBlogs,
                page: 'blog'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/blog', {
                title: 'Blog - YouthConnekt Sahel 2025',
                blogs: [],
                page: 'blog'
            });
        }
    },

    // Page blog - article individuel
    blogPost: (req, res) => {
        try {
            const { id } = req.params;
            const allBlogs = dataService.getBlogs();
            const blog = allBlogs.find(b => b.id === id && b.status === 'published');
            
            if (!blog) {
                return res.status(404).render('pages/404', {
                    title: 'Article non trouvé - YouthConnekt Sahel 2025',
                    page: '404'
                });
            }
            
            // Incrémenter les vues
            dataService.updateBlog(id, { views: (blog.views || 0) + 1 });
            
            res.render('pages/blog-post', {
                title: `${blog.title} - YouthConnekt Sahel 2025`,
                blog: blog,
                page: 'blog'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.status(500).render('pages/500', {
                title: 'Erreur - YouthConnekt Sahel 2025',
                page: '500'
            });
        }
    },

    // Page de contact
    contact: (req, res) => {
        res.render('pages/contact', {
            title: 'Contact - YouthConnekt Sahel 2025',
            page: 'contact'
        });
    },

    // Envoyer un message de contact
    sendContactMessage: (req, res) => {
        try {
            const { name, email, subject, message, phone } = req.body;
            
            // Valider les données
            if (!name || !email || !subject || !message) {
                return res.status(400).json({
                    success: false,
                    message: 'Tous les champs obligatoires doivent être remplis'
                });
            }
            
            // Ajouter le message via le service de données
            const newMessage = dataService.addMessage({
                name,
                email,
                subject,
                message,
                phone: phone || '',
                type: 'contact'
            });
            
            res.json({
                success: true,
                message: 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
                data: {
                    id: newMessage.id,
                    subject: newMessage.subject
                }
            });
        } catch (error) {
            console.error('Erreur lors de l\'envoi du message:', error);
            res.status(500).json({
                success: false,
                message: 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.'
            });
        }
    }
};

module.exports = homeController;