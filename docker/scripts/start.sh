#!/bin/bash

# Script de démarrage pour GESFARM avec Docker

echo "🐳 Démarrage de GESFARM avec Docker..."

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

# Créer le fichier .env si il n'existe pas
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp docker/env.example .env
    echo "⚠️  Veuillez configurer le fichier .env avant de continuer."
    exit 1
fi

# Construire et démarrer les conteneurs
echo "🔨 Construction des images Docker..."
docker-compose build --no-cache

echo "🚀 Démarrage des conteneurs..."
docker-compose up -d

# Attendre que les services soient prêts
echo "⏳ Attente du démarrage des services..."
sleep 30

# Vérifier le statut des conteneurs
echo "📊 Statut des conteneurs:"
docker-compose ps

# Afficher les logs
echo "📋 Logs de l'application:"
docker-compose logs app

echo ""
echo "✅ GESFARM est démarré!"
echo ""
echo "🌐 URLs d'accès:"
echo "   - Application: http://localhost"
echo "   - Documentation API: http://localhost/docs"
echo "   - phpMyAdmin: http://localhost:8080"
echo "   - Mailhog: http://localhost:8025"
echo ""
echo "🔧 Commandes utiles:"
echo "   - Voir les logs: docker-compose logs -f"
echo "   - Arrêter: docker-compose down"
echo "   - Redémarrer: docker-compose restart"
echo "   - Accéder au conteneur: docker-compose exec app bash"
