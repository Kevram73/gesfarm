#!/bin/bash

echo "🐳 Déploiement de GESFARM..."

# Vérifier que Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé"
    exit 1
fi

# Créer le fichier .env si il n'existe pas
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp docker/env.example .env
    echo "⚠️  Veuillez configurer le fichier .env avant de continuer"
    exit 1
fi

# Arrêter les conteneurs existants
echo "🛑 Arrêt des conteneurs existants..."
docker-compose down

# Construire les images
echo "🔨 Construction des images Docker..."
docker-compose build --no-cache

# Démarrer les conteneurs
echo "🚀 Démarrage des conteneurs..."
docker-compose up -d

# Attendre que les services soient prêts
echo "⏳ Attente du démarrage des services..."
sleep 30

# Vérifier le statut
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
