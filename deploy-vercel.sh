#!/bin/bash

# Script de déploiement Laravel sur Vercel
echo "🚀 Déploiement de GESFARM sur Vercel..."

# Vérifier si Vercel CLI est installé
if ! command -v vercel &> /dev/null; then
    echo "❌ Vercel CLI n'est pas installé. Installation..."
    npm install -g vercel
fi

# Vérifier si l'utilisateur est connecté à Vercel
if ! vercel whoami &> /dev/null; then
    echo "🔐 Connexion à Vercel..."
    vercel login
fi

# Générer la clé d'application si elle n'existe pas
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp .env.example .env
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
php artisan key:generate --show

echo "⚠️  IMPORTANT: Copiez la clé générée ci-dessus et ajoutez-la comme variable APP_KEY dans Vercel"

# Installer les dépendances
echo "📦 Installation des dépendances..."
composer install --no-dev --optimize-autoloader

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Déployer sur Vercel
echo "🚀 Déploiement sur Vercel..."
vercel --prod

echo "✅ Déploiement terminé!"
echo "📋 Prochaines étapes:"
echo "1. Configurez les variables d'environnement dans Vercel"
echo "2. Configurez votre base de données externe"
echo "3. Exécutez les migrations: vercel env pull .env.local && php artisan migrate --force"
echo "4. Testez votre API: https://your-app-name.vercel.app/api/"
