#!/bin/bash

# Script de configuration initiale du serveur VPS Hostinger
# Usage: sudo ./setup-server.sh

set -e

echo "🚀 Configuration initiale du serveur VPS pour YouthConnekt Sahel 2025"

# Vérifier que le script est exécuté en tant que root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Ce script doit être exécuté en tant que root (sudo)"
    exit 1
fi

# Mise à jour du système
echo "📦 Mise à jour du système..."
apt update && apt upgrade -y

# Installation des dépendances de base
echo "🔧 Installation des dépendances de base..."
apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release

# Installation de PHP 8.2
echo "🐘 Installation de PHP 8.2..."
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-intl php8.2-sqlite3 php8.2-redis

# Installation de Node.js 18
echo "📦 Installation de Node.js 18..."
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs

# Installation de Composer
echo "🎼 Installation de Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Installation de Nginx
echo "🌐 Installation de Nginx..."
apt install -y nginx

# Installation de MySQL
echo "🗄️ Installation de MySQL..."
apt install -y mysql-server

# Configuration de MySQL
echo "🔐 Configuration de MySQL..."
mysql_secure_installation

# Installation de Certbot pour SSL
echo "🔒 Installation de Certbot..."
apt install -y certbot python3-certbot-nginx

# Création de l'utilisateur pour l'application
echo "👤 Création de l'utilisateur application..."
if ! id "youthconnekt" &>/dev/null; then
    useradd -m -s /bin/bash youthconnekt
    usermod -aG www-data youthconnekt
    echo "✅ Utilisateur 'youthconnekt' créé"
else
    echo "ℹ️ Utilisateur 'youthconnekt' existe déjà"
fi

# Création des répertoires
echo "📁 Création des répertoires..."
mkdir -p /var/www/youthconnekt-sahel-2025
mkdir -p /var/log/youthconnekt
chown -R youthconnekt:www-data /var/www/youthconnekt-sahel-2025
chown -R youthconnekt:www-data /var/log/youthconnekt

# Configuration des permissions
echo "🔑 Configuration des permissions..."
chmod -R 755 /var/www/youthconnekt-sahel-2025
chmod -R 775 /var/log/youthconnekt

# Configuration du firewall
echo "🔥 Configuration du firewall..."
ufw allow ssh
ufw allow 'Nginx Full'
ufw --force enable

# Configuration de Nginx
echo "⚙️ Configuration de Nginx..."
cat > /etc/nginx/sites-available/youthconnekt-sahel-2025 << 'EOF'
server {
    listen 80;
    server_name _;
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

    # Logs
    access_log /var/log/youthconnekt/access.log;
    error_log /var/log/youthconnekt/error.log;
}
EOF

# Activation du site Nginx
ln -sf /etc/nginx/sites-available/youthconnekt-sahel-2025 /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test de la configuration Nginx
nginx -t

# Configuration des services systemd
echo "⚙️ Configuration des services systemd..."

# Service Laravel Backend
cat > /etc/systemd/system/youthconnekt-backend.service << 'EOF'
[Unit]
Description=YouthConnekt Sahel 2025 Backend
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/youthconnekt-sahel-2025/backend
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=3
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF

# Service Node.js Frontend
cat > /etc/systemd/system/youthconnekt-frontend.service << 'EOF'
[Unit]
Description=YouthConnekt Sahel 2025 Frontend
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/youthconnekt-sahel-2025/frontend
ExecStart=/usr/bin/node app.js
Restart=always
RestartSec=3
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF

# Rechargement des services systemd
systemctl daemon-reload

# Activation des services
systemctl enable youthconnekt-backend
systemctl enable youthconnekt-frontend

# Redémarrage de Nginx
systemctl restart nginx
systemctl enable nginx

# Configuration de la base de données
echo "🗄️ Configuration de la base de données..."
read -p "Entrez le mot de passe root MySQL: " mysql_root_password

mysql -u root -p"$mysql_root_password" << 'EOF'
CREATE DATABASE IF NOT EXISTS youthconnekt_sahel_2025 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'youthconnekt_user'@'localhost' IDENTIFIED BY 'YouthConnekt2025!';
GRANT ALL PRIVILEGES ON youthconnekt_sahel_2025.* TO 'youthconnekt_user'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "✅ Base de données configurée:"
echo "   - Nom: youthconnekt_sahel_2025"
echo "   - Utilisateur: youthconnekt_user"
echo "   - Mot de passe: YouthConnekt2025!"

# Configuration des logs
echo "📋 Configuration des logs..."
cat > /etc/logrotate.d/youthconnekt << 'EOF'
/var/log/youthconnekt/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload nginx
    endscript
}
EOF

# Installation d'outils de monitoring
echo "📊 Installation d'outils de monitoring..."
apt install -y htop iotop nethogs

# Configuration du swap (si nécessaire)
echo "💾 Configuration du swap..."
if [ ! -f /swapfile ]; then
    fallocate -l 2G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    echo "✅ Swap de 2GB configuré"
fi

# Optimisation de PHP
echo "⚡ Optimisation de PHP..."
cat > /etc/php/8.2/fpm/conf.d/99-youthconnekt.ini << 'EOF'
; Configuration optimisée pour YouthConnekt Sahel 2025
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
max_input_time = 300
date.timezone = Africa/Ndjamena
EOF

# Redémarrage de PHP-FPM
systemctl restart php8.2-fpm

echo ""
echo "🎉 Configuration du serveur terminée avec succès!"
echo ""
echo "📋 Résumé de la configuration:"
echo "   ✅ PHP 8.2 installé et configuré"
echo "   ✅ Node.js 18 installé"
echo "   ✅ Composer installé"
echo "   ✅ Nginx configuré"
echo "   ✅ MySQL installé et configuré"
echo "   ✅ Certbot installé pour SSL"
echo "   ✅ Services systemd créés"
echo "   ✅ Firewall configuré"
echo "   ✅ Utilisateur 'youthconnekt' créé"
echo ""
echo "🔧 Prochaines étapes:"
echo "   1. Cloner votre projet dans /var/www/youthconnekt-sahel-2025"
echo "   2. Configurer le fichier .env dans le backend"
echo "   3. Exécuter les migrations Laravel"
echo "   4. Installer les dépendances Node.js"
echo "   5. Démarrer les services"
echo "   6. Configurer SSL avec Certbot"
echo ""
echo "📖 Consultez le guide complet: GUIDE-DEPLOIEMENT-HOSTINGER.md"
