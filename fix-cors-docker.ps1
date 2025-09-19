# Script PowerShell pour corriger les problèmes CORS avec Docker

Write-Host "🔧 Correction des problèmes CORS avec Docker..." -ForegroundColor Cyan

# Arrêter les conteneurs
Write-Host "⏹️ Arrêt des conteneurs Docker..." -ForegroundColor Yellow
docker-compose down

# Nettoyer les volumes et images
Write-Host "🧹 Nettoyage des volumes et images..." -ForegroundColor Yellow
docker-compose down --volumes --remove-orphans
docker system prune -f

# Reconstruire les images
Write-Host "🏗️ Reconstruction des images Docker..." -ForegroundColor Yellow
docker-compose build --no-cache

# Démarrer les services
Write-Host "▶️ Démarrage des services..." -ForegroundColor Yellow
docker-compose up -d

# Attendre que les services soient prêts
Write-Host "⏳ Attente du démarrage des services..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# Vérifier l'état des conteneurs
Write-Host "📋 État des conteneurs:" -ForegroundColor Green
docker-compose ps

# Exécuter les migrations
Write-Host "🗄️ Exécution des migrations..." -ForegroundColor Yellow
docker-compose exec app php artisan migrate --force

# Nettoyer le cache Laravel
Write-Host "🧹 Nettoyage du cache Laravel..." -ForegroundColor Yellow
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear

# Tester la configuration CORS
Write-Host "🧪 Test de la configuration CORS..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method OPTIONS -Headers @{
        "Origin" = "http://localhost:3000"
        "Access-Control-Request-Method" = "POST"
        "Access-Control-Request-Headers" = "Content-Type,Authorization"
    } -UseBasicParsing
    
    Write-Host "✅ Test CORS réussi!" -ForegroundColor Green
    Write-Host "Headers de réponse:" -ForegroundColor Cyan
    $response.Headers | Format-Table
} catch {
    Write-Host "❌ Test CORS échoué: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "✅ Correction CORS terminée!" -ForegroundColor Green
Write-Host "🌐 API accessible à: http://localhost:8000/api" -ForegroundColor Cyan
Write-Host "📚 Documentation API: http://localhost:8000/docs" -ForegroundColor Cyan
