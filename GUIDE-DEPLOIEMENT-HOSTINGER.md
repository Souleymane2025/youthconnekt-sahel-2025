# 🚀 Guide de Déploiement - YouthConnekt Sahel 2025 sur Hostinger VPS

## 📋 Prérequis
- VPS Hostinger avec Ubuntu 20.04/22.04
- Accès SSH au serveur
- Domaine configuré
- Base de données MySQL

## 🔧 Configuration du Serveur

### 1. Mise à jour du système
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Installation des dépendances
```bash
# PHP 8.2 et extensions
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-intl php8.2-sqlite3 -y

# Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Nginx
sudo apt install nginx -y

# MySQL
sudo apt install mysql-server -y

# Git
sudo apt install git -y
```

### 3. Configuration de la base de données
```bash
sudo mysql_secure_installation
sudo mysql -u root -p
```

```sql
CREATE DATABASE youthconnekt_sahel_2025;
CREATE USER 'youthconnekt_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON youthconnekt_sahel_2025.* TO 'youthconnekt_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 📁 Déploiement de l'Application

### 1. Cloner le projet
```bash
cd /var/www
sudo git clone https://github.com/votre-repo/youthconnekt-sahel-2025.git
sudo chown -R www-data:www-data youthconnekt-sahel-2025
cd youthconnekt-sahel-2025
```

### 2. Configuration Backend Laravel
```bash
cd backend
cp .env.example .env
nano .env
```

**Configuration .env pour la production:**
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
DB_PASSWORD=votre_mot_de_passe_securise

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@votre-domaine.com
MAIL_PASSWORD=votre_mot_de_passe_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votre-domaine.com"
MAIL_FROM_NAME="YouthConnekt Sahel 2025"
```

### 3. Installation des dépendances Laravel
```bash
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Configuration Frontend Node.js
```bash
cd ../frontend
npm install --production
npm run build
```

## 🌐 Configuration Nginx

### 1. Configuration du site
```bash
sudo nano /etc/nginx/sites-available/youthconnekt-sahel-2025
```

```nginx
server {
    listen 80;
    server_name votre-domaine.com www.votre-domaine.com;
    root /var/www/youthconnekt-sahel-2025/frontend/public;
    index index.html index.htm;

    # Frontend Express (port 3000)
    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }

    # Backend Laravel API (port 8000)
    location /api/ {
        proxy_pass http://127.0.0.1:8000/api/;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Images Laravel
    location /images/ {
        proxy_pass http://127.0.0.1:8000/images/;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
    }

    # Assets statiques
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 2. Activation du site
```bash
sudo ln -s /etc/nginx/sites-available/youthconnekt-sahel-2025 /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔒 Configuration SSL (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com
```

## 🚀 Services Systemd

### 1. Service Laravel Backend
```bash
sudo nano /etc/systemd/system/youthconnekt-backend.service
```

```ini
[Unit]
Description=YouthConnekt Sahel 2025 Backend
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/youthconnekt-sahel-2025/backend
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
```

### 2. Service Node.js Frontend
```bash
sudo nano /etc/systemd/system/youthconnekt-frontend.service
```

```ini
[Unit]
Description=YouthConnekt Sahel 2025 Frontend
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/youthconnekt-sahel-2025/frontend
ExecStart=/usr/bin/node app.js
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
```

### 3. Activation des services
```bash
sudo systemctl daemon-reload
sudo systemctl enable youthconnekt-backend
sudo systemctl enable youthconnekt-frontend
sudo systemctl start youthconnekt-backend
sudo systemctl start youthconnekt-frontend
```

## 📊 Monitoring et Logs
```bash
# Vérifier le statut des services
sudo systemctl status youthconnekt-backend
sudo systemctl status youthconnekt-frontend

# Voir les logs
sudo journalctl -u youthconnekt-backend -f
sudo journalctl -u youthconnekt-frontend -f
```

## 🔄 Script de Déploiement Automatique
```bash
#!/bin/bash
# deploy.sh

cd /var/www/youthconnekt-sahel-2025

# Mise à jour du code
git pull origin main

# Backend
cd backend
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Frontend
cd ../frontend
npm install --production
npm run build

# Redémarrage des services
sudo systemctl restart youthconnekt-backend
sudo systemctl restart youthconnekt-frontend

echo "Déploiement terminé!"
```

## ✅ Checklist de Déploiement
- [ ] Serveur VPS configuré
- [ ] PHP 8.2 installé avec extensions
- [ ] Node.js 18+ installé
- [ ] Composer installé
- [ ] MySQL configuré
- [ ] Nginx configuré
- [ ] SSL activé
- [ ] Services systemd créés
- [ ] Application déployée
- [ ] Tests de fonctionnement

## 🆘 Dépannage
- Vérifier les logs: `sudo journalctl -u service-name -f`
- Tester la connectivité: `curl http://localhost:3000` et `curl http://localhost:8000/api/participants`
- Vérifier les permissions: `sudo chown -R www-data:www-data /var/www/youthconnekt-sahel-2025`
