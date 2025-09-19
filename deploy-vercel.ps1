# Script PowerShell pour déployer GESFARM sur Vercel
Write-Host "🚀 Déploiement de GESFARM sur Vercel avec gestion CORS..." -ForegroundColor Green

# Vérifier si Node.js est installé
if (!(Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Node.js n'est pas installé. Veuillez l'installer depuis https://nodejs.org" -ForegroundColor Red
    exit 1
}

# Vérifier si Vercel CLI est installé
if (!(Get-Command vercel -ErrorAction SilentlyContinue)) {
    Write-Host "📦 Installation de Vercel CLI..." -ForegroundColor Yellow
    npm install -g vercel
}

# Vérifier si l'utilisateur est connecté à Vercel
try {
    vercel whoami | Out-Null
    Write-Host "✅ Connecté à Vercel" -ForegroundColor Green
} catch {
    Write-Host "🔐 Connexion à Vercel..." -ForegroundColor Yellow
    vercel login
}

# Vérifier si le fichier .env existe
if (!(Test-Path ".env")) {
    Write-Host "📝 Création du fichier .env..." -ForegroundColor Yellow
    Copy-Item ".env.example" ".env"
}

# Générer la clé d'application
Write-Host "🔑 Génération de la clé d'application..." -ForegroundColor Yellow
$appKey = php artisan key:generate --show
Write-Host "⚠️  IMPORTANT: Copiez cette clé et ajoutez-la comme variable APP_KEY dans Vercel:" -ForegroundColor Red
Write-Host $appKey -ForegroundColor Cyan

# Installer les dépendances Composer
Write-Host "📦 Installation des dépendances Composer..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader

# Optimiser l'application Laravel
Write-Host "⚡ Optimisation de l'application..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Nettoyer le cache
Write-Host "🧹 Nettoyage du cache..." -ForegroundColor Yellow
php artisan cache:clear

# Déployer sur Vercel
Write-Host "🚀 Déploiement sur Vercel..." -ForegroundColor Green
vercel --prod

Write-Host "✅ Déploiement terminé!" -ForegroundColor Green
Write-Host "📋 Prochaines étapes:" -ForegroundColor Cyan
Write-Host "1. Configurez les variables d'environnement dans Vercel:" -ForegroundColor White
Write-Host "   - APP_KEY: $appKey" -ForegroundColor Gray
Write-Host "   - DB_CONNECTION: mysql" -ForegroundColor Gray
Write-Host "   - DB_HOST: votre-hôte-base-de-données" -ForegroundColor Gray
Write-Host "   - DB_DATABASE: votre-nom-base-de-données" -ForegroundColor Gray
Write-Host "   - DB_USERNAME: votre-utilisateur" -ForegroundColor Gray
Write-Host "   - DB_PASSWORD: votre-mot-de-passe" -ForegroundColor Gray
Write-Host "2. Configurez votre base de données externe (PlanetScale, Railway, etc.)" -ForegroundColor White
Write-Host "3. Exécutez les migrations:" -ForegroundColor White
Write-Host "   vercel env pull .env.local" -ForegroundColor Gray
Write-Host "   php artisan migrate --force" -ForegroundColor Gray
Write-Host "4. Testez votre API: https://votre-app.vercel.app/api/" -ForegroundColor White
Write-Host "5. Mettez à jour l'URL de l'API dans votre frontend" -ForegroundColor White
