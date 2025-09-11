# 🚀 YouthConnekt Sahel 2025 - Guide de Déploiement

## 📋 Fichiers de déploiement créés

### 📖 Documentation
- **`GUIDE-DEPLOIEMENT-HOSTINGER.md`** - Guide complet de déploiement
- **`DEPLOYMENT-QUICK-START.md`** - Déploiement rapide en 5 étapes
- **`CONFIGURATION-PRODUCTION.md`** - Configuration détaillée pour la production

### 🔧 Scripts automatisés
- **`scripts/setup-server.sh`** - Configuration initiale du serveur VPS
- **`scripts/deploy.sh`** - Script de déploiement automatique

## 🎯 Déploiement sur Hostinger VPS

### Option 1: Déploiement automatique (Recommandé)
```bash
# 1. Connectez-vous à votre VPS Hostinger
ssh root@votre-ip-serveur

# 2. Téléchargez et exécutez le script de configuration
wget https://raw.githubusercontent.com/votre-repo/youthconnekt-sahel-2025/main/scripts/setup-server.sh
chmod +x setup-server.sh
sudo ./setup-server.sh

# 3. Clonez votre projet
cd /var/www
git clone https://github.com/votre-repo/youthconnekt-sahel-2025.git
cd youthconnekt-sahel-2025

# 4. Configurez l'environnement
cp CONFIGURATION-PRODUCTION.md backend/.env
nano backend/.env  # Modifiez selon votre configuration

# 5. Déployez l'application
./scripts/deploy.sh production
```

### Option 2: Déploiement manuel
Suivez le guide complet dans `GUIDE-DEPLOIEMENT-HOSTINGER.md`

## ⚙️ Configuration requise

### Serveur VPS
- **OS**: Ubuntu 20.04/22.04
- **RAM**: Minimum 2GB (Recommandé 4GB)
- **Stockage**: Minimum 20GB SSD
- **CPU**: 2 vCPU minimum

### Logiciels installés automatiquement
- PHP 8.2 avec extensions
- Node.js 18+
- Composer
- Nginx
- MySQL
- Certbot (SSL)

## 🔐 Configuration de base de données

### Création automatique
Le script `setup-server.sh` crée automatiquement :
- Base de données: `youthconnekt_sahel_2025`
- Utilisateur: `youthconnekt_user`
- Mot de passe: `YouthConnekt2025!`

### Configuration manuelle
```sql
CREATE DATABASE youthconnekt_sahel_2025 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'youthconnekt_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON youthconnekt_sahel_2025.* TO 'youthconnekt_user'@'localhost';
FLUSH PRIVILEGES;
```

## 📧 Configuration email

### Paramètres Hostinger SMTP
```env
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@votre-domaine.com
MAIL_PASSWORD=votre_mot_de_passe_email
MAIL_ENCRYPTION=tls
```

## 🌐 Configuration SSL

### Activation automatique
```bash
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com
```

## 📊 Services créés

### Services systemd
- **`youthconnekt-backend`** - Service Laravel (port 8000)
- **`youthconnekt-frontend`** - Service Node.js (port 3000)

### Commandes de gestion
```bash
# Statut des services
sudo systemctl status youthconnekt-backend
sudo systemctl status youthconnekt-frontend

# Redémarrage
sudo systemctl restart youthconnekt-backend
sudo systemctl restart youthconnekt-frontend

# Logs
sudo journalctl -u youthconnekt-backend -f
sudo journalctl -u youthconnekt-frontend -f
```

## 🔄 Mise à jour de l'application

### Mise à jour automatique
```bash
./scripts/deploy.sh production
```

### Mise à jour manuelle
```bash
git pull origin main
cd backend && composer install --optimize-autoloader --no-dev
cd ../frontend && npm install --production
sudo systemctl restart youthconnekt-backend youthconnekt-frontend
```

## 🆘 Dépannage

### Problèmes courants

#### 1. Services ne démarrent pas
```bash
# Vérifier les logs
sudo journalctl -u youthconnekt-backend -f
sudo journalctl -u youthconnekt-frontend -f

# Vérifier les permissions
sudo chown -R www-data:www-data /var/www/youthconnekt-sahel-2025
```

#### 2. Erreurs de base de données
```bash
# Vérifier la connexion
mysql -u youthconnekt_user -p youthconnekt_sahel_2025

# Relancer les migrations
cd backend && php artisan migrate --force
```

#### 3. Problèmes d'images
```bash
# Créer le lien de stockage
cd backend && php artisan storage:link

# Vérifier les permissions
sudo chmod -R 775 storage/app/public
```

### Logs importants
- **Laravel**: `backend/storage/logs/laravel.log`
- **Nginx**: `/var/log/nginx/error.log`
- **Services**: `journalctl -u service-name -f`

## 📞 Support

### Documentation disponible
1. **`GUIDE-DEPLOIEMENT-HOSTINGER.md`** - Guide complet
2. **`DEPLOYMENT-QUICK-START.md`** - Déploiement rapide
3. **`CONFIGURATION-PRODUCTION.md`** - Configuration détaillée

### Scripts disponibles
1. **`scripts/setup-server.sh`** - Configuration serveur
2. **`scripts/deploy.sh`** - Déploiement automatique

## ✅ Checklist de déploiement

- [ ] VPS Hostinger configuré
- [ ] Script `setup-server.sh` exécuté
- [ ] Projet cloné dans `/var/www/youthconnekt-sahel-2025`
- [ ] Fichier `.env` configuré
- [ ] Base de données créée et migrée
- [ ] Services systemd démarrés
- [ ] SSL configuré avec Certbot
- [ ] Tests de fonctionnement effectués
- [ ] Sauvegarde automatique configurée

## 🎉 Félicitations !

Votre application YouthConnekt Sahel 2025 est maintenant déployée et accessible sur votre domaine !

**URLs importantes :**
- **Site principal** : `https://votre-domaine.com`
- **Dashboard admin** : `https://votre-domaine.com/admin/participants`
- **API** : `https://votre-domaine.com/api/participants`
