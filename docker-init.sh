#!/bin/bash

echo "🚀 Initialisation de Farm Manager avec Docker..."

# Vérifier si Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé. Veuillez installer Docker d'abord."
    exit 1
fi

# Vérifier si Docker Compose est installé
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé. Veuillez installer Docker Compose d'abord."
    exit 1
fi

# Créer le fichier .env s'il n'existe pas
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cat > .env << EOF
APP_NAME="Farm Manager"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:4010

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=farmmanager
DB_USERNAME=root
DB_PASSWORD=Alkashi13!!!%

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"
EOF
    echo "✅ Fichier .env créé"
else
    echo "ℹ️  Le fichier .env existe déjà"
fi

# Démarrer les conteneurs
echo "🐳 Démarrage des conteneurs Docker..."
docker-compose up -d

# Attendre que les services soient prêts
echo "⏳ Attente du démarrage des services..."
sleep 5

# Installer les dépendances Composer
echo "📦 Installation des dépendances Composer..."
docker-compose exec -T app composer install

# Installer les dépendances NPM
echo "📦 Installation des dépendances NPM..."
docker-compose exec -T app npm install

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
docker-compose exec -T app php artisan key:generate

# Configurer les permissions
echo "🔐 Configuration des permissions..."
docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache

# Exécuter les migrations
echo "🗄️  Exécution des migrations..."
docker-compose exec -T app php artisan migrate --force

echo ""
echo "✅ Initialisation terminée !"
echo ""
echo "🌐 Application disponible sur : http://localhost:4010"
echo "🗄️  phpMyAdmin disponible sur : http://localhost:8081"
echo ""
echo "Pour voir les logs : docker-compose logs -f"
echo "Pour arrêter : docker-compose down"

