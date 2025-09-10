#!/bin/sh

# Script de démarrage pour la production

echo "🚀 Démarrage de GESFARM en production..."

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "En attente de MySQL..."
    sleep 2
done

echo "✅ Base de données connectée!"

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Générer la documentation API
echo "📚 Génération de la documentation API..."
php artisan scribe:generate --force

echo "✅ GESFARM est prêt en production!"

# Démarrer PHP-FPM
exec php-fpm
