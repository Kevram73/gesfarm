#!/bin/bash

# Script de démarrage pour le conteneur PHP

echo "🚀 Démarrage de GESFARM..."

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
while ! mysqladmin ping -h"db" -u"gesfarm_user" -p"gesfarm_password" --silent; do
    echo "En attente de MySQL..."
    sleep 2
done

echo "✅ Base de données connectée!"

# Générer la clé d'application si elle n'existe pas
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp .env.example .env
fi

# Installer les dépendances si nécessaire
if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    echo "📦 Installation des dépendances Composer..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Installer les dépendances Node.js si nécessaire
if [ ! -d node_modules ]; then
    echo "📦 Installation des dépendances Node.js..."
    npm install
fi

# Compiler les assets
echo "🎨 Compilation des assets..."
npm run build

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Exécuter les seeders
echo "🌱 Exécution des seeders..."
php artisan db:seed --force

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
php artisan key:generate --force

# Créer le lien symbolique pour le stockage
echo "🔗 Création du lien symbolique de stockage..."
php artisan storage:link

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Générer la documentation API
echo "📚 Génération de la documentation API..."
php artisan scribe:generate --force

echo "✅ GESFARM est prêt!"

# Démarrer PHP-FPM
exec php-fpm