# 🔧 Solution CORS pour Docker - GESFARM

## 🚨 Problème Identifié
```
Access to XMLHttpRequest at 'http://62.171.181.213/api/login' from origin 'http://localhost:3000' has been blocked by CORS policy: No 'Access-Control-Allow-Origin' header is present on the requested resource.
```

## ✅ Solutions Implémentées

### 1. **Middleware CORS Laravel** (`app/Http/Middleware/CorsMiddleware.php`)
- ✅ Gestion des requêtes OPTIONS (preflight)
- ✅ Headers CORS sur toutes les réponses
- ✅ Support des credentials
- ✅ Origines autorisées configurées

### 2. **Configuration Nginx** (`docker/nginx/sites/gesfarm.conf`)
- ✅ Gestion des requêtes OPTIONS
- ✅ Headers CORS ajoutés
- ✅ Support des credentials
- ✅ Vary header pour le cache

### 3. **Configuration Laravel CORS** (`config/cors.php`)
- ✅ Origines autorisées : localhost:3000, 62.171.181.213:3000
- ✅ Méthodes autorisées : GET, POST, PUT, PATCH, DELETE, OPTIONS
- ✅ Headers autorisés : Authorization, Content-Type, etc.
- ✅ Credentials supportés

## 🚀 Commandes de Correction

### Option 1: Script PowerShell (Recommandé)
```powershell
cd C:\Users\LENOVO\Documents\codes\gesfarm
.\fix-cors-docker.ps1
```

### Option 2: Script Bash
```bash
cd C:\Users\LENOVO\Documents\codes\gesfarm
chmod +x fix-cors-docker.sh
./fix-cors-docker.sh
```

### Option 3: Commandes Manuelles
```bash
# Arrêter et nettoyer
docker-compose down --volumes --remove-orphans

# Reconstruire
docker-compose build --no-cache

# Démarrer
docker-compose up -d

# Nettoyer le cache Laravel
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

## 🧪 Test de la Configuration

### Test CORS avec curl
```bash
curl -X OPTIONS \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  http://localhost:8000/api/login
```

### Test avec PowerShell
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method OPTIONS -Headers @{
    "Origin" = "http://localhost:3000"
    "Access-Control-Request-Method" = "POST"
    "Access-Control-Request-Headers" = "Content-Type,Authorization"
}
```

## 🔍 Vérifications

### 1. Vérifier que les conteneurs sont en cours d'exécution
```bash
docker-compose ps
```

### 2. Vérifier les logs
```bash
docker-compose logs app
docker-compose logs nginx
```

### 3. Tester l'API directement
```bash
curl http://localhost:8000/api/health
```

## 🌐 URLs de Test

- **API Backend**: http://localhost:8000/api
- **Documentation API**: http://localhost:8000/docs
- **Frontend**: http://localhost:3000
- **phpMyAdmin**: http://localhost:8080

## 🚨 Dépannage Avancé

### Si le problème persiste :

1. **Vérifier les ports**
   ```bash
   netstat -an | findstr :8000
   netstat -an | findstr :3000
   ```

2. **Vérifier les logs Docker**
   ```bash
   docker-compose logs --tail=50
   ```

3. **Redémarrer Docker Desktop**
   - Fermer Docker Desktop
   - Redémarrer Docker Desktop
   - Relancer les conteneurs

4. **Vérifier la configuration réseau**
   ```bash
   docker network ls
   docker network inspect gesfarm_default
   ```

## 📝 Notes Importantes

- ✅ Le middleware CORS est configuré dans le Kernel Laravel
- ✅ Nginx gère les requêtes OPTIONS
- ✅ Les headers CORS sont ajoutés à toutes les réponses
- ✅ Les credentials sont supportés
- ✅ Les origines localhost:3000 et 62.171.181.213:3000 sont autorisées

## 🎯 Résultat Attendu

Après application de ces corrections, les requêtes depuis le frontend (localhost:3000) vers l'API (localhost:8000) devraient fonctionner sans erreur CORS.
