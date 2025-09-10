# 🐳 GESFARM - Docker Setup

Ce guide vous explique comment déployer GESFARM avec Docker.

## 📋 Prérequis

- Docker (version 20.10+)
- Docker Compose (version 2.0+)
- Git

## 🚀 Démarrage rapide

### 1. Cloner le projet
```bash
git clone <repository-url>
cd gesfarm
```

### 2. Configuration
```bash
# Copier le fichier de configuration
cp docker/env.example .env

# Éditer le fichier .env selon vos besoins
nano .env
```

### 3. Démarrer l'application
```bash
# Rendre les scripts exécutables
chmod +x docker/scripts/*.sh

# Démarrer l'application
./docker/scripts/start.sh
```

## 🌐 URLs d'accès

Une fois démarré, vous pouvez accéder à :

- **Application** : http://localhost
- **Documentation API** : http://localhost/docs
- **phpMyAdmin** : http://localhost:8080
- **Mailhog** : http://localhost:8025

## 🔧 Commandes utiles

### Gestion des conteneurs
```bash
# Voir le statut des conteneurs
docker-compose ps

# Voir les logs
docker-compose logs -f

# Redémarrer un service
docker-compose restart app

# Arrêter l'application
./docker/scripts/stop.sh

# Arrêter et nettoyer (supprime les volumes)
./docker/scripts/stop.sh --clean
```

### Accès aux conteneurs
```bash
# Accéder au conteneur PHP
docker-compose exec app bash

# Accéder à la base de données
docker-compose exec db mysql -u gesfarm_user -p gesfarm

# Voir les logs d'un service spécifique
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Commandes Laravel
```bash
# Exécuter des commandes Artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan scribe:generate

# Accéder au tinker
docker-compose exec app php artisan tinker

# Nettoyer le cache
docker-compose exec app php artisan cache:clear
```

## 💾 Sauvegarde

```bash
# Créer une sauvegarde complète
./docker/scripts/backup.sh
```

Les sauvegardes sont stockées dans le dossier `./backups/`.

## 🏗️ Architecture

L'application utilise les services suivants :

- **app** : Application PHP/Laravel
- **nginx** : Serveur web
- **db** : Base de données MySQL
- **redis** : Cache et sessions
- **mailhog** : Serveur de mail pour le développement
- **phpmyadmin** : Interface d'administration MySQL
- **scheduler** : Tâches cron Laravel
- **queue** : Traitement des files d'attente

## 🔒 Production

Pour déployer en production :

1. Utilisez `Dockerfile.prod` au lieu de `Dockerfile`
2. Configurez SSL/TLS avec Nginx
3. Utilisez des variables d'environnement sécurisées
4. Activez les logs et monitoring
5. Configurez les sauvegardes automatiques

## 🐛 Dépannage

### Problèmes courants

**Port déjà utilisé** :
```bash
# Vérifier les ports utilisés
netstat -tulpn | grep :80
netstat -tulpn | grep :3306
```

**Problème de permissions** :
```bash
# Corriger les permissions
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
```

**Base de données non accessible** :
```bash
# Vérifier la connexion
docker-compose exec app php artisan migrate:status
```

## 📊 Monitoring

### Logs
```bash
# Logs de l'application
docker-compose logs -f app

# Logs de Nginx
docker-compose logs -f nginx

# Logs de MySQL
docker-compose logs -f db
```

### Ressources
```bash
# Utilisation des ressources
docker stats

# Espace disque
docker system df
```

## 🔄 Mise à jour

```bash
# Arrêter l'application
./docker/scripts/stop.sh

# Mettre à jour le code
git pull

# Reconstruire et redémarrer
docker-compose build --no-cache
docker-compose up -d
```

## 📞 Support

Pour toute question ou problème, consultez :
- La documentation Laravel
- Les logs Docker
- Le fichier `API_README.md`
