const partnerService = require('../services/partnerService');

const partnerFormController = {
    // Afficher le formulaire de candidature partenaire
    showPartnerForm: (req, res) => {
        res.render('pages/partner-form', {
            title: 'Devenir Partenaire - YouthConnekt Sahel 2025',
            page: 'partner-form',
            type: 'partner'
        });
    },

    // Afficher le formulaire de candidature sponsor
    showSponsorForm: (req, res) => {
        res.render('pages/partner-form', {
            title: 'Devenir Sponsor - YouthConnekt Sahel 2025',
            page: 'sponsor-form',
            type: 'sponsor'
        });
    },

    // Afficher le formulaire de réservation de stand
    showStandForm: (req, res) => {
        res.render('pages/stand-form', {
            title: 'Réserver un Stand - YouthConnekt Sahel 2025',
            page: 'stand-form'
        });
    },

    // Traiter la soumission du formulaire partenaire
    submitPartnerForm: async (req, res) => {
        try {
            const partnerData = {
                type: 'partner',
                companyName: req.body.companyName,
                contactPerson: req.body.contactPerson,
                email: req.body.email,
                phone: req.body.phone,
                website: req.body.website,
                address: req.body.address,
                country: req.body.country,
                partnershipType: req.body.partnershipType,
                description: req.body.description,
                expectedBenefits: req.body.expectedBenefits,
                previousExperience: req.body.previousExperience,
                budget: req.body.budget,
                logo: req.body.logo || null
            };

            // Sauvegarder localement
            const newPartner = partnerService.addPartner(partnerData);
            
            // Envoyer l'email de confirmation via Laravel
            try {
                const axios = require('axios');
                const emailResponse = await axios.post('http://localhost:8000/api/email/partner-confirmation', partnerData);
                console.log('Email de confirmation envoyé:', emailResponse.data);
            } catch (emailError) {
                console.error('Erreur envoi email:', emailError.message);
                // Ne pas faire échouer la soumission si l'email échoue
            }
            
            res.json({
                success: true,
                message: 'Votre candidature de partenariat a été soumise avec succès ! Un email de confirmation vous a été envoyé.',
                data: newPartner
            });
        } catch (error) {
            console.error('Erreur soumission partenaire:', error);
            res.json({
                success: false,
                message: 'Une erreur est survenue lors de la soumission. Veuillez réessayer.'
            });
        }
    },

    // Traiter la soumission du formulaire sponsor
    submitSponsorForm: async (req, res) => {
        try {
            const sponsorData = {
                type: 'sponsor',
                companyName: req.body.companyName,
                contactPerson: req.body.contactPerson,
                email: req.body.email,
                phone: req.body.phone,
                website: req.body.website,
                address: req.body.address,
                country: req.body.country,
                sponsorshipLevel: req.body.sponsorshipLevel,
                description: req.body.description,
                marketingObjectives: req.body.marketingObjectives,
                targetAudience: req.body.targetAudience,
                budget: req.body.budget,
                additionalServices: req.body.additionalServices,
                logo: req.body.logo || null
            };

            // Sauvegarder localement
            const newSponsor = partnerService.addSponsor(sponsorData);
            
            // Envoyer l'email de confirmation via Laravel
            try {
                const axios = require('axios');
                const emailResponse = await axios.post('http://localhost:8000/api/email/partner-confirmation', sponsorData);
                console.log('Email de confirmation envoyé:', emailResponse.data);
            } catch (emailError) {
                console.error('Erreur envoi email:', emailError.message);
                // Ne pas faire échouer la soumission si l'email échoue
            }
            
            res.json({
                success: true,
                message: 'Votre candidature de sponsoring a été soumise avec succès ! Un email de confirmation vous a été envoyé.',
                data: newSponsor
            });
        } catch (error) {
            console.error('Erreur soumission sponsor:', error);
            res.json({
                success: false,
                message: 'Une erreur est survenue lors de la soumission. Veuillez réessayer.'
            });
        }
    },

    // Traiter la soumission du formulaire de stand
    submitStandForm: async (req, res) => {
        try {
            const standData = {
                companyName: req.body.companyName,
                contactPerson: req.body.contactPerson,
                email: req.body.email,
                phone: req.body.phone,
                website: req.body.website,
                address: req.body.address,
                country: req.body.country,
                standType: req.body.standType,
                standSize: req.body.standSize,
                description: req.body.description,
                products: req.body.products,
                expectedVisitors: req.body.expectedVisitors,
                additionalServices: req.body.additionalServices,
                budget: req.body.budget,
                logo: req.body.logo || null
            };

            // Sauvegarder localement
            const newStand = partnerService.addStand(standData);
            
            // Envoyer l'email de confirmation via Laravel
            try {
                const axios = require('axios');
                const emailResponse = await axios.post('http://localhost:8000/api/email/stand-confirmation', standData);
                console.log('Email de confirmation envoyé:', emailResponse.data);
            } catch (emailError) {
                console.error('Erreur envoi email:', emailError.message);
                // Ne pas faire échouer la soumission si l'email échoue
            }
            
            res.json({
                success: true,
                message: 'Votre demande de réservation de stand a été soumise avec succès ! Un email de confirmation vous a été envoyé.',
                data: newStand
            });
        } catch (error) {
            console.error('Erreur soumission stand:', error);
            res.json({
                success: false,
                message: 'Une erreur est survenue lors de la soumission. Veuillez réessayer.'
            });
        }
    }
};

module.exports = partnerFormController;
