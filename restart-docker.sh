#!/bin/bash

# Script pour redémarrer Docker avec les corrections

echo "🛑 Arrêt des conteneurs existants..."
docker-compose down

echo "🗑️ Suppression des conteneurs et images..."
docker-compose down --rmi all --volumes --remove-orphans

echo "🔨 Reconstruction des images..."
docker-compose build --no-cache

echo "🚀 Démarrage des services..."
docker-compose up -d

echo "📊 Statut des conteneurs..."
docker-compose ps

echo "📝 Logs du service app (dernières 20 lignes)..."
docker-compose logs --tail=20 app

echo "✅ Redémarrage terminé!"
echo "🌐 Application disponible sur: http://localhost"
echo "🗄️ phpMyAdmin disponible sur: http://localhost:8080"
echo "📧 Mailhog disponible sur: http://localhost:8025"
