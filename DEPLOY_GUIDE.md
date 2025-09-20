# 🚀 Guide de Déploiement GESFARM sur Vercel

## 📋 Prérequis

- ✅ Compte Vercel
- ✅ Base de données externe (PlanetScale, Railway, Supabase)
- ✅ Node.js installé
- ✅ Vercel CLI installé

## 🔧 Étape 1: Préparation

### 1.1 Installation de Vercel CLI
```bash
npm install -g vercel
```

### 1.2 Connexion à Vercel
```bash
vercel login
```

### 1.3 Génération de la clé d'application
```bash
php artisan key:generate --show
```
**⚠️ IMPORTANT**: Copiez cette clé, vous en aurez besoin pour la configuration Vercel.

## 🗄️ Étape 2: Configuration de la Base de Données

### Option A: PlanetScale (Recommandé)
1. Créez un compte sur [PlanetScale](https://planetscale.com)
2. Créez une nouvelle base de données
3. Notez les credentials :
   - Host: `aws.connect.psdb.cloud`
   - Database: `votre-nom-db`
   - Username: `votre-username`
   - Password: `votre-password`

### Option B: Railway
1. Créez un compte sur [Railway](https://railway.app)
2. Créez un service MySQL
3. Copiez les credentials depuis le dashboard

### Option C: Supabase
1. Créez un compte sur [Supabase](https://supabase.com)
2. Créez un nouveau projet
3. Utilisez les credentials PostgreSQL

## 🚀 Étape 3: Déploiement

### Méthode 1: Script PowerShell (Windows)
```powershell
.\deploy-vercel.ps1
```

### Méthode 2: Script Bash (Linux/Mac)
```bash
chmod +x deploy-vercel.sh
./deploy-vercel.sh
```

### Méthode 3: Manuel
```bash
# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Déployer
vercel --prod
```

## ⚙️ Étape 4: Configuration des Variables d'Environnement

Dans le dashboard Vercel, ajoutez ces variables :

### Variables Obligatoires
```bash
APP_NAME=GESFARM
APP_ENV=production
APP_KEY=base64:votre-clé-générée-ici
APP_DEBUG=false
APP_URL=https://votre-app.vercel.app

DB_CONNECTION=mysql
DB_HOST=votre-hôte-base-de-données
DB_PORT=3306
DB_DATABASE=votre-nom-base-de-données
DB_USERNAME=votre-utilisateur
DB_PASSWORD=votre-mot-de-passe

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=log
```

### Variables CORS
```bash
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:3000,localhost:3001,gesfarm-frontend.vercel.app
SESSION_DOMAIN=null
```

## 🗃️ Étape 5: Migrations et Seeders

### 5.1 Récupérer les variables d'environnement
```bash
vercel env pull .env.local
```

### 5.2 Exécuter les migrations
```bash
php artisan migrate --force
```

### 5.3 Exécuter les seeders (optionnel)
```bash
php artisan db:seed --force
```

## 🧪 Étape 6: Test de l'API

### 6.1 Test de base
```bash
curl https://votre-app.vercel.app/api/
```

### 6.2 Test CORS
```powershell
.\test-vercel-cors.ps1 -ApiUrl "https://votre-app.vercel.app" -FrontendUrl "https://gesfarm-frontend.vercel.app"
```

### 6.3 Test d'authentification
```bash
curl -X POST https://votre-app.vercel.app/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

## 🔗 Étape 7: Mise à Jour du Frontend

Mettez à jour l'URL de l'API dans votre frontend Next.js :

```typescript
// lib/services/api.ts
const baseURL = "https://votre-app.vercel.app/api"
```

## 📊 Étape 8: Monitoring

### 8.1 Logs Vercel
```bash
vercel logs
vercel logs --follow
```

### 8.2 Métriques
- Dashboard Vercel > Analytics
- Dashboard Vercel > Functions

## 🛠️ Configuration CORS Avancée

### Middleware CORS Personnalisé
Le projet inclut un middleware CORS personnalisé (`VercelCorsMiddleware`) qui :
- ✅ Gère les requêtes preflight OPTIONS
- ✅ Autorise les origines spécifiques
- ✅ Supporte les credentials
- ✅ Optimisé pour Vercel

### Configuration dans vercel.json
```json
{
  "headers": [
    {
      "source": "/api/(.*)",
      "headers": [
        {
          "key": "Access-Control-Allow-Origin",
          "value": "https://gesfarm-frontend.vercel.app"
        },
        {
          "key": "Access-Control-Allow-Methods",
          "value": "GET, POST, PUT, PATCH, DELETE, OPTIONS"
        },
        {
          "key": "Access-Control-Allow-Headers",
          "value": "Content-Type, Accept, Authorization, X-Requested-With, Application, X-CSRF-TOKEN"
        },
        {
          "key": "Access-Control-Allow-Credentials",
          "value": "true"
        }
      ]
    }
  ]
}
```

## 🆘 Dépannage

### Erreur 500
1. Vérifiez les logs Vercel : `vercel logs`
2. Vérifiez les variables d'environnement
3. Vérifiez la connexion à la base de données

### Erreur CORS
1. Vérifiez la configuration CORS dans `config/cors.php`
2. Vérifiez le middleware `VercelCorsMiddleware`
3. Vérifiez les headers dans `vercel.json`
4. Testez avec le script `test-vercel-cors.ps1`

### Timeout
1. Optimisez vos requêtes
2. Réduisez la complexité des opérations
3. Utilisez le cache quand possible

### Base de données
1. Vérifiez les credentials
2. Vérifiez la connectivité
3. Vérifiez les migrations

## 📈 Optimisations

### Performance
- ✅ Cache des configurations Laravel
- ✅ Cache des routes
- ✅ Cache des vues
- ✅ Optimisation des dépendances Composer

### Sécurité
- ✅ Variables d'environnement sécurisées
- ✅ CORS configuré correctement
- ✅ Headers de sécurité
- ✅ Authentification Sanctum

### Monitoring
- ✅ Logs centralisés
- ✅ Métriques de performance
- ✅ Alertes d'erreur

## 🎯 URLs Finales

Après déploiement :
- **API Backend** : `https://votre-app.vercel.app/api/`
- **Frontend** : `https://gesfarm-frontend.vercel.app`
- **Documentation API** : `https://votre-app.vercel.app/api/documentation`

## ✅ Checklist de Déploiement

- [ ] Vercel CLI installé et connecté
- [ ] Base de données externe configurée
- [ ] Clé d'application générée
- [ ] Variables d'environnement configurées dans Vercel
- [ ] Code déployé sur Vercel
- [ ] Migrations exécutées
- [ ] API testée
- [ ] CORS testé
- [ ] Frontend mis à jour
- [ ] Monitoring configuré

## 🎉 Félicitations !

Votre API GESFARM est maintenant déployée sur Vercel avec une gestion CORS complète !

### Prochaines étapes :
1. Déployez votre frontend Next.js sur Vercel
2. Configurez le monitoring
3. Mettez en place les alertes
4. Optimisez les performances
