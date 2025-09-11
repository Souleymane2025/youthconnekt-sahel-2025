const fs = require('fs');
const path = require('path');

class PartnerService {
    constructor() {
        this.partnersFile = path.join(__dirname, '../data/partners.json');
        this.sponsorsFile = path.join(__dirname, '../data/sponsors.json');
        this.standsFile = path.join(__dirname, '../data/stands.json');
    }

    // Méthodes génériques pour lire/écrire les données
    _readData(filePath) {
        try {
            if (!fs.existsSync(filePath)) {
                fs.writeFileSync(filePath, JSON.stringify([]));
            }
            const data = fs.readFileSync(filePath, 'utf8');
            return JSON.parse(data);
        } catch (error) {
            console.error(`Erreur lecture ${filePath}:`, error);
            return [];
        }
    }

    _writeData(filePath, data) {
        try {
            fs.writeFileSync(filePath, JSON.stringify(data, null, 2));
            return true;
        } catch (error) {
            console.error(`Erreur écriture ${filePath}:`, error);
            return false;
        }
    }

    // Gestion des partenaires
    getAllPartners() {
        return this._readData(this.partnersFile);
    }

    addPartner(partnerData) {
        const partners = this.getAllPartners();
        const newPartner = {
            id: Date.now().toString(),
            ...partnerData,
            createdAt: new Date().toISOString(),
            status: 'pending' // pending, approved, rejected
        };
        partners.push(newPartner);
        this._writeData(this.partnersFile, partners);
        return newPartner;
    }

    updatePartnerStatus(id, status) {
        const partners = this.getAllPartners();
        const partnerIndex = partners.findIndex(p => p.id === id);
        if (partnerIndex !== -1) {
            partners[partnerIndex].status = status;
            partners[partnerIndex].updatedAt = new Date().toISOString();
            this._writeData(this.partnersFile, partners);
            return partners[partnerIndex];
        }
        return null;
    }

    // Gestion des sponsors
    getAllSponsors() {
        return this._readData(this.sponsorsFile);
    }

    addSponsor(sponsorData) {
        const sponsors = this.getAllSponsors();
        const newSponsor = {
            id: Date.now().toString(),
            ...sponsorData,
            createdAt: new Date().toISOString(),
            status: 'pending'
        };
        sponsors.push(newSponsor);
        this._writeData(this.sponsorsFile, sponsors);
        return newSponsor;
    }

    updateSponsorStatus(id, status) {
        const sponsors = this.getAllSponsors();
        const sponsorIndex = sponsors.findIndex(s => s.id === id);
        if (sponsorIndex !== -1) {
            sponsors[sponsorIndex].status = status;
            sponsors[sponsorIndex].updatedAt = new Date().toISOString();
            this._writeData(this.sponsorsFile, sponsors);
            return sponsors[sponsorIndex];
        }
        return null;
    }

    // Gestion des stands
    getAllStands() {
        return this._readData(this.standsFile);
    }

    addStand(standData) {
        const stands = this.getAllStands();
        const newStand = {
            id: Date.now().toString(),
            ...standData,
            createdAt: new Date().toISOString(),
            status: 'pending'
        };
        stands.push(newStand);
        this._writeData(this.standsFile, stands);
        return newStand;
    }

    updateStandStatus(id, status) {
        const stands = this.getAllStands();
        const standIndex = stands.findIndex(s => s.id === id);
        if (standIndex !== -1) {
            stands[standIndex].status = status;
            stands[standIndex].updatedAt = new Date().toISOString();
            this._writeData(this.standsFile, stands);
            return stands[standIndex];
        }
        return null;
    }

    // Statistiques pour le dashboard
    getStats() {
        const partners = this.getAllPartners();
        const sponsors = this.getAllSponsors();
        const stands = this.getAllStands();

        return {
            partners: {
                total: partners.length,
                pending: partners.filter(p => p.status === 'pending').length,
                approved: partners.filter(p => p.status === 'approved').length,
                rejected: partners.filter(p => p.status === 'rejected').length
            },
            sponsors: {
                total: sponsors.length,
                pending: sponsors.filter(s => s.status === 'pending').length,
                approved: sponsors.filter(s => s.status === 'approved').length,
                rejected: sponsors.filter(s => s.status === 'rejected').length
            },
            stands: {
                total: stands.length,
                pending: stands.filter(s => s.status === 'pending').length,
                approved: stands.filter(s => s.status === 'approved').length,
                rejected: stands.filter(s => s.status === 'rejected').length
            }
        };
    }
}

module.exports = new PartnerService();


