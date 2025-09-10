#!/bin/bash

# Script d'arrêt pour GESFARM avec Docker

echo "🛑 Arrêt de GESFARM..."

# Arrêter tous les conteneurs
docker-compose down

echo "✅ GESFARM arrêté!"

# Optionnel: Supprimer les volumes (ATTENTION: cela supprimera toutes les données!)
if [ "$1" = "--clean" ]; then
    echo "🧹 Nettoyage des volumes..."
    docker-compose down -v
    docker system prune -f
    echo "✅ Nettoyage terminé!"
fi
