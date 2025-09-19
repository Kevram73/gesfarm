# 🐳 Guide CORS avec Docker - GESFARM

## 🚨 **Problème Identifié**

**Oui, Docker peut causer des problèmes CORS !** Voici pourquoi :

### **Causes Principales**
1. **Nginx ne transmet pas les headers CORS** - Le proxy Nginx bloque les headers CORS
2. **Réseaux Docker isolés** - Les conteneurs communiquent via des réseaux internes
3. **Configuration de proxy manquante** - Nginx ne gère pas les requêtes preflight OPTIONS

---

## ✅ **Solution Appliquée**

### **1. Configuration Nginx Corrigée**
```nginx
# docker/nginx/sites/gesfarm.conf
location / {
    # Gérer les requêtes preflight OPTIONS
    if ($request_method = 'OPTIONS') {
        add_header 'Access-Control-Allow-Origin' '$http_origin' always;
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, PATCH, DELETE, OPTIONS' always;
        add_header 'Access-Control-Allow-Headers' 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Access-Control-Request-Method, Access-Control-Request-Headers' always;
        add_header 'Access-Control-Allow-Credentials' 'true' always;
        add_header 'Access-Control-Max-Age' 86400 always;
        return 204;
    }
    
    # Headers CORS pour toutes les réponses
    add_header 'Access-Control-Allow-Origin' '$http_origin' always;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, PATCH, DELETE, OPTIONS' always;
    add_header 'Access-Control-Allow-Headers' 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Access-Control-Request-Method, Access-Control-Request-Headers' always;
    add_header 'Access-Control-Allow-Credentials' 'true' always;
    add_header 'Vary' 'Origin' always;
    
    try_files $uri $uri/ /index.php?$query_string;
}
```

### **2. Configuration Laravel CORS**
```php
// config/cors.php
'allowed_origins' => [
    'http://62.171.181.213:3000',
    'http://62.171.181.213',
    'https://62.171.181.213:3000',
    'https://62.171.181.213',
],
'supports_credentials' => true,
```

### **3. Middleware CORS Personnalisé**
- ✅ `CorsMiddleware.php` pour une gestion robuste
- ✅ Vérification des origines autorisées
- ✅ Gestion des requêtes preflight

---

## 🚀 **Application de la Correction**

### **Option 1: Script PowerShell Automatique**
```powershell
cd C:\Users\LENOVO\Documents\codes\gesfarm
.\fix-docker-cors.ps1
```

### **Option 2: Commandes Manuelles**
```bash
# 1. Arrêter les conteneurs
docker-compose down

# 2. Reconstruire les images (important pour Nginx)
docker-compose build --no-cache

# 3. Redémarrer les services
docker-compose up -d

# 4. Attendre le démarrage
sleep 15

# 5. Nettoyer le cache Laravel
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear

# 6. Vérifier le statut
docker-compose ps
```

---

## 🔍 **Diagnostic Docker CORS**

### **1. Vérifier les Conteneurs**
```bash
docker-compose ps
```

### **2. Vérifier les Logs Nginx**
```bash
docker-compose logs nginx
```

### **3. Vérifier les Logs Laravel**
```bash
docker-compose logs app
```

### **4. Test CORS Direct**
```bash
# Test de requête preflight
curl -X OPTIONS \
  -H "Origin: http://62.171.181.213:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  "http://62.171.181.213/api/login"
```

### **5. Test avec PowerShell**
```powershell
$response = Invoke-WebRequest -Uri "http://62.171.181.213/api/login" -Method OPTIONS -Headers @{
    "Origin" = "http://62.171.181.213:3000"
    "Access-Control-Request-Method" = "POST"
    "Access-Control-Request-Headers" = "Content-Type,Authorization"
} -UseBasicParsing

$response.Headers
```

---

## 🛠️ **Architecture Docker CORS**

```
Internet → Nginx (Port 80) → Laravel App (Port 9000)
    ↓
Frontend (Port 3000) → API (Port 80) → Laravel
```

### **Flux des Requêtes CORS**
1. **Frontend** envoie requête preflight OPTIONS
2. **Nginx** intercepte et ajoute headers CORS
3. **Laravel** traite la requête avec middleware CORS
4. **Nginx** transmet la réponse avec headers CORS

---

## 📋 **Checklist de Vérification**

- [ ] ✅ Configuration Nginx mise à jour
- [ ] ✅ Configuration Laravel CORS mise à jour
- [ ] ✅ Middleware CORS personnalisé activé
- [ ] ✅ Conteneurs Docker redémarrés
- [ ] ✅ Images Docker reconstruites
- [ ] ✅ Cache Laravel nettoyé
- [ ] ✅ Test CORS réussi
- [ ] ✅ Headers Access-Control présents

---

## 🚨 **Problèmes Courants avec Docker**

### **1. Cache Docker**
```bash
# Nettoyer le cache Docker
docker system prune -a
docker-compose build --no-cache
```

### **2. Réseaux Docker**
```bash
# Vérifier les réseaux
docker network ls
docker network inspect gesfarm_gesfarm_network
```

### **3. Volumes Docker**
```bash
# Vérifier les volumes
docker volume ls
docker volume inspect gesfarm_mysql_data
```

### **4. Ports Docker**
```bash
# Vérifier les ports exposés
docker-compose ps
netstat -tulpn | grep :80
```

---

## 🔧 **Configuration de Production**

### **Pour la Production, ajoutez :**
```nginx
# Sécurité renforcée
add_header 'Access-Control-Allow-Origin' 'https://votre-domaine.com' always;
add_header 'Strict-Transport-Security' 'max-age=31536000; includeSubDomains' always;
```

### **Variables d'Environnement**
```env
# .env
APP_ENV=production
APP_URL=https://votre-domaine.com
CORS_ALLOWED_ORIGINS=https://votre-domaine.com,https://www.votre-domaine.com
```

---

## 📞 **Support**

Si le problème persiste :

1. **Vérifier les logs** : `docker-compose logs -f`
2. **Tester avec curl** : Vérifier les headers de réponse
3. **Vérifier la configuration réseau** : Ports et firewall
4. **Consulter les DevTools** : Onglet Network dans le navigateur

---

*Guide Docker CORS mis à jour le : 18 Septembre 2025*
