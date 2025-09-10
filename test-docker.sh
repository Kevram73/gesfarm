#!/bin/bash

echo "🧪 Test de la configuration Docker..."

# Vérifier les fichiers nécessaires
echo "📁 Vérification des fichiers..."

if [ ! -f "docker/php/local.ini" ]; then
    echo "❌ docker/php/local.ini manquant"
    exit 1
else
    echo "✅ docker/php/local.ini trouvé"
fi

if [ ! -f "docker/php/start.sh" ]; then
    echo "❌ docker/php/start.sh manquant"
    exit 1
else
    echo "✅ docker/php/start.sh trouvé"
fi

if [ ! -f "docker-compose.yml" ]; then
    echo "❌ docker-compose.yml manquant"
    exit 1
else
    echo "✅ docker-compose.yml trouvé"
fi

if [ ! -f "Dockerfile" ]; then
    echo "❌ Dockerfile manquant"
    exit 1
else
    echo "✅ Dockerfile trouvé"
fi

# Tester la syntaxe du docker-compose
echo "🔍 Test de la syntaxe docker-compose..."
docker-compose config > /dev/null
if [ $? -eq 0 ]; then
    echo "✅ Syntaxe docker-compose valide"
else
    echo "❌ Erreur dans docker-compose.yml"
    exit 1
fi

# Tester la construction de l'image
echo "🔨 Test de construction de l'image..."
docker build -t gesfarm-test . > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✅ Construction de l'image réussie"
    docker rmi gesfarm-test > /dev/null 2>&1
else
    echo "❌ Erreur lors de la construction de l'image"
    exit 1
fi

echo ""
echo "🎉 Tous les tests sont passés ! La configuration Docker est prête."
