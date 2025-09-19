#!/bin/bash

echo "🧪 Test de la configuration Nginx CORS..."

# Tester la configuration Nginx
echo "📋 Vérification de la syntaxe Nginx..."
docker-compose exec nginx nginx -t

if [ $? -eq 0 ]; then
    echo "✅ Configuration Nginx valide"
    
    # Recharger la configuration Nginx
    echo "🔄 Rechargement de la configuration Nginx..."
    docker-compose exec nginx nginx -s reload
    
    echo "✅ Configuration Nginx rechargée"
else
    echo "❌ Erreur dans la configuration Nginx"
    exit 1
fi

# Tester les requêtes CORS
echo "🧪 Test des requêtes CORS..."

echo "1. Test OPTIONS preflight pour /api/login:"
curl -X OPTIONS \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  http://localhost:8000/api/login

echo -e "\n2. Test OPTIONS preflight pour /sanctum/csrf-cookie:"
curl -X OPTIONS \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: GET" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -v \
  http://localhost:8000/sanctum/csrf-cookie

echo -e "\n3. Test GET simple:"
curl -X GET \
  -H "Origin: http://localhost:3000" \
  -v \
  http://localhost:8000/api/health

echo -e "\n✅ Tests CORS terminés!"
