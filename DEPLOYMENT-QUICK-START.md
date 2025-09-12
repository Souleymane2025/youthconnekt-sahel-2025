# 🚀 Déploiement Rapide - YouthConnekt Sahel 2025

## ⚡ Déploiement en 5 étapes sur Hostinger VPS

### 1️⃣ Préparation du serveur
```bash
# Connectez-vous à votre VPS Hostinger via SSH
ssh root@votre-ip-serveur

# Téléchargez et exécutez le script de configuration
wget https://raw.githubusercontent.com/votre-repo/youthconnekt-sahel-2025/main/scripts/setup-server.sh
chmod +x setup-server.sh
sudo ./setup-server.sh
```

### 2️⃣ Déploiement de l'application
```bash
# Clonez le projet
cd /var/www
git clone https://github.com/votre-repo/youthconnekt-sahel-2025.git
cd youthconnekt-sahel-2025

# Configurez l'environnement
cp backend/.env.production backend/.env
nano backend/.env  # Modifiez les paramètres selon votre configuration
```

### 3️⃣ Configuration de la base de données
```bash
# Dans backend/.env, configurez:
DB_DATABASE=youthconnekt_sahel_2025
DB_USERNAME=youthconnekt_user
DB_PASSWORD=YouthConnekt2025!
```

### 4️⃣ Installation et configuration
```bash
# Backend Laravel
cd backend
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache

# Frontend Node.js
cd ../frontend
npm install --production
```

### 5️⃣ Démarrage des services
```bash
# Démarrez les services
sudo systemctl start youthconnekt-backend
sudo systemctl start youthconnekt-frontend
sudo systemctl restart nginx

# Configurez SSL
sudo certbot --nginx -d votre-domaine.com
```

## 🔧 Configuration .env pour la production

```env
APP_NAME="YouthConnekt Sahel 2025"
APP_ENV=production
APP_KEY=base64:votre_clé_générée
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=youthconnekt_sahel_2025
DB_USERNAME=youthconnekt_user
DB_PASSWORD=YouthConnekt2025!

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@votre-domaine.com
MAIL_PASSWORD=votre_mot_de_passe_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votre-domaine.com"
MAIL_FROM_NAME="YouthConnekt Sahel 2025"
```

## 📊 Vérification du déploiement

```bash
# Vérifiez le statut des services
sudo systemctl status youthconnekt-backend
sudo systemctl status youthconnekt-frontend

# Testez l'API
curl http://localhost:8000/api/participants

# Testez le frontend
curl http://localhost:3000
```

## 🔄 Mise à jour automatique

```bash
# Utilisez le script de déploiement pour les mises à jour
./scripts/deploy.sh production
```

## 🆘 Dépannage rapide

```bash
# Redémarrer tous les services
sudo systemctl restart youthconnekt-backend
sudo systemctl restart youthconnekt-frontend
sudo systemctl restart nginx

# Voir les logs
sudo journalctl -u youthconnekt-backend -f
sudo journalctl -u youthconnekt-frontend -f

# Vérifier les permissions
sudo chown -R www-data:www-data /var/www/youthconnekt-sahel-2025
```

## 📞 Support

- **Documentation complète**: `GUIDE-DEPLOIEMENT-HOSTINGER.md`
- **Scripts automatisés**: `scripts/setup-server.sh` et `scripts/deploy.sh`
- **Configuration optimisée** pour Hostinger VPS
