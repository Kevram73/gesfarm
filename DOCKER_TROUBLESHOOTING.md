# 🐳 GESFARM - Guide de Dépannage Docker

## 🚨 **Erreurs Courantes et Solutions**

### **1. Erreur: `Failed to open stream: No such file or directory in /var/www/artisan`**

**Cause :** Les dépendances Composer ne sont pas installées dans le conteneur.

**Solution :**
```bash
# Arrêter les conteneurs
docker-compose down

# Reconstruire les images
docker-compose build --no-cache

# Redémarrer
docker-compose up -d
```

### **2. Erreur: `mysqladmin: command not found`**

**Cause :** Le client MySQL n'est pas installé dans le conteneur PHP.

**Solution :** ✅ **CORRIGÉ** - Le Dockerfile a été mis à jour pour inclure `default-mysql-client`.

### **3. Erreur: `En attente de MySQL...` (boucle infinie)**

**Cause :** Le service queue/scheduler démarre avant que les dépendances soient installées.

**Solution :** ✅ **CORRIGÉ** - Les services queue et scheduler dépendent maintenant du service `app`.

---

## 🛠️ **Commandes de Dépannage**

### **Redémarrage Complet**
```bash
# Script automatisé
chmod +x restart-docker.sh
./restart-docker.sh
```

### **Redémarrage Manuel**
```bash
# Arrêter tous les services
docker-compose down

# Supprimer les volumes (ATTENTION: supprime les données)
docker-compose down --volumes

# Reconstruire les images
docker-compose build --no-cache

# Démarrer les services
docker-compose up -d
```

### **Vérification des Services**
```bash
# Statut des conteneurs
docker-compose ps

# Logs en temps réel
docker-compose logs -f

# Logs d'un service spécifique
docker-compose logs app
docker-compose logs queue
docker-compose logs db
```

### **Accès aux Conteneurs**
```bash
# Accéder au conteneur app
docker-compose exec app bash

# Accéder au conteneur de base de données
docker-compose exec db mysql -u gesfarm_user -p gesfarm

# Vérifier les dépendances Composer
docker-compose exec app composer --version
docker-compose exec app ls -la vendor/
```

---

## 🔧 **Corrections Appliquées**

### **1. Dockerfile**
- ✅ Ajout de `default-mysql-client` pour `mysqladmin`
- ✅ Copie du script `start-queue.sh`
- ✅ Permissions correctes sur les scripts

### **2. Scripts de Démarrage**
- ✅ `start.sh` : Script principal pour le service app
- ✅ `start-queue.sh` : Script simplifié pour queue/scheduler
- ✅ Vérification des dépendances avant démarrage

### **3. Docker Compose**
- ✅ Dépendances correctes entre services
- ✅ Queue et scheduler dépendent du service app
- ✅ Commandes de démarrage mises à jour

---

## 📋 **Ordre de Démarrage Correct**

1. **Base de données** (`db`) - MySQL
2. **Cache** (`redis`) - Redis
3. **Application** (`app`) - PHP/Laravel avec installation des dépendances
4. **Queue** (`queue`) - Worker Laravel
5. **Scheduler** (`scheduler`) - Tâches cron Laravel
6. **Nginx** (`nginx`) - Serveur web
7. **Services auxiliaires** (phpMyAdmin, Mailhog)

---

## 🚀 **Démarrage Rapide**

### **Première Installation**
```bash
# Cloner le projet
git clone <repository>
cd gesfarm

# Copier le fichier d'environnement
cp .env.example .env

# Démarrer avec Docker
docker-compose up -d

# Vérifier les logs
docker-compose logs -f app
```

### **Après des Modifications**
```bash
# Redémarrage rapide
docker-compose restart

# Ou reconstruction complète
./restart-docker.sh
```

---

## 🔍 **Diagnostic des Problèmes**

### **Vérifier les Logs**
```bash
# Logs de tous les services
docker-compose logs

# Logs d'un service spécifique
docker-compose logs app
docker-compose logs queue
docker-compose logs db
```

### **Vérifier les Conteneurs**
```bash
# Statut des conteneurs
docker-compose ps

# Informations détaillées
docker-compose config
```

### **Vérifier les Volumes**
```bash
# Lister les volumes
docker volume ls

# Inspecter un volume
docker volume inspect gesfarm_mysql_data
```

### **Vérifier les Réseaux**
```bash
# Lister les réseaux
docker network ls

# Inspecter le réseau
docker network inspect gesfarm_gesfarm_network
```

---

## 🌐 **URLs d'Accès**

- **Application** : http://localhost
- **phpMyAdmin** : http://localhost:8080
- **Mailhog** : http://localhost:8025
- **API Documentation** : http://localhost/api/documentation

---

## 📞 **Support**

Si les problèmes persistent :

1. **Vérifier les logs** : `docker-compose logs -f`
2. **Redémarrer complètement** : `./restart-docker.sh`
3. **Vérifier les ressources système** : RAM, espace disque
4. **Consulter la documentation Docker** : https://docs.docker.com/

---

*Dernière mise à jour : 18 Septembre 2025*
