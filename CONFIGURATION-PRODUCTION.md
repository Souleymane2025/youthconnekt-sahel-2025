# ⚙️ Configuration de Production - YouthConnekt Sahel 2025

## 📁 Fichier .env pour la production

Créez le fichier `backend/.env` avec cette configuration :

```env
APP_NAME="YouthConnekt Sahel 2025"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://votre-domaine.com

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=youthconnekt_sahel_2025
DB_USERNAME=youthconnekt_user
DB_PASSWORD=votre_mot_de_passe_securise

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@votre-domaine.com
MAIL_PASSWORD=votre_mot_de_passe_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votre-domaine.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## 🔧 Paramètres à modifier

### 1. Informations de base
- `APP_URL` : Remplacez par votre domaine (ex: `https://youthconnekt-sahel-2025.com`)
- `APP_KEY` : Généré automatiquement avec `php artisan key:generate`

### 2. Base de données
- `DB_DATABASE` : Nom de votre base de données MySQL
- `DB_USERNAME` : Utilisateur MySQL
- `DB_PASSWORD` : Mot de passe MySQL sécurisé

### 3. Configuration email
- `MAIL_HOST` : Serveur SMTP Hostinger (`smtp.hostinger.com`)
- `MAIL_USERNAME` : Votre email Hostinger
- `MAIL_PASSWORD` : Mot de passe de votre email
- `MAIL_FROM_ADDRESS` : Email d'expéditeur (ex: `noreply@votre-domaine.com`)

## 🗄️ Configuration de la base de données

### Création de la base de données
```sql
CREATE DATABASE youthconnekt_sahel_2025 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'youthconnekt_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON youthconnekt_sahel_2025.* TO 'youthconnekt_user'@'localhost';
FLUSH PRIVILEGES;
```

### Exécution des migrations
```bash
cd backend
php artisan migrate --force
```

## 📧 Configuration email Hostinger

### Paramètres SMTP Hostinger
- **Serveur SMTP** : `smtp.hostinger.com`
- **Port** : `587` (TLS) ou `465` (SSL)
- **Sécurité** : TLS ou SSL
- **Authentification** : Requise

### Test de l'envoi d'emails
```bash
php artisan tinker
```

```php
Mail::raw('Test email', function ($message) {
    $message->to('test@example.com')
            ->subject('Test Email');
});
```

## 🔒 Configuration SSL

### Avec Certbot (Let's Encrypt)
```bash
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com
```

### Configuration Nginx pour HTTPS
```nginx
server {
    listen 443 ssl http2;
    server_name votre-domaine.com www.votre-domaine.com;
    
    ssl_certificate /etc/letsencrypt/live/votre-domaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/votre-domaine.com/privkey.pem;
    
    # Configuration SSL optimisée
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    
    # Votre configuration d'application...
}
```

## 📊 Optimisations de performance

### Cache Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Optimisation PHP
```ini
; /etc/php/8.2/fpm/conf.d/99-youthconnekt.ini
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
max_input_time = 300
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
```

### Configuration Nginx pour les performances
```nginx
# Compression
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

# Cache des assets statiques
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    access_log off;
}
```

## 🔍 Monitoring et logs

### Configuration des logs
```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Nginx
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# Logs des services systemd
journalctl -u youthconnekt-backend -f
journalctl -u youthconnekt-frontend -f
```

### Surveillance des performances
```bash
# Utilisation des ressources
htop
iotop
nethogs

# Espace disque
df -h
du -sh /var/www/youthconnekt-sahel-2025
```

## 🚨 Sécurité

### Permissions des fichiers
```bash
# Permissions correctes
chown -R www-data:www-data /var/www/youthconnekt-sahel-2025
chmod -R 755 /var/www/youthconnekt-sahel-2025
chmod -R 775 /var/www/youthconnekt-sahel-2025/storage
chmod -R 775 /var/www/youthconnekt-sahel-2025/bootstrap/cache
```

### Firewall
```bash
# Configuration UFW
ufw allow ssh
ufw allow 'Nginx Full'
ufw enable
```

### Sauvegarde automatique
```bash
# Script de sauvegarde quotidienne
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u youthconnekt_user -p youthconnekt_sahel_2025 > /backups/db_$DATE.sql
tar -czf /backups/files_$DATE.tar.gz /var/www/youthconnekt-sahel-2025
find /backups -name "*.sql" -mtime +7 -delete
find /backups -name "*.tar.gz" -mtime +7 -delete
```
