# Guide de Déploiement Laravel sur Vercel

## 🚀 Configuration pour Vercel

### 1. Prérequis
- Compte Vercel
- Base de données externe (PlanetScale, Railway, ou autre)
- Variables d'environnement configurées

### 2. Variables d'Environnement à Configurer

Dans le dashboard Vercel, ajoutez ces variables :

```bash
# Application
APP_NAME=GESFARM
APP_ENV=production
APP_KEY=base64:your-generated-app-key
APP_DEBUG=false
APP_URL=https://your-app-name.vercel.app

# Base de données
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

# Cache et sessions (optimisés pour serverless)
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=log
```

### 3. Génération de la Clé d'Application

```bash
# Localement, générez une clé
php artisan key:generate --show

# Copiez la clé générée dans APP_KEY sur Vercel
```

### 4. Base de Données Recommandées

#### Option 1: PlanetScale (Gratuit)
- Créez un compte sur [PlanetScale](https://planetscale.com)
- Créez une nouvelle base de données
- Copiez les credentials dans Vercel

#### Option 2: Railway (Gratuit)
- Créez un compte sur [Railway](https://railway.app)
- Créez un service MySQL
- Copiez les credentials dans Vercel

#### Option 3: Supabase (Gratuit)
- Créez un compte sur [Supabase](https://supabase.com)
- Créez un nouveau projet
- Utilisez les credentials PostgreSQL

### 5. Déploiement

#### Méthode 1: Via CLI Vercel
```bash
# Installez Vercel CLI
npm i -g vercel

# Connectez-vous
vercel login

# Déployez
vercel --prod
```

#### Méthode 2: Via GitHub
1. Poussez votre code sur GitHub
2. Connectez votre repo à Vercel
3. Configurez les variables d'environnement
4. Déployez

### 6. Configuration Post-Déploiement

#### Migrations et Seeders
```bash
# Via Vercel CLI (après déploiement)
vercel env pull .env.local
php artisan migrate --force
php artisan db:seed --force
```

#### Ou via une fonction API temporaire
Créez une route temporaire pour les migrations :

```php
// routes/api.php
Route::post('/setup', function() {
    Artisan::call('migrate', ['--force' => true]);
    Artisan::call('db:seed', ['--force' => true]);
    return response()->json(['message' => 'Setup completed']);
});
```

### 7. Optimisations pour Vercel

#### Cache Configuration
```php
// config/cache.php - Ajoutez pour Vercel
'vercel' => [
    'driver' => 'array',
],
```

#### Session Configuration
```php
// config/session.php - Optimisé pour serverless
'lifetime' => 120, // 2 heures
'expire_on_close' => true,
'encrypt' => false, // Pas de chiffrement pour les performances
```

### 8. Limitations Vercel

- **Timeout**: 30 secondes max par requête
- **Mémoire**: 1024 MB max
- **Stockage**: Pas de stockage persistant
- **Queue**: Pas de workers en arrière-plan

### 9. Solutions pour les Limitations

#### Stockage de Fichiers
Utilisez des services externes :
- AWS S3
- Cloudinary
- Vercel Blob

#### Queue et Jobs
- Utilisez des services externes (Redis, AWS SQS)
- Ou traitez les jobs de manière synchrone

#### Cache
- Utilisez Vercel KV (Redis)
- Ou cache en mémoire (array driver)

### 10. Monitoring et Debugging

#### Logs Vercel
```bash
# Voir les logs
vercel logs

# Logs en temps réel
vercel logs --follow
```

#### Debugging
```php
// Dans vos contrôleurs, utilisez
Log::info('Debug info', $data);
```

### 11. URLs de l'API

Après déploiement, votre API sera disponible à :
- `https://your-app-name.vercel.app/api/`
- `https://your-app-name.vercel.app/sanctum/`

### 12. Mise à Jour du Frontend

Mettez à jour l'URL de l'API dans votre frontend :

```typescript
// lib/services/api.ts
const baseURL = "https://your-app-name.vercel.app/api"
```

## ✅ Checklist de Déploiement

- [ ] Variables d'environnement configurées
- [ ] Base de données externe créée
- [ ] Clé d'application générée
- [ ] Code poussé sur GitHub
- [ ] Projet Vercel créé
- [ ] Déploiement réussi
- [ ] Migrations exécutées
- [ ] API testée
- [ ] Frontend mis à jour
- [ ] CORS configuré

## 🆘 Dépannage

### Erreur 500
- Vérifiez les logs Vercel
- Vérifiez les variables d'environnement
- Vérifiez la connexion à la base de données

### Erreur CORS
- Vérifiez la configuration CORS dans `vercel.json`
- Vérifiez les headers dans `config/cors.php`

### Timeout
- Optimisez vos requêtes
- Réduisez la complexité des opérations
- Utilisez le cache quand possible
