// Contrôleur Contact uniquement (nettoyé)
const dataService = require('../services/dataService');

const contactController = {
    index: (req, res) => {
        console.log('[CONTACT] GET /contact');
        res.render('pages/contact', {
            title: 'Contact - YouthConnekt Sahel 2025',
            page: 'contact',
            success: req.query.success,
            error: req.query.error
        });
    },
    store: async (req, res) => {
        try {
            console.log('[CONTACT] POST /contact payload:', req.body);
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
            
            // Envoyer l'email de confirmation via Laravel
            let emailSent = false;
            let emailError = null;
            
            try {
                const axios = require('axios');
                const emailResponse = await axios.post('http://localhost:8000/api/email/contact-confirmation', {
                    name,
                    email,
                    subject,
                    message,
                    phone: phone || ''
                });
                console.log('Email de confirmation envoyé:', emailResponse.data);
                emailSent = true;
            } catch (error) {
                console.error('Erreur envoi email:', error.message);
                emailError = error.message;
                // Ne pas faire échouer la soumission si l'email échoue
            }
            
            // Message de confirmation adapté selon le statut de l'email
            let confirmationMessage = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
            
            if (emailSent) {
                confirmationMessage = 'Votre message a été envoyé avec succès. Un email de confirmation vous a été envoyé. Nous vous répondrons dans les plus brefs délais.';
            } else if (emailError) {
                confirmationMessage = 'Votre message a été envoyé avec succès. Note: L\'email de confirmation n\'a pas pu être envoyé pour le moment, mais votre message a bien été reçu. Nous vous répondrons dans les plus brefs délais.';
            }
            
            res.json({
                success: true,
                message: confirmationMessage,
                data: {
                    id: newMessage.id,
                    subject: newMessage.subject,
                    emailSent: emailSent,
                    emailError: emailError
                }
            });
        } catch (error) {
            console.error('[CONTACT] ERROR:', error);
            res.status(500).json({
                success: false,
                message: 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.'
            });
        }
    }
};

module.exports = { contactController };