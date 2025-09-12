const axios = require('axios');

const API_BASE_URL = process.env.API_BASE_URL || 'http://localhost:8000/api';

const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

const apiService = {
    // Participants
    async registerParticipant(data) {
        try {
            const response = await apiClient.post('/participants', data);
            console.log('API Response:', response.status, response.data);
            return response.data;
        } catch (error) {
            console.error('Erreur API registerParticipant:', error && error.toString ? error.toString() : error);
            
            // If it's a 409 conflict (duplicate email), return the server message
            if (error.response && error.response.status === 409 && error.response.data && error.response.data.message) {
                return { success: false, error: true, message: error.response.data.message };
            }
            
            // If it's a successful response but with error structure
            if (error.response && error.response.status === 201 && error.response.data) {
                return error.response.data;
            }
            
            // return structured error so caller can decide fallback without uncaught exceptions
            return { success: false, error: true, message: (error && error.message) || 'Erreur API', code: error && error.code, details: error };
        }
    },

    async getParticipants(page = 1, status = 'all') {
        try {
            const response = await apiClient.get(`/participants?page=${page}&status=${status}`);
            return response.data;
        } catch (error) {
            console.error('Erreur API getParticipants:', error);
            return [];
        }
    },

    // Blog
    async getBlogs(page = 1) {
        try {
            const response = await apiClient.get(`/blogs?page=${page}`);
            return response.data;
        } catch (error) {
            console.error('Erreur API getBlogs:', error);
            return [];
        }
    },

    async getBlogBySlug(slug) {
        try {
            const response = await apiClient.get(`/blogs/${slug}`);
            return response.data;
        } catch (error) {
            console.error('Erreur API getBlogBySlug:', error);
            return null;
        }
    },

    async getLatestBlogs(limit = 3) {
        try {
            const response = await apiClient.get(`/blogs/latest?limit=${limit}`);
            return response.data;
        } catch (error) {
            console.error('Erreur API getLatestBlogs:', error);
            return [];
        }
    },

    // Partners & Sponsors
    async getPartners() {
        try {
            const response = await apiClient.get('/partners');
            return response.data;
        } catch (error) {
            console.error('Erreur API getPartners:', error);
            return [];
        }
    },

    async getSponsors() {
        try {
            const response = await apiClient.get('/sponsors');
            return response.data;
        } catch (error) {
            console.error('Erreur API getSponsors:', error);
            return [];
        }
    },

    // Contact
    async sendContact(data) {
        try {
            const response = await apiClient.post('/contact', data);
            return response.data;
        } catch (error) {
            console.error('Erreur API sendContact:', error);
            throw error;
        }
    },

    // Dashboard
    async getDashboardStats() {
        try {
            const response = await apiClient.get('/dashboard/stats');
            return response.data;
        } catch (error) {
            console.error('Erreur API getDashboardStats:', error);
            return {};
        }
    }
};

module.exports = apiService;