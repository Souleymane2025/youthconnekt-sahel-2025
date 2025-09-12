const fs = require('fs');
const path = require('path');

class DataService {
    constructor() {
        this.dataDir = path.join(__dirname, '..', 'data');
        this.ensureDataDir();
    }

    ensureDataDir() {
        if (!fs.existsSync(this.dataDir)) {
            fs.mkdirSync(this.dataDir, { recursive: true });
        }
    }

    // Messages
    getMessages() {
        try {
            const filePath = path.join(this.dataDir, 'messages.json');
            if (!fs.existsSync(filePath)) {
                return [];
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des messages:', error);
            return [];
        }
    }

    saveMessages(messages) {
        try {
            const filePath = path.join(this.dataDir, 'messages.json');
            fs.writeFileSync(filePath, JSON.stringify(messages, null, 2));
            return true;
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des messages:', error);
            return false;
        }
    }

    addMessage(message) {
        const messages = this.getMessages();
        const newMessage = {
            id: Date.now().toString(),
            ...message,
            createdAt: new Date().toISOString(),
            status: 'non_lu'
        };
        messages.push(newMessage);
        this.saveMessages(messages);
        return newMessage;
    }

    updateMessage(id, updates) {
        const messages = this.getMessages();
        const index = messages.findIndex(msg => msg.id === id);
        if (index !== -1) {
            messages[index] = { ...messages[index], ...updates };
            this.saveMessages(messages);
            return messages[index];
        }
        return null;
    }

    deleteMessage(id) {
        const messages = this.getMessages();
        const filteredMessages = messages.filter(msg => msg.id !== id);
        this.saveMessages(filteredMessages);
        return true;
    }

    // Blogs
    getBlogs() {
        try {
            const filePath = path.join(this.dataDir, 'blogs.json');
            if (!fs.existsSync(filePath)) {
                return [];
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des blogs:', error);
            return [];
        }
    }

    saveBlogs(blogs) {
        try {
            const filePath = path.join(this.dataDir, 'blogs.json');
            fs.writeFileSync(filePath, JSON.stringify(blogs, null, 2));
            return true;
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des blogs:', error);
            return false;
        }
    }

    addBlog(blog) {
        const blogs = this.getBlogs();
        const newBlog = {
            id: Date.now().toString(),
            ...blog,
            createdAt: new Date().toISOString(),
            status: 'brouillon',
            views: 0
        };
        blogs.push(newBlog);
        this.saveBlogs(blogs);
        return newBlog;
    }

    updateBlog(id, updates) {
        const blogs = this.getBlogs();
        const index = blogs.findIndex(blog => blog.id === id);
        if (index !== -1) {
            blogs[index] = { ...blogs[index], ...updates };
            this.saveBlogs(blogs);
            return blogs[index];
        }
        return null;
    }

    deleteBlog(id) {
        const blogs = this.getBlogs();
        const filteredBlogs = blogs.filter(blog => blog.id !== id);
        this.saveBlogs(filteredBlogs);
        return true;
    }

    // Badges
    getBadges() {
        try {
            const filePath = path.join(this.dataDir, 'badges.json');
            if (!fs.existsSync(filePath)) {
                return [];
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des badges:', error);
            return [];
        }
    }

    saveBadges(badges) {
        try {
            const filePath = path.join(this.dataDir, 'badges.json');
            fs.writeFileSync(filePath, JSON.stringify(badges, null, 2));
            return true;
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des badges:', error);
            return false;
        }
    }

    addBadge(badge) {
        const badges = this.getBadges();
        const newBadge = {
            id: Date.now().toString(),
            ...badge,
            createdAt: new Date().toISOString(),
            status: 'généré'
        };
        badges.push(newBadge);
        this.saveBadges(badges);
        return newBadge;
    }

    // Newsletters
    getNewsletters() {
        try {
            const filePath = path.join(this.dataDir, 'newsletters.json');
            if (!fs.existsSync(filePath)) {
                return [];
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des newsletters:', error);
            return [];
        }
    }

    saveNewsletters(newsletters) {
        try {
            const filePath = path.join(this.dataDir, 'newsletters.json');
            fs.writeFileSync(filePath, JSON.stringify(newsletters, null, 2));
            return true;
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des newsletters:', error);
            return false;
        }
    }

    addNewsletter(newsletter) {
        const newsletters = this.getNewsletters();
        const newNewsletter = {
            id: Date.now().toString(),
            ...newsletter,
            createdAt: new Date().toISOString(),
            status: 'brouillon',
            sentCount: 0,
            openRate: 0,
            clickRate: 0
        };
        newsletters.push(newNewsletter);
        this.saveNewsletters(newsletters);
        return newNewsletter;
    }

    updateNewsletter(id, updates) {
        const newsletters = this.getNewsletters();
        const index = newsletters.findIndex(news => news.id === id);
        if (index !== -1) {
            newsletters[index] = { ...newsletters[index], ...updates };
            this.saveNewsletters(newsletters);
            return newsletters[index];
        }
        return null;
    }

    // Participants
    getParticipants() {
        try {
            const filePath = path.join(this.dataDir, 'participants.json');
            if (!fs.existsSync(filePath)) {
                // Créer des participants de démonstration
                const demoParticipants = [
                    {
                        id: 'demo_1',
                        first_name: 'Ahmed',
                        last_name: 'Mahamat',
                        email: 'ahmed.mahamat@example.com',
                        phone: '+235 66 12 34 56',
                        country: 'Tchad',
                        city: 'N\'Djamena',
                        registration_type: 'national',
                        status: 'confirmed',
                        invitation_sent: false,
                        createdAt: new Date().toISOString()
                    },
                    {
                        id: 'demo_2',
                        first_name: 'Fatima',
                        last_name: 'Ousmane',
                        email: 'fatima.ousmane@example.com',
                        phone: '+235 66 78 90 12',
                        country: 'Tchad',
                        city: 'Moundou',
                        registration_type: 'national',
                        status: 'pending',
                        invitation_sent: false,
                        createdAt: new Date().toISOString()
                    },
                    {
                        id: 'demo_3',
                        first_name: 'Ibrahim',
                        last_name: 'Diallo',
                        email: 'ibrahim.diallo@example.com',
                        phone: '+223 66 12 34 56',
                        country: 'Mali',
                        city: 'Bamako',
                        registration_type: 'international',
                        status: 'confirmed',
                        invitation_sent: true,
                        invitation_sent_at: new Date().toISOString(),
                        createdAt: new Date().toISOString()
                    }
                ];
                this.saveParticipants(demoParticipants);
                return demoParticipants;
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des participants:', error);
            return [];
        }
    }

    saveParticipants(participants) {
        try {
            const filePath = path.join(this.dataDir, 'participants.json');
            fs.writeFileSync(filePath, JSON.stringify(participants, null, 2));
            return true;
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des participants:', error);
            return false;
        }
    }

    addParticipant(participant) {
        const participants = this.getParticipants();
        const newParticipant = {
            id: Date.now().toString(),
            ...participant,
            status: participant.status || 'pending',
            invitation_sent: false,
            createdAt: new Date().toISOString()
        };
        participants.push(newParticipant);
        this.saveParticipants(participants);
        return newParticipant;
    }

    updateParticipant(id, updates) {
        const participants = this.getParticipants();
        const index = participants.findIndex(participant => participant.id === id);
        if (index !== -1) {
            participants[index] = { ...participants[index], ...updates };
            this.saveParticipants(participants);
            return participants[index];
        }
        return null;
    }

    deleteParticipant(id) {
        const participants = this.getParticipants();
        const filteredParticipants = participants.filter(participant => participant.id !== id);
        this.saveParticipants(filteredParticipants);
        return true;
    }

    // Partenaires
    getPartners() {
        try {
            const filePath = path.join(this.dataDir, 'partners.json');
            if (!fs.existsSync(filePath)) {
                return [];
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des partenaires:', error);
            return [];
        }
    }

    // Sponsors
    getSponsors() {
        try {
            const filePath = path.join(this.dataDir, 'sponsors.json');
            if (!fs.existsSync(filePath)) {
                return [];
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des sponsors:', error);
            return [];
        }
    }

    // Newsletters
    getNewsletters() {
        try {
            const filePath = path.join(this.dataDir, 'newsletters.json');
            if (!fs.existsSync(filePath)) {
                // Créer des newsletters de démonstration
                const demoNewsletters = [
                    {
                        id: 'news_1',
                        title: 'Bienvenue à YouthConnekt Sahel 2025',
                        content: 'Nous sommes ravis de vous accueillir à cet événement exceptionnel...',
                        status: 'published',
                        recipients: 1250,
                        createdAt: '2025-01-10T10:00:00.000Z',
                        publishedAt: '2025-01-10T10:30:00.000Z'
                    },
                    {
                        id: 'news_2',
                        title: 'Programme détaillé disponible',
                        content: 'Découvrez le programme complet de YouthConnekt Sahel 2025...',
                        status: 'draft',
                        recipients: 0,
                        createdAt: '2025-01-11T14:00:00.000Z'
                    }
                ];
                this.saveNewsletters(demoNewsletters);
                return demoNewsletters;
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des newsletters:', error);
            return [];
        }
    }

    saveNewsletters(newsletters) {
        try {
            const filePath = path.join(this.dataDir, 'newsletters.json');
            fs.writeFileSync(filePath, JSON.stringify(newsletters, null, 2));
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des newsletters:', error);
        }
    }

    // Badges
    getBadges() {
        try {
            const filePath = path.join(this.dataDir, 'badges.json');
            if (!fs.existsSync(filePath)) {
                // Créer des badges de démonstration
                const demoBadges = [
                    {
                        id: 'badge_1',
                        name: 'Participant Actif',
                        description: 'Badge pour les participants très engagés',
                        color: '#28a745',
                        icon: 'fas fa-star',
                        criteria: 'Participation à 3+ sessions',
                        generated: 45,
                        total: 150
                    },
                    {
                        id: 'badge_2',
                        name: 'Innovateur',
                        description: 'Badge pour les projets innovants',
                        color: '#007bff',
                        icon: 'fas fa-lightbulb',
                        criteria: 'Projet innovant validé',
                        generated: 23,
                        total: 50
                    }
                ];
                this.saveBadges(demoBadges);
                return demoBadges;
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des badges:', error);
            return [];
        }
    }

    saveBadges(badges) {
        try {
            const filePath = path.join(this.dataDir, 'badges.json');
            fs.writeFileSync(filePath, JSON.stringify(badges, null, 2));
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des badges:', error);
        }
    }

    // Stands
    getStands() {
        try {
            const filePath = path.join(this.dataDir, 'stands.json');
            if (!fs.existsSync(filePath)) {
                // Créer des stands de démonstration
                const demoStands = [
                    {
                        id: 'stand_1',
                        name: 'Stand Innovation Tech',
                        company: 'Tech Solutions SARL',
                        location: 'Hall A - Stand 15',
                        size: '9m²',
                        status: 'confirmed',
                        contact: 'tech@solutions.com',
                        phone: '+235 66 12 34 56',
                        createdAt: '2025-01-05T09:00:00.000Z'
                    },
                    {
                        id: 'stand_2',
                        name: 'Stand Agriculture',
                        company: 'AgriTech Burkina',
                        location: 'Hall B - Stand 8',
                        size: '12m²',
                        status: 'pending',
                        contact: 'contact@agritech.bf',
                        phone: '+226 70 12 34 56',
                        createdAt: '2025-01-08T14:30:00.000Z'
                    }
                ];
                this.saveStands(demoStands);
                return demoStands;
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error('Erreur lors de la lecture des stands:', error);
            return [];
        }
    }

    saveStands(stands) {
        try {
            const filePath = path.join(this.dataDir, 'stands.json');
            fs.writeFileSync(filePath, JSON.stringify(stands, null, 2));
        } catch (error) {
            console.error('Erreur lors de la sauvegarde des stands:', error);
        }
    }
}

module.exports = new DataService();
