const axios = require('axios');
const dataService = require('../services/dataService');

const invitationController = {
    // Envoyer une invitation individuelle
    sendInvitation: async (req, res) => {
        try {
            const { participantId } = req.body;
            
            // Récupérer les données du participant
            const participants = dataService.getParticipants();
            const participant = participants.find(p => p.id === participantId);
            
            if (!participant) {
                return res.status(404).json({
                    success: false,
                    message: 'Participant non trouvé'
                });
            }

            // Données d'invitation
            const invitationData = {
                participant: participant,
                event_name: 'Forum YouthConnekt Sahel 2025',
                event_date: '15-17 Mars 2025',
                event_location: 'N\'Djamena, Tchad',
                invitation_code: `YC2025-${participant.id}`,
                qr_code: `https://youthconnekt.td/invitation/${participant.id}`,
                organizer: 'YouthConnekt Tchad',
                contact_email: 'contact@youthconnekt.td',
                contact_phone: '+235 66 16 17 53'
            };

            // Envoyer l'invitation via l'API Laravel
            let emailSent = false;
            let emailError = null;

            try {
                const emailResponse = await axios.post('http://localhost:8000/api/invitations/send', {
                    participant: participant,
                    invitationData: invitationData
                }, {
                    timeout: 10000
                });
                
                console.log('Invitation envoyée:', emailResponse.data);
                emailSent = true;
            } catch (error) {
                console.error('Erreur envoi invitation:', error.message);
                emailError = error.message;
            }

            // Mettre à jour le statut du participant
            const updatedParticipant = dataService.updateParticipant(participantId, {
                invitation_sent: true,
                invitation_sent_at: new Date().toISOString(),
                invitation_code: invitationData.invitation_code
            });

            // Message de confirmation adapté
            let confirmationMessage = 'Invitation envoyée avec succès.';
            if (emailSent) {
                confirmationMessage = 'Invitation envoyée avec succès. Un email de confirmation a été envoyé au participant.';
            } else if (emailError) {
                confirmationMessage = 'Invitation préparée avec succès. Note: L\'email n\'a pas pu être envoyé pour le moment, mais l\'invitation a été générée.';
            }

            res.json({
                success: true,
                message: confirmationMessage,
                data: {
                    participant: updatedParticipant,
                    invitationData: invitationData,
                    emailSent: emailSent,
                    emailError: emailError
                }
            });

        } catch (error) {
            console.error('Erreur envoi invitation:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de l\'envoi de l\'invitation'
            });
        }
    },

    // Envoyer des invitations en masse
    bulkSendInvitations: async (req, res) => {
        try {
            const { participantIds, status } = req.body;
            
            const participants = dataService.getParticipants();
            let results = [];
            let successCount = 0;
            let errorCount = 0;

            for (const participantId of participantIds) {
                try {
                    const participant = participants.find(p => p.id === participantId);
                    if (!participant) continue;

                    // Données d'invitation
                    const invitationData = {
                        participant: participant,
                        event_name: 'Forum YouthConnekt Sahel 2025',
                        event_date: '15-17 Mars 2025',
                        event_location: 'N\'Djamena, Tchad',
                        invitation_code: `YC2025-${participant.id}`,
                        qr_code: `https://youthconnekt.td/invitation/${participant.id}`,
                        organizer: 'YouthConnekt Tchad',
                        contact_email: 'contact@youthconnekt.td',
                        contact_phone: '+235 66 16 17 53'
                    };

                    // Envoyer l'invitation
                    let emailSent = false;
                    try {
                        const emailResponse = await axios.post('http://localhost:8000/api/invitations/send', {
                            participant: participant,
                            invitationData: invitationData
                        }, {
                            timeout: 10000
                        });
                        emailSent = true;
                        successCount++;
                    } catch (error) {
                        console.error(`Erreur invitation ${participantId}:`, error.message);
                        errorCount++;
                    }

                    // Mettre à jour le participant
                    dataService.updateParticipant(participantId, {
                        invitation_sent: true,
                        invitation_sent_at: new Date().toISOString(),
                        invitation_code: invitationData.invitation_code
                    });

                    results.push({
                        participantId: participantId,
                        participantName: `${participant.first_name} ${participant.last_name}`,
                        emailSent: emailSent,
                        success: true
                    });

                } catch (error) {
                    errorCount++;
                    results.push({
                        participantId: participantId,
                        success: false,
                        error: error.message
                    });
                }
            }

            res.json({
                success: true,
                message: `Invitations traitées: ${successCount} réussies, ${errorCount} échecs`,
                data: {
                    total: participantIds.length,
                    success: successCount,
                    errors: errorCount,
                    results: results
                }
            });

        } catch (error) {
            console.error('Erreur envoi invitations en masse:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de l\'envoi des invitations en masse'
            });
        }
    },

    // Télécharger une invitation
    downloadInvitation: async (req, res) => {
        try {
            const { participantId } = req.params;
            
            // Récupérer les données du participant
            const participants = dataService.getParticipants();
            const participant = participants.find(p => p.id === participantId);
            
            if (!participant) {
                return res.status(404).json({
                    success: false,
                    message: 'Participant non trouvé'
                });
            }

            // Données d'invitation
            const invitationData = {
                participant: participant,
                event_name: 'Forum YouthConnekt Sahel 2025',
                event_date: '15-17 Mars 2025',
                event_location: 'N\'Djamena, Tchad',
                invitation_code: `YC2025-${participant.id}`,
                qr_code: `https://youthconnekt.td/invitation/${participant.id}`,
                organizer: 'YouthConnekt Tchad',
                contact_email: 'contact@youthconnekt.td',
                contact_phone: '+235 66 16 17 53'
            };

            // Télécharger l'invitation via l'API Laravel
            try {
                const downloadResponse = await axios.get(`http://localhost:8000/api/invitations/download/${participantId}`, {
                    timeout: 10000
                });
                
                res.json({
                    success: true,
                    message: 'Invitation téléchargée avec succès',
                    data: {
                        participant: participant,
                        invitationData: invitationData,
                        downloadUrl: downloadResponse.data.download_url || '#'
                    }
                });
            } catch (error) {
                console.error('Erreur téléchargement invitation:', error.message);
                
                // Fallback: retourner les données pour génération côté client
                res.json({
                    success: true,
                    message: 'Données d\'invitation récupérées',
                    data: {
                        participant: participant,
                        invitationData: invitationData,
                        downloadUrl: '#',
                        fallback: true
                    }
                });
            }

        } catch (error) {
            console.error('Erreur téléchargement invitation:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors du téléchargement de l\'invitation'
            });
        }
    },

    // Obtenir les statistiques des invitations
    getInvitationStats: (req, res) => {
        try {
            const participants = dataService.getParticipants();
            
            const stats = {
                total: participants.length,
                invitations_sent: participants.filter(p => p.invitation_sent).length,
                invitations_pending: participants.filter(p => !p.invitation_sent).length,
                by_status: {
                    confirmed: participants.filter(p => p.status === 'confirmed').length,
                    pending: participants.filter(p => p.status === 'pending').length,
                    rejected: participants.filter(p => p.status === 'rejected').length
                },
                by_country: participants.reduce((acc, p) => {
                    acc[p.country] = (acc[p.country] || 0) + 1;
                    return acc;
                }, {}),
                by_type: {
                    national: participants.filter(p => p.registration_type === 'national').length,
                    international: participants.filter(p => p.registration_type === 'international').length
                }
            };

            res.json({
                success: true,
                data: stats
            });

        } catch (error) {
            console.error('Erreur récupération stats invitations:', error);
            res.status(500).json({
                success: false,
                message: 'Erreur lors de la récupération des statistiques'
            });
        }
    }
};

module.exports = invitationController;