#!/bin/bash

echo "🔧 Correction des problèmes CORS avec Docker..."

# Arrêter les conteneurs
echo "⏹️ Arrêt des conteneurs Docker..."
docker-compose down

# Nettoyer les volumes et images
echo "🧹 Nettoyage des volumes et images..."
docker-compose down --volumes --remove-orphans
docker system prune -f

# Reconstruire les images
echo "🏗️ Reconstruction des images Docker..."
docker-compose build --no-cache

# Démarrer les services
echo "▶️ Démarrage des services..."
docker-compose up -d

# Attendre que les services soient prêts
echo "⏳ Attente du démarrage des services..."
sleep 30

# Vérifier l'état des conteneurs
echo "📋 État des conteneurs:"
docker-compose ps

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
docker-compose exec app php artisan migrate --force

# Nettoyer le cache Laravel
echo "🧹 Nettoyage du cache Laravel..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear

# Tester la configuration CORS
echo "🧪 Test de la configuration CORS..."
curl -X OPTIONS \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  http://localhost:8000/api/login

echo "✅ Correction CORS terminée!"
echo "🌐 API accessible à: http://localhost:8000/api"
echo "📚 Documentation API: http://localhost:8000/docs"
