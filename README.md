# 🌍 YouthConnekt Sahel 2025

## 📋 À propos

**YouthConnekt Sahel 2025** est le site officiel du Forum de la Jeunesse du Sahel, un événement majeur qui rassemble les jeunes leaders, entrepreneurs et innovateurs de la région du Sahel pour promouvoir le développement durable et l'entrepreneuriat.

## 🎯 Objectifs

- **Connecter** les jeunes du Sahel
- **Promouvoir** l'entrepreneuriat et l'innovation
- **Faciliter** les échanges et le réseautage
- **Accompagner** les projets de développement

## 🚀 Fonctionnalités

### 🌐 Site Web
- **Page d'accueil** moderne et responsive
- **Programme détaillé** de l'événement
- **Intervenants** et speakers
- **Partenaires** officiels
- **Inscription** en ligne

### 👥 Gestion des Participants
- **Système d'inscription** adaptatif selon le statut
- **Dashboard d'administration** complet
- **Gestion des invitations** officielles
- **Génération de badges** personnalisés
- **Suivi des participants** en temps réel

### 🔧 Administration
- **Interface d'administration** sécurisée
- **Gestion des participants** (CRUD complet)
- **Envoi d'invitations** automatiques
- **Génération de rapports**
- **Export des données**

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 12** - Framework PHP
- **MySQL** - Base de données
- **Laravel Sanctum** - Authentification API
- **Laravel Mail** - Système d'emails

### Frontend
- **Node.js** - Serveur web
- **Express.js** - Framework web
- **EJS** - Moteur de templates
- **Bootstrap 5** - Framework CSS
- **Font Awesome** - Icônes

### Infrastructure
- **Nginx** - Serveur web et reverse proxy
- **SSL/TLS** - Sécurité HTTPS
- **Systemd** - Gestion des services
- **Git** - Contrôle de version

## 📁 Structure du Projet

```
youthconnekt-sahel-2025/
├── backend/                 # API Laravel
│   ├── app/                # Logique métier
│   ├── database/           # Migrations et seeders
│   ├── routes/             # Routes API
│   └── storage/            # Stockage des fichiers
├── frontend/               # Interface utilisateur
│   ├── views/              # Templates EJS
│   ├── public/             # Assets statiques
│   └── css/                # Styles CSS
├── scripts/                # Scripts de déploiement
│   ├── setup-server.sh     # Configuration serveur
│   └── deploy.sh           # Déploiement automatique
└── docs/                   # Documentation
```

## 🚀 Installation et Déploiement

### Développement Local

1. **Cloner le repository**
```bash
git clone https://github.com/votre-username/youthconnekt-sahel-2025.git
cd youthconnekt-sahel-2025
```

2. **Backend Laravel**
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --port=8000
```

3. **Frontend Node.js**
```bash
cd frontend
npm install
npm start
```

4. **Accès**
- Site web : http://localhost:3000
- API : http://localhost:8000/api
- Admin : http://localhost:3000/admin/participants

### Déploiement Production

Consultez la documentation complète :
- [Guide de déploiement](GUIDE-DEPLOIEMENT-HOSTINGER.md)
- [Déploiement rapide](DEPLOYMENT-QUICK-START.md)
- [Configuration production](CONFIGURATION-PRODUCTION.md)

## 📊 Fonctionnalités Administratives

### Dashboard Participants
- **Vue d'ensemble** des inscriptions
- **Actions en lot** (suppression, invitations)
- **Filtres et recherche** avancés
- **Export des données**

### Gestion des Invitations
- **Envoi automatique** d'invitations officielles
- **Suivi du statut** des invitations
- **Génération de badges** personnalisés
- **Notifications** par email

## 🤝 Partenaires Officiels

- **Ministère de la Jeunesse et des Sports du Tchad**
- **PNUD Tchad**
- **YouthConnekt Africa**
- **UNICEF**
- **Organisations du Sahel**

## 📞 Contact

- **Email** : contact@youthconnekt-sahel-2025.com
- **Site web** : https://youthconnekt-sahel-2025.com
- **Support technique** : support@youthconnekt-sahel-2025.com

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🙏 Remerciements

Merci à tous les partenaires, organisateurs et participants qui rendent cet événement possible.

---

**YouthConnekt Sahel 2025** - Connecter la jeunesse pour un avenir meilleur 🌍✨