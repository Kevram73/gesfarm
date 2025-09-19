#!/bin/bash

echo "🧪 Test CORS Simple"
echo "==================="

# Test de la requête preflight
echo "🔍 Test requête preflight OPTIONS:"
curl -X OPTIONS \
  -H "Origin: http://62.171.181.213:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  "http://62.171.181.213/api/login" 2>&1 | grep -E "(Access-Control|HTTP|Origin)"

echo ""
echo "✅ Si vous voyez 'Access-Control-Allow-Origin: *', CORS fonctionne!"
echo "❌ Si vous ne voyez pas de headers Access-Control, il y a un problème"
