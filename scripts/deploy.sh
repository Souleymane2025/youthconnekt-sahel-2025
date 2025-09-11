#!/bin/bash

# Script de déploiement automatique pour YouthConnekt Sahel 2025
# Usage: ./deploy.sh [environment]

set -e

ENVIRONMENT=${1:-production}
PROJECT_DIR="/var/www/youthconnekt-sahel-2025"
BACKEND_DIR="$PROJECT_DIR/backend"
FRONTEND_DIR="$PROJECT_DIR/frontend"

echo "🚀 Début du déploiement pour l'environnement: $ENVIRONMENT"

# Vérifier que nous sommes dans le bon répertoire
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Erreur: Le répertoire $PROJECT_DIR n'existe pas"
    exit 1
fi

cd "$PROJECT_DIR"

# Sauvegarder la base de données avant le déploiement
echo "💾 Sauvegarde de la base de données..."
if [ -f "$BACKEND_DIR/.env" ]; then
    DB_NAME=$(grep DB_DATABASE "$BACKEND_DIR/.env" | cut -d '=' -f2)
    DB_USER=$(grep DB_USERNAME "$BACKEND_DIR/.env" | cut -d '=' -f2)
    DB_PASS=$(grep DB_PASSWORD "$BACKEND_DIR/.env" | cut -d '=' -f2)
    
    if [ ! -z "$DB_NAME" ] && [ ! -z "$DB_USER" ]; then
        mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "backup_$(date +%Y%m%d_%H%M%S).sql"
        echo "✅ Sauvegarde créée: backup_$(date +%Y%m%d_%H%M%S).sql"
    fi
fi

# Mise à jour du code depuis Git
echo "📥 Mise à jour du code depuis Git..."
git fetch origin
git reset --hard origin/main
git clean -fd

# Configuration Backend Laravel
echo "🔧 Configuration du backend Laravel..."
cd "$BACKEND_DIR"

# Installer les dépendances Composer
echo "📦 Installation des dépendances Composer..."
composer install --optimize-autoloader --no-dev --no-interaction

# Générer la clé d'application si elle n'existe pas
if ! grep -q "APP_KEY=" .env 2>/dev/null || grep -q "APP_KEY=$" .env 2>/dev/null; then
    echo "🔑 Génération de la clé d'application..."
    php artisan key:generate --force
fi

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Créer le lien de stockage
echo "🔗 Création du lien de stockage..."
php artisan storage:link --force

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Configuration Frontend Node.js
echo "🌐 Configuration du frontend Node.js..."
cd "$FRONTEND_DIR"

# Installer les dépendances NPM
echo "📦 Installation des dépendances NPM..."
npm ci --production

# Build de l'application (si nécessaire)
if [ -f "package.json" ] && grep -q '"build"' package.json; then
    echo "🏗️ Build de l'application frontend..."
    npm run build
fi

# Redémarrage des services
echo "🔄 Redémarrage des services..."

# Redémarrer le backend Laravel
if systemctl is-active --quiet youthconnekt-backend; then
    echo "🔄 Redémarrage du service backend..."
    sudo systemctl restart youthconnekt-backend
else
    echo "⚠️ Service backend non actif, démarrage..."
    sudo systemctl start youthconnekt-backend
fi

# Redémarrer le frontend Node.js
if systemctl is-active --quiet youthconnekt-frontend; then
    echo "🔄 Redémarrage du service frontend..."
    sudo systemctl restart youthconnekt-frontend
else
    echo "⚠️ Service frontend non actif, démarrage..."
    sudo systemctl start youthconnekt-frontend
fi

# Vérifier le statut des services
echo "📊 Vérification du statut des services..."
sleep 5

if systemctl is-active --quiet youthconnekt-backend; then
    echo "✅ Service backend: ACTIF"
else
    echo "❌ Service backend: INACTIF"
    sudo systemctl status youthconnekt-backend --no-pager
fi

if systemctl is-active --quiet youthconnekt-frontend; then
    echo "✅ Service frontend: ACTIF"
else
    echo "❌ Service frontend: INACTIF"
    sudo systemctl status youthconnekt-frontend --no-pager
fi

# Test de connectivité
echo "🔍 Test de connectivité..."
sleep 3

# Test backend
if curl -s -f http://localhost:8000/api/participants > /dev/null; then
    echo "✅ Backend API: ACCESSIBLE"
else
    echo "❌ Backend API: INACCESSIBLE"
fi

# Test frontend
if curl -s -f http://localhost:3000 > /dev/null; then
    echo "✅ Frontend: ACCESSIBLE"
else
    echo "❌ Frontend: INACCESSIBLE"
fi

# Nettoyage des fichiers temporaires
echo "🧹 Nettoyage des fichiers temporaires..."
cd "$PROJECT_DIR"
find . -name "*.log" -type f -mtime +7 -delete
find . -name "*.tmp" -type f -delete

echo "🎉 Déploiement terminé avec succès!"
echo "📅 Date: $(date)"
echo "🌐 Votre application est accessible sur: https://votre-domaine.com"

# Afficher les logs récents en cas de problème
echo ""
echo "📋 Logs récents des services:"
echo "--- Backend ---"
sudo journalctl -u youthconnekt-backend --since "5 minutes ago" --no-pager | tail -10
echo ""
echo "--- Frontend ---"
sudo journalctl -u youthconnekt-frontend --since "5 minutes ago" --no-pager | tail -10
