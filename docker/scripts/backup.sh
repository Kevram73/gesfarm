#!/bin/bash

# Script de sauvegarde pour GESFARM

BACKUP_DIR="./backups"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="gesfarm_backup_$DATE"

echo "💾 Sauvegarde de GESFARM..."

# Créer le dossier de sauvegarde
mkdir -p $BACKUP_DIR

# Sauvegarder la base de données
echo "🗄️ Sauvegarde de la base de données..."
docker-compose exec -T db mysqldump -u gesfarm_user -pgesfarm_password gesfarm > "$BACKUP_DIR/${BACKUP_FILE}.sql"

# Sauvegarder les fichiers de l'application
echo "📁 Sauvegarde des fichiers..."
tar -czf "$BACKUP_DIR/${BACKUP_FILE}_files.tar.gz" \
    --exclude=node_modules \
    --exclude=vendor \
    --exclude=.git \
    --exclude=storage/logs \
    --exclude=storage/framework/cache \
    --exclude=storage/framework/sessions \
    --exclude=storage/framework/views \
    .

# Sauvegarder les fichiers de stockage
echo "📦 Sauvegarde du stockage..."
tar -czf "$BACKUP_DIR/${BACKUP_FILE}_storage.tar.gz" storage/

echo "✅ Sauvegarde terminée!"
echo "📂 Fichiers sauvegardés:"
echo "   - Base de données: $BACKUP_DIR/${BACKUP_FILE}.sql"
echo "   - Fichiers: $BACKUP_DIR/${BACKUP_FILE}_files.tar.gz"
echo "   - Stockage: $BACKUP_DIR/${BACKUP_FILE}_storage.tar.gz"
