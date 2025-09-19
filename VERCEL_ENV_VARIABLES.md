# Variables d'Environnement pour Vercel

## 🔧 Configuration des Variables dans Vercel

### 1. Accédez au Dashboard Vercel
1. Allez sur [vercel.com](https://vercel.com)
2. Sélectionnez votre projet GESFARM
3. Allez dans **Settings** > **Environment Variables**

### 2. Variables Obligatoires

#### Application
```bash
APP_NAME=GESFARM
APP_ENV=production
APP_KEY=base64:your-generated-app-key-here
APP_DEBUG=false
APP_URL=https://your-app-name.vercel.app
```

#### Base de Données
```bash
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password
```

#### Cache et Sessions (optimisés pour serverless)
```bash
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=log
```

#### Sanctum (Authentification API)
```bash
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:3000,localhost:3001,your-frontend-domain.vercel.app
SESSION_DOMAIN=null
```

### 3. Génération de la Clé d'Application

Exécutez cette commande localement pour générer une clé :

```bash
php artisan key:generate --show
```

Copiez la clé générée et ajoutez-la comme variable `APP_KEY` dans Vercel.

### 4. Configuration de la Base de Données

#### Option 1: PlanetScale (Recommandé - Gratuit)
1. Créez un compte sur [PlanetScale](https://planetscale.com)
2. Créez une nouvelle base de données
3. Copiez les credentials :
   - Host: `aws.connect.psdb.cloud`
   - Database: `votre-nom-db`
   - Username: `votre-username`
   - Password: `votre-password`

#### Option 2: Railway (Gratuit)
1. Créez un compte sur [Railway](https://railway.app)
2. Créez un service MySQL
3. Copiez les credentials depuis le dashboard

#### Option 3: Supabase (Gratuit)
1. Créez un compte sur [Supabase](https://supabase.com)
2. Créez un nouveau projet
3. Utilisez les credentials PostgreSQL

### 5. Configuration CORS

Ajoutez ces variables pour la gestion CORS :

```bash
CORS_ALLOWED_ORIGINS=https://your-frontend.vercel.app,https://*.vercel.app
```

### 6. Variables Optionnelles

#### Mail (pour les notifications)
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=GESFARM
```

#### AWS S3 (pour le stockage de fichiers)
```bash
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

### 7. Ordre de Configuration

1. **D'abord** : Configurez toutes les variables d'environnement
2. **Ensuite** : Déployez l'application
3. **Enfin** : Exécutez les migrations

### 8. Commandes Post-Déploiement

```bash
# Récupérer les variables d'environnement
vercel env pull .env.local

# Exécuter les migrations
php artisan migrate --force

# Exécuter les seeders (optionnel)
php artisan db:seed --force
```

### 9. Test de l'API

Une fois déployé, testez votre API :

```bash
# Test de base
curl https://your-app.vercel.app/api/

# Test d'authentification
curl -X POST https://your-app.vercel.app/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

### 10. URLs de l'API

Après déploiement, votre API sera disponible à :
- **Base URL** : `https://your-app.vercel.app`
- **API Routes** : `https://your-app.vercel.app/api/`
- **Sanctum** : `https://your-app.vercel.app/sanctum/`

### 11. Mise à Jour du Frontend

Mettez à jour l'URL de l'API dans votre frontend Next.js :

```typescript
// lib/services/api.ts
const baseURL = "https://your-app.vercel.app/api"
```

### 12. Monitoring

- **Logs** : `vercel logs`
- **Logs en temps réel** : `vercel logs --follow`
- **Métriques** : Dashboard Vercel > Analytics

## ⚠️ Notes Importantes

- Les variables d'environnement sont sensibles, ne les partagez jamais
- Redéployez après avoir modifié les variables d'environnement
- Vercel a une limite de 30 secondes par requête
- Utilisez des services externes pour le stockage de fichiers
- Configurez CORS correctement pour éviter les erreurs
