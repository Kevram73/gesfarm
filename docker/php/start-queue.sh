#!/bin/bash

# Script de démarrage pour les services queue et scheduler

echo "🚀 Démarrage du service queue/scheduler..."

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
while ! mysqladmin ping -h"db" -u"gesfarm_user" -p"gesfarm_password" --silent; do
    echo "En attente de MySQL..."
    sleep 2
done

echo "✅ Base de données connectée!"

# Vérifier que les dépendances sont installées
if [ ! -f vendor/autoload.php ]; then
    echo "❌ Erreur: Les dépendances Composer ne sont pas installées!"
    echo "Veuillez d'abord démarrer le service 'app' pour installer les dépendances."
    exit 1
fi

echo "✅ Dépendances Composer trouvées!"

# Exécuter la commande passée en argument
exec "$@"
