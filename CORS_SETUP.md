# 🌐 Configuration CORS - GESFARM

## ✅ **Configuration Appliquée**

### **Origines Autorisées**
- `http://localhost:3000` (développement local)
- `http://localhost:3001` (développement local alternatif)
- `http://127.0.0.1:3000` (développement local)
- `http://127.0.0.1:3001` (développement local alternatif)
- `http://62.171.181.213:3000` (votre serveur de production)
- `https://62.171.181.213:3000` (votre serveur de production HTTPS)

### **Patterns Autorisés**
- `/^https?:\/\/localhost:\d+$/` - Tous les ports localhost
- `/^https?:\/\/127\.0\.0\.1:\d+$/` - Tous les ports 127.0.0.1
- `/^https?:\/\/62\.171\.181\.213:\d+$/` - Tous les ports de votre serveur

---

## 🔧 **Fichiers Modifiés**

### **1. `config/cors.php`**
```php
'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:3001',
    'http://127.0.0.1:3000',
    'http://127.0.0.1:3001',
    'http://62.171.181.213:3000',
    'https://62.171.181.213:3000',
],
```

### **2. `app/Http/Middleware/HandleCors.php`**
- Middleware personnalisé pour une meilleure gestion CORS
- Support des patterns d'URL
- Gestion des requêtes preflight OPTIONS

### **3. `app/Http/Kernel.php`**
- Middleware CORS enregistré globalement

---

## 🚀 **Commandes de Redémarrage**

### **Pour Appliquer les Changements**
```bash
# Redémarrer les conteneurs Docker
docker-compose restart

# Ou reconstruction complète
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### **Vérifier la Configuration**
```bash
# Vérifier les logs
docker-compose logs -f app

# Tester une requête CORS
curl -H "Origin: http://62.171.181.213:3000" \
     -H "Access-Control-Request-Method: GET" \
     -H "Access-Control-Request-Headers: X-Requested-With" \
     -X OPTIONS \
     http://62.171.181.213:8000/api/dashboard
```

---

## 🔍 **Test de la Configuration CORS**

### **Test avec cURL**
```bash
# Test de requête preflight
curl -X OPTIONS \
  -H "Origin: http://62.171.181.213:3000" \
  -H "Access-Control-Request-Method: GET" \
  -H "Access-Control-Request-Headers: Content-Type,Authorization" \
  -v \
  http://62.171.181.213:8000/api/dashboard
```

### **Réponse Attendue**
```
HTTP/1.1 200 OK
Access-Control-Allow-Origin: http://62.171.181.213:3000
Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
Access-Control-Allow-Headers: Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin
Access-Control-Allow-Credentials: true
Access-Control-Max-Age: 86400
```

---

## 🛠️ **Configuration Frontend**

### **Fichier `.env.local` (à créer)**
```env
NEXT_PUBLIC_API_URL=http://62.171.181.213:8000/api
NEXT_PUBLIC_APP_URL=http://62.171.181.213:3000
```

### **Configuration API (déjà mise à jour)**
```typescript
// lib/services/api.ts
const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || "http://62.171.181.213:8000/api",
  withCredentials: true,
})
```

---

## 🚨 **Dépannage**

### **Erreur CORS Persistante**
1. **Vérifier les logs Laravel** :
   ```bash
   docker-compose logs app | grep -i cors
   ```

2. **Vérifier la configuration** :
   ```bash
   docker-compose exec app php artisan config:show cors
   ```

3. **Nettoyer le cache** :
   ```bash
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan cache:clear
   ```

### **Erreur "Access-Control-Allow-Origin"**
- Vérifier que l'URL exacte est dans `allowed_origins`
- Vérifier que le protocole (http/https) correspond
- Vérifier que le port correspond

### **Erreur "Access-Control-Allow-Headers"**
- Vérifier que tous les headers nécessaires sont dans `allowed_headers`
- Vérifier que `Authorization` est inclus pour les requêtes authentifiées

---

## 📋 **Checklist de Vérification**

- [ ] ✅ Configuration CORS mise à jour
- [ ] ✅ Middleware CORS enregistré
- [ ] ✅ URL serveur ajoutée aux origines autorisées
- [ ] ✅ Frontend configuré avec la bonne URL API
- [ ] ✅ Conteneurs redémarrés
- [ ] ✅ Test CORS réussi

---

## 🔄 **Prochaines Étapes**

1. **Redémarrer les conteneurs** pour appliquer les changements
2. **Tester l'API** depuis le frontend
3. **Vérifier les logs** en cas d'erreur
4. **Ajouter d'autres domaines** si nécessaire

---

*Configuration mise à jour le : 18 Septembre 2025*
