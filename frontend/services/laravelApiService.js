const axios = require('axios');

class LaravelApiService {
    constructor() {
        this.baseURL = 'http://localhost:8000/api';
        this.api = axios.create({
            baseURL: this.baseURL,
            timeout: 10000,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
    }

    // Participants
    async getParticipants() {
        try {
            const response = await this.api.get('/participants');
            return response.data.data || response.data;
        } catch (error) {
            console.error('Erreur récupération participants:', error.message);
            // Retourner des données de démonstration en cas d'erreur
            return this.getDemoParticipants();
        }
    }

    async getParticipant(id) {
        try {
            const response = await this.api.get(`/participants/${id}`);
            return response.data.data || response.data;
        } catch (error) {
            console.error('Erreur récupération participant:', error.message);
            return null;
        }
    }

    async createParticipant(participantData) {
        try {
            const response = await this.api.post('/participants', participantData);
            return response.data;
        } catch (error) {
            console.error('Erreur création participant:', error.message);
            throw error;
        }
    }

    async updateParticipant(id, participantData) {
        try {
            const response = await this.api.put(`/participants/${id}`, participantData);
            return response.data;
        } catch (error) {
            console.error('Erreur mise à jour participant:', error.message);
            throw error;
        }
    }

    async deleteParticipant(id) {
        try {
            const response = await this.api.delete(`/participants/${id}`);
            return response.data;
        } catch (error) {
            console.error('Erreur suppression participant:', error.message);
            throw error;
        }
    }

    async updateParticipantStatus(id, status) {
        try {
            const response = await this.api.put(`/participants/${id}/status`, { status });
            return response.data;
        } catch (error) {
            console.error('Erreur mise à jour statut:', error.message);
            throw error;
        }
    }

    async clearAllParticipants() {
        try {
            const response = await this.api.post('/participants/clear-all');
            return response.data;
        } catch (error) {
            console.error('Erreur suppression tous participants:', error.message);
            throw error;
        }
    }

    // Messages
    async getMessages() {
        try {
            const response = await this.api.get('/messages');
            return response.data.data || response.data;
        } catch (error) {
            console.error('Erreur récupération messages:', error.message);
            return [];
        }
    }

    // Blogs
    async getBlogs() {
        try {
            const response = await this.api.get('/blogs');
            return response.data.data || response.data;
        } catch (error) {
            console.error('Erreur récupération blogs:', error.message);
            return [];
        }
    }

    // Partners
    async getPartners() {
        try {
            const response = await this.api.get('/partners');
            return response.data.data || response.data;
        } catch (error) {
            console.error('Erreur récupération partenaires:', error.message);
            return [];
        }
    }

    // Sponsors
    async getSponsors() {
        try {
            const response = await this.api.get('/sponsors');
            return response.data.data || response.data;
        } catch (error) {
            console.error('Erreur récupération sponsors:', error.message);
            return [];
        }
    }

    // Statistiques
    async getStats() {
        try {
            const response = await this.api.get('/stats');
            return response.data;
        } catch (error) {
            console.error('Erreur récupération statistiques:', error.message);
            return {
                totalParticipants: 0,
                confirmedParticipants: 0,
                pendingParticipants: 0,
                totalCountries: 0
            };
        }
    }

    // Envoyer une invitation individuelle
    async sendInvitation(participantId) {
        try {
            const response = await this.api.post('/invitations/send', {
                participantId: participantId
            });
            return response.data;
        } catch (error) {
            console.error('Erreur envoi invitation:', error.message);
            throw error;
        }
    }

    // Envoyer des invitations en masse
    async sendBulkInvitations(participantIds) {
        try {
            const response = await this.api.post('/invitations/bulk', {
                participantIds: participantIds
            });
            return response.data;
        } catch (error) {
            console.error('Erreur invitations en masse:', error.message);
            throw error;
        }
    }

    // Générer un badge numérique
    async generateBadge(participantId) {
        try {
            const response = await this.api.post(`/badges/generate/${participantId}`);
            return response.data;
        } catch (error) {
            console.error('Erreur génération badge:', error.message);
            throw error;
        }
    }

    // Données de démonstration en cas d'erreur de connexion
    getDemoParticipants() {
        return [
            {
                id: 1,
                first_name: "Ahmed",
                last_name: "Mahamat",
                email: "ahmed.mahamat@example.com",
                phone: "+235 66 12 34 56",
                country: "Tchad",
                city: "N'Djamena",
                type: "national",
                status: "confirmed",
                organization: "Ministère de la Jeunesse",
                occupation: "Fonctionnaire",
                interests: ["Développement", "Innovation"],
                experience: 5,
                motivation: "Participer au développement du Sahel",
                created_at: "2025-01-01T08:00:00.000Z",
                updated_at: "2025-01-01T08:00:00.000Z"
            },
            {
                id: 2,
                first_name: "Fatima",
                last_name: "Diallo",
                email: "fatima.diallo@example.com",
                phone: "+221 77 123 45 67",
                country: "Sénégal",
                city: "Dakar",
                type: "international",
                status: "pending",
                organization: "ONG Jeunesse Active",
                occupation: "Coordinatrice",
                interests: ["Éducation", "Santé"],
                experience: 3,
                motivation: "Échanger avec d'autres jeunes leaders",
                created_at: "2025-01-02T09:30:00.000Z",
                updated_at: "2025-01-02T09:30:00.000Z"
            },
            {
                id: 3,
                first_name: "Moussa",
                last_name: "Traoré",
                email: "moussa.traore@example.com",
                phone: "+223 66 98 76 54",
                country: "Mali",
                city: "Bamako",
                type: "national",
                status: "confirmed",
                organization: "Association des Étudiants",
                occupation: "Étudiant",
                interests: ["Technologie", "Entrepreneuriat"],
                experience: 1,
                motivation: "Découvrir de nouvelles opportunités",
                created_at: "2025-01-03T11:45:00.000Z",
                updated_at: "2025-01-03T11:45:00.000Z"
            },
            {
                id: 4,
                first_name: "Aminata",
                last_name: "Ouedraogo",
                email: "aminata.ouedraogo@example.com",
                phone: "+226 70 12 34 56",
                country: "Burkina Faso",
                city: "Ouagadougou",
                type: "national",
                status: "pending",
                organization: "Coopérative Agricole",
                occupation: "Agricultrice",
                interests: ["Agriculture", "Développement rural"],
                experience: 8,
                motivation: "Partager l'expérience agricole",
                created_at: "2025-01-04T14:20:00.000Z",
                updated_at: "2025-01-04T14:20:00.000Z"
            },
            {
                id: 5,
                first_name: "Ibrahim",
                last_name: "Kane",
                email: "ibrahim.kane@example.com",
                phone: "+222 45 67 89 01",
                country: "Mauritanie",
                city: "Nouakchott",
                type: "international",
                status: "confirmed",
                organization: "Chambre de Commerce",
                occupation: "Entrepreneur",
                interests: ["Commerce", "Innovation"],
                experience: 6,
                motivation: "Développer le réseau commercial",
                created_at: "2025-01-05T16:10:00.000Z",
                updated_at: "2025-01-05T16:10:00.000Z"
            }
        ];
    }
}

module.exports = new LaravelApiService();
