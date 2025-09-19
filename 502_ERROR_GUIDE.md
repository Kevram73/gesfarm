# 🚨 Guide Erreur 502 Bad Gateway - GESFARM

## 🔍 **Diagnostic de l'Erreur 502**

### **Erreur Observée**
```
POST http://62.171.181.213/api/login net::ERR_FAILED 502 (Bad Gateway)
```

### **Signification**
- **502 Bad Gateway** = Nginx ne peut pas communiquer avec le conteneur Laravel
- **net::ERR_FAILED** = La requête n'atteint même pas le serveur

---

## 🚨 **Causes Possibles**

### **1. Conteneur Laravel Non Démarré**
- Le conteneur `gesfarm_app` n'est pas en cours d'exécution
- Le conteneur crash au démarrage
- Problème de dépendances (MySQL, Redis)

### **2. Problème de Réseau Docker**
- Les conteneurs ne peuvent pas communiquer entre eux
- Problème de configuration réseau Docker
- Ports non exposés correctement

### **3. Problème de Configuration Nginx**
- Nginx ne peut pas atteindre `app:9000`
- Configuration FastCGI incorrecte
- Timeouts trop courts

### **4. Problème de Base de Données**
- MySQL non accessible
- Migrations non exécutées
- Configuration de connexion incorrecte

---

## ✅ **Solutions Appliquées**

### **1. Configuration Nginx Renforcée**
```nginx
# Timeouts plus longs
fastcgi_connect_timeout 300s;
fastcgi_send_timeout 300s;
fastcgi_read_timeout 300s;

# Buffer settings
fastcgi_buffer_size 128k;
fastcgi_buffers 4 256k;
fastcgi_busy_buffers_size 256k;
```

### **2. Headers CORS dans Nginx**
```nginx
# Headers CORS pour les réponses PHP
add_header 'Access-Control-Allow-Origin' '$http_origin' always;
add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, PATCH, DELETE, OPTIONS' always;
add_header 'Access-Control-Allow-Headers' 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Access-Control-Request-Method, Access-Control-Request-Headers' always;
add_header 'Access-Control-Allow-Credentials' 'true' always;
add_header 'Vary' 'Origin' always;
```

---

## 🚀 **Actions de Correction**

### **Option 1: Script Automatique**
```bash
cd C:\Users\LENOVO\Documents\codes\gesfarm
chmod +x fix-502-error.sh
./fix-502-error.sh
```

### **Option 2: Correction Manuelle**

#### **Étape 1: Diagnostic**
```bash
# Vérifier l'état des conteneurs
docker-compose ps

# Vérifier les logs
docker-compose logs app
docker-compose logs nginx
docker-compose logs db
```

#### **Étape 2: Redémarrage Complet**
```bash
# Arrêter tous les conteneurs
docker-compose down

# Nettoyer les volumes et réseaux
docker-compose down --volumes --remove-orphans

# Reconstruire les images
docker-compose build --no-cache --pull

# Redémarrer les services
docker-compose up -d
```

#### **Étape 3: Vérification**
```bash
# Attendre le démarrage
sleep 30

# Vérifier le statut
docker-compose ps

# Tester la connectivité
docker-compose exec app php --version
docker-compose exec app php artisan migrate:status
```

---

## 🔍 **Diagnostic Avancé**

### **1. Vérifier les Conteneurs**
```bash
# Statut détaillé
docker-compose ps

# Informations sur les conteneurs
docker inspect gesfarm_app
docker inspect gesfarm_nginx
```

### **2. Vérifier les Réseaux**
```bash
# Lister les réseaux Docker
docker network ls

# Inspecter le réseau
docker network inspect gesfarm_gesfarm_network
```

### **3. Vérifier les Volumes**
```bash
# Lister les volumes
docker volume ls

# Inspecter les volumes
docker volume inspect gesfarm_mysql_data
```

### **4. Test de Connectivité**
```bash
# Test depuis nginx vers app
docker-compose exec nginx ping app

# Test depuis app vers db
docker-compose exec app ping db

# Test de l'API
curl -I "http://62.171.181.213/api/dashboard"
```

---

## 🛠️ **Corrections Spécifiques**

### **Si le Conteneur App Crash**
```bash
# Vérifier les logs d'erreur
docker-compose logs app

# Accéder au conteneur en mode debug
docker-compose run --rm app bash

# Vérifier les dépendances
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
```

### **Si MySQL N'est Pas Accessible**
```bash
# Vérifier MySQL
docker-compose exec db mysql -u gesfarm_user -p gesfarm

# Réinitialiser la base de données
docker-compose exec app php artisan migrate:fresh --seed
```

### **Si Nginx Ne Peut Pas Atteindre App**
```bash
# Vérifier la configuration nginx
docker-compose exec nginx nginx -t

# Recharger la configuration nginx
docker-compose exec nginx nginx -s reload
```

---

## 📋 **Checklist de Vérification**

- [ ] ✅ Conteneurs Docker en cours d'exécution
- [ ] ✅ Réseau Docker fonctionnel
- [ ] ✅ Base de données MySQL accessible
- [ ] ✅ Conteneur Laravel répond
- [ ] ✅ Nginx peut communiquer avec app
- [ ] ✅ Configuration CORS active
- [ ] ✅ API accessible depuis l'extérieur
- [ ] ✅ Test CORS réussi

---

## 🚨 **Si le Problème Persiste**

### **1. Vérifier les Ressources Système**
```bash
# Vérifier l'utilisation de la mémoire
docker stats

# Vérifier l'espace disque
df -h
```

### **2. Vérifier les Logs Système**
```bash
# Logs Docker
docker system events

# Logs du système
journalctl -u docker
```

### **3. Test avec un Conteneur Simple**
```bash
# Tester avec un conteneur nginx simple
docker run -d -p 8080:80 nginx:alpine
curl http://62.171.181.213:8080
```

---

## 📞 **Support**

Si le problème persiste après toutes ces étapes :

1. **Collecter les logs** : `docker-compose logs > logs.txt`
2. **Vérifier les ressources** : CPU, RAM, espace disque
3. **Tester avec un conteneur simple**
4. **Vérifier la configuration réseau du serveur**

---

*Guide de dépannage 502 mis à jour le : 18 Septembre 2025*
