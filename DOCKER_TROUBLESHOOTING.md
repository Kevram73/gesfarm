# 🐳 Guide de Dépannage Docker - GESFARM

## Problèmes Courants et Solutions

### 1. Erreur "COPY docker/php/local.ini not found"

**Problème** : Docker ne peut pas trouver les fichiers dans le dossier `docker/`

**Solutions** :
```bash
# Vérifier que le fichier .dockerignore n'exclut pas le dossier docker/
grep -n "docker/" .dockerignore

# Si docker/ est listé, le commenter ou le supprimer
# docker/ - Commenté pour permettre l'accès aux fichiers de config
```

### 2. Erreur de Permissions

**Problème** : Erreurs de permissions lors de la construction

**Solutions** :
```bash
# Vérifier les permissions des fichiers
ls -la docker/php/

# Corriger les permissions si nécessaire
chmod +x docker/php/start.sh
chmod +x docker/scripts/*.sh
```

### 3. Erreur de Contexte Docker

**Problème** : Fichiers non trouvés dans le contexte de construction

**Solutions** :
```bash
# Vérifier le contexte de construction
docker build --no-cache -t gesfarm-test .

# Vérifier que tous les fichiers sont dans le bon répertoire
find . -name "*.sh" -o -name "*.ini" | grep docker
```

### 4. Erreur de Port Occupé

**Problème** : Port 80 ou 3306 déjà utilisé

**Solutions** :
```bash
# Vérifier les ports utilisés
netstat -tulpn | grep :80
netstat -tulpn | grep :3306

# Arrêter les services qui utilisent ces ports
sudo systemctl stop apache2
sudo systemctl stop mysql
```

### 5. Erreur de Mémoire Insuffisante

**Problème** : Docker manque de mémoire

**Solutions** :
```bash
# Augmenter la mémoire allouée à Docker
# Dans Docker Desktop : Settings > Resources > Memory

# Ou réduire la consommation mémoire
docker system prune -f
```

## Commandes de Diagnostic

### Vérifier l'État des Conteneurs
```bash
docker-compose ps
docker-compose logs
```

### Nettoyer Docker
```bash
# Nettoyer les images inutilisées
docker system prune -f

# Nettoyer tout (ATTENTION: supprime tout)
docker system prune -a -f
```

### Reconstruire Complètement
```bash
# Arrêter et supprimer tout
docker-compose down -v

# Reconstruire sans cache
docker-compose build --no-cache

# Redémarrer
docker-compose up -d
```

## Scripts de Test

### Test de Configuration
```bash
chmod +x test-docker.sh
./test-docker.sh
```

### Déploiement Complet
```bash
chmod +x deploy.sh
./deploy.sh
```

## Logs et Debugging

### Voir les Logs en Temps Réel
```bash
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Accéder au Conteneur
```bash
# Accéder au conteneur PHP
docker-compose exec app bash

# Accéder à la base de données
docker-compose exec db mysql -u gesfarm_user -p gesfarm
```

### Vérifier la Configuration
```bash
# Tester la configuration docker-compose
docker-compose config

# Vérifier les variables d'environnement
docker-compose exec app env
```

## Solutions Spécifiques

### Problème de .dockerignore
Si le fichier `.dockerignore` exclut le dossier `docker/`, modifiez-le :

```bash
# Éditer .dockerignore
nano .dockerignore

# Commenter ou supprimer la ligne :
# docker/
```

### Problème de Fichiers Manquants
Vérifiez que tous les fichiers nécessaires existent :

```bash
# Vérifier la structure
tree docker/
ls -la docker/php/
ls -la docker/nginx/
ls -la docker/scripts/
```

### Problème de Permissions Windows
Sur Windows, assurez-vous que Docker Desktop a accès aux dossiers :

1. Ouvrir Docker Desktop
2. Aller dans Settings > Resources > File Sharing
3. Ajouter le dossier du projet

## Support

Si les problèmes persistent :

1. Vérifiez les logs : `docker-compose logs`
2. Testez la configuration : `./test-docker.sh`
3. Consultez la documentation Docker
4. Contactez l'équipe de support
