#!/bin/bash

echo "🔧 Correction Erreur 502 Bad Gateway - GESFARM"
echo "=============================================="

echo "📊 État actuel des conteneurs:"
docker-compose ps

echo ""
echo "📝 Logs du service app (Laravel):"
docker-compose logs --tail=20 app

echo ""
echo "📝 Logs du service nginx:"
docker-compose logs --tail=20 nginx

echo ""
echo "🛑 Arrêt des conteneurs..."
docker-compose down

echo "🗑️ Nettoyage des volumes et réseaux..."
docker-compose down --volumes --remove-orphans

echo "🔨 Reconstruction complète des images..."
docker-compose build --no-cache --pull

echo "🚀 Démarrage des services..."
docker-compose up -d

echo "⏳ Attente du démarrage (30 secondes)..."
sleep 30

echo "📊 Vérification du statut:"
docker-compose ps

echo ""
echo "🧪 Test de connectivité:"
echo "Test 1: Vérification du conteneur app"
docker-compose exec app php --version

echo ""
echo "Test 2: Vérification de la base de données"
docker-compose exec app php artisan migrate:status

echo ""
echo "Test 3: Test de l'API"
curl -I "http://62.171.181.213/api/dashboard" || echo "❌ API non accessible"

echo ""
echo "Test 4: Test CORS"
curl -X OPTIONS \
  -H "Origin: http://62.171.181.213:3000" \
  -H "Access-Control-Request-Method: POST" \
  -v \
  "http://62.171.181.213/api/login" 2>&1 | grep -E "(Access-Control|HTTP|Origin)"

echo ""
echo "✅ Correction terminée!"
echo "🌐 Application: http://62.171.181.213"
echo "🗄️ phpMyAdmin: http://62.171.181.213:8080"
