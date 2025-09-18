#!/bin/bash

# Script de test CORS pour GESFARM

echo "🧪 Test de la configuration CORS"
echo "================================="

# URL de base
BASE_URL="http://62.171.181.213"
API_URL="$BASE_URL/api"

echo "📍 URL de l'API: $API_URL"
echo ""

# Test 1: Requête preflight OPTIONS
echo "🔍 Test 1: Requête preflight OPTIONS"
echo "------------------------------------"
curl -X OPTIONS \
  -H "Origin: http://62.171.181.213:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  "$API_URL/login" 2>&1 | grep -E "(Access-Control|HTTP|Origin)"

echo ""
echo ""

# Test 2: Requête GET simple
echo "🔍 Test 2: Requête GET simple"
echo "-----------------------------"
curl -X GET \
  -H "Origin: http://62.171.181.213:3000" \
  -H "Accept: application/json" \
  -v \
  "$API_URL/dashboard" 2>&1 | grep -E "(Access-Control|HTTP|Origin)"

echo ""
echo ""

# Test 3: Test avec différentes origines
echo "🔍 Test 3: Test avec différentes origines"
echo "----------------------------------------"

ORIGINS=(
    "http://62.171.181.213:3000"
    "http://62.171.181.213"
    "https://62.171.181.213:3000"
    "https://62.171.181.213"
)

for origin in "${ORIGINS[@]}"; do
    echo "Testing origin: $origin"
    curl -X OPTIONS \
      -H "Origin: $origin" \
      -H "Access-Control-Request-Method: GET" \
      -s \
      "$API_URL/dashboard" | head -1
    echo ""
done

echo "✅ Tests terminés!"
echo ""
echo "📋 Vérifiez que tous les tests retournent des headers Access-Control-Allow-Origin"
echo "❌ Si vous voyez des erreurs, vérifiez la configuration CORS"
