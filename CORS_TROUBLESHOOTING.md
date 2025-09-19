# 🚨 Dépannage CORS - GESFARM

## 🔍 **Diagnostic du Problème**

### **Erreur Typique**
```
Access to XMLHttpRequest at 'http://62.171.181.213/api/login' from origin 'http://62.171.181.213:3000' has been blocked by CORS policy: Response to preflight request doesn't pass access control check: No 'Access-Control-Allow-Origin' header is present on the requested resource.
```

### **Causes Possibles**
1. **Middleware CORS non activé**
2. **Configuration CORS incorrecte**
3. **Cache Laravel non vidé**
4. **Serveur web (Nginx) bloque les requêtes OPTIONS**
5. **Conflit entre middlewares CORS**

---

## ✅ **Solutions Appliquées**

### **1. Configuration CORS Corrigée**
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

### **2. Middleware CORS Personnalisé**
- ✅ `CorsMiddleware.php` créé
- ✅ Gestion robuste des requêtes preflight
- ✅ Headers CORS corrects

### **3. Scripts de Diagnostic**
- ✅ `debug-cors.sh` - Diagnostic complet
- ✅ `fix-cors.sh` - Correction automatique

---

## 🚀 **Actions de Correction**

### **Option 1: Script Automatique**
```bash
cd C:\Users\LENOVO\Documents\codes\gesfarm
chmod +x fix-cors.sh
./fix-cors.sh
```

### **Option 2: Correction Manuelle**
```bash
# 1. Arrêter les conteneurs
docker-compose down

# 2. Reconstruire les images
docker-compose build --no-cache

# 3. Redémarrer les services
docker-compose up -d

# 4. Nettoyer le cache Laravel
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear

# 5. Tester CORS
chmod +x debug-cors.sh
./debug-cors.sh
```

---

## 🔍 **Diagnostic Avancé**

### **1. Vérifier la Configuration CORS**
```bash
docker-compose exec app php artisan config:show cors
```

### **2. Vérifier les Middlewares**
```bash
docker-compose exec app php artisan route:list | grep api
```

### **3. Vérifier les Logs**
```bash
docker-compose logs app | grep -i cors
docker-compose logs nginx | grep -i cors
```

### **4. Test Direct de l'API**
```bash
# Test simple
curl -X GET "http://62.171.181.213/api/dashboard" -H "Accept: application/json"

# Test avec origine
curl -X GET "http://62.171.181.213/api/dashboard" \
  -H "Accept: application/json" \
  -H "Origin: http://62.171.181.213:3000"
```

---

## 🛠️ **Configuration Nginx (Si Nécessaire)**

Si le problème persiste, vérifiez la configuration Nginx :

```nginx
# docker/nginx/sites/default.conf
location /api {
    # Gérer les requêtes preflight
    if ($request_method = 'OPTIONS') {
        add_header 'Access-Control-Allow-Origin' '$http_origin' always;
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, PATCH, DELETE, OPTIONS' always;
        add_header 'Access-Control-Allow-Headers' 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin' always;
        add_header 'Access-Control-Allow-Credentials' 'true' always;
        add_header 'Access-Control-Max-Age' 86400 always;
        add_header 'Content-Length' 0;
        add_header 'Content-Type' 'text/plain charset=UTF-8';
        return 204;
    }
    
    # Headers CORS pour les autres requêtes
    add_header 'Access-Control-Allow-Origin' '$http_origin' always;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, PATCH, DELETE, OPTIONS' always;
    add_header 'Access-Control-Allow-Headers' 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin' always;
    add_header 'Access-Control-Allow-Credentials' 'true' always;
    
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## 📋 **Checklist de Vérification**

- [ ] ✅ Configuration CORS mise à jour
- [ ] ✅ Middleware CORS personnalisé activé
- [ ] ✅ Conteneurs redémarrés
- [ ] ✅ Cache Laravel nettoyé
- [ ] ✅ Test CORS réussi
- [ ] ✅ Headers Access-Control présents
- [ ] ✅ Requêtes preflight OPTIONS fonctionnelles

---

## 🚨 **Si le Problème Persiste**

### **1. Vérifier les Ports**
```bash
# Vérifier que les ports sont ouverts
netstat -tulpn | grep :80
netstat -tulpn | grep :8000
```

### **2. Vérifier le Firewall**
```bash
# Vérifier les règles de firewall
sudo ufw status
```

### **3. Test avec Postman/Insomnia**
- Créer une requête POST vers `http://62.171.181.213/api/login`
- Ajouter l'header `Origin: http://62.171.181.213:3000`
- Vérifier la réponse

### **4. Vérifier les Logs du Navigateur**
- Ouvrir les DevTools (F12)
- Onglet Network
- Vérifier les requêtes OPTIONS et POST
- Vérifier les headers de réponse

---

## 📞 **Support**

Si le problème persiste après toutes ces étapes :

1. **Exécuter le diagnostic complet** : `./debug-cors.sh`
2. **Vérifier les logs** : `docker-compose logs -f`
3. **Tester avec différents navigateurs**
4. **Vérifier la configuration réseau du serveur**

---

*Guide de dépannage mis à jour le : 18 Septembre 2025*
