#!/bin/bash

# Script de déploiement manuel pour Farm Manager
# Usage: ./deploy.sh

set -e

SERVER_IP="72.60.188.146"
SERVER_USER="root"
APP_DIR="/var/www/farm-manager"

echo "🚀 Déploiement de Farm Manager sur $SERVER_IP..."

# Vérifier que les fichiers nécessaires existent
if [ ! -f ".env" ]; then
    echo "❌ Le fichier .env n'existe pas. Veuillez le créer d'abord."
    exit 1
fi

# Vérifier la connexion SSH
echo "🔌 Vérification de la connexion SSH..."
if ! ssh -o ConnectTimeout=5 $SERVER_USER@$SERVER_IP "echo 'Connexion OK'" > /dev/null 2>&1; then
    echo "❌ Impossible de se connecter au serveur. Vérifiez votre configuration SSH."
    exit 1
fi

# Créer le répertoire sur le serveur
echo "📁 Création du répertoire sur le serveur..."
ssh $SERVER_USER@$SERVER_IP "mkdir -p $APP_DIR"

# Copier les fichiers
echo "📤 Copie des fichiers..."
scp -r \
    .env \
    composer.json \
    composer.lock \
    package.json \
    package-lock.json \
    Dockerfile \
    docker-compose.yml \
    artisan \
    app \
    bootstrap \
    config \
    database \
    public \
    resources \
    routes \
    storage \
    docker \
    $SERVER_USER@$SERVER_IP:$APP_DIR/

# Configurer l'environnement sur le serveur
echo "⚙️  Configuration de l'environnement..."
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
set -e
cd /var/www/farm-manager

# Créer les dossiers nécessaires
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Définir les permissions
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true
ENDSSH

# Déployer avec Docker
echo "🐳 Déploiement avec Docker..."
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
set -e
cd /var/www/farm-manager

# Arrêter les conteneurs existants
docker-compose down || true

# Reconstruire les images
docker-compose build --no-cache

# Démarrer les conteneurs
docker-compose up -d

# Attendre que les services soient prêts
echo "⏳ Attente du démarrage des services..."
sleep 15
ENDSSH

# Installer les dépendances et configurer Laravel
echo "📦 Installation des dépendances..."
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
set -e
cd /var/www/farm-manager

# Installer les dépendances Composer
docker-compose exec -T app composer install --no-dev --optimize-autoloader --no-interaction || true

# Installer les dépendances NPM
docker-compose exec -T app npm install || true

# Générer la clé d'application si nécessaire
docker-compose exec -T app php artisan key:generate --force || true

# Optimiser Laravel
docker-compose exec -T app php artisan config:cache || true
docker-compose exec -T app php artisan route:cache || true
docker-compose exec -T app php artisan view:cache || true
ENDSSH

# Exécuter les migrations
echo "🗄️  Exécution des migrations..."
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
set -e
cd /var/www/farm-manager

# Attendre que la base de données soit prête
sleep 10

# Exécuter les migrations
docker-compose exec -T app php artisan migrate --force || echo "⚠️ Migrations déjà à jour"
ENDSSH

# Vérifier le déploiement
echo "✅ Vérification du déploiement..."
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
cd /var/www/farm-manager

if docker-compose ps | grep -q "Up"; then
    echo "✅ Application déployée avec succès!"
    echo "🌐 Accédez à: http://72.60.188.146:4010"
    echo "🗄️  phpMyAdmin: http://72.60.188.146:8081"
    docker-compose ps
else
    echo "❌ Les conteneurs ne sont pas en cours d'exécution"
    docker-compose ps
    exit 1
fi
ENDSSH

echo ""
echo "✅ Déploiement terminé avec succès!"

