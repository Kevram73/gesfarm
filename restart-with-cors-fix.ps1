# Script PowerShell pour redémarrer Docker avec correction CORS

Write-Host "🔧 Redémarrage Docker avec correction CORS..." -ForegroundColor Cyan

# Arrêter les conteneurs
Write-Host "⏹️ Arrêt des conteneurs Docker..." -ForegroundColor Yellow
docker-compose down

# Nettoyer les volumes et images
Write-Host "🧹 Nettoyage des volumes et images..." -ForegroundColor Yellow
docker-compose down --volumes --remove-orphans

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

# Tester la configuration Nginx
Write-Host "🧪 Test de la configuration Nginx..." -ForegroundColor Yellow
docker-compose exec nginx nginx -t

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Configuration Nginx valide" -ForegroundColor Green
    
    # Recharger Nginx
    Write-Host "🔄 Rechargement de Nginx..." -ForegroundColor Yellow
    docker-compose exec nginx nginx -s reload
} else {
    Write-Host "❌ Erreur dans la configuration Nginx" -ForegroundColor Red
}

# Exécuter les migrations
Write-Host "🗄️ Exécution des migrations..." -ForegroundColor Yellow
docker-compose exec app php artisan migrate --force

# Nettoyer le cache Laravel
Write-Host "🧹 Nettoyage du cache Laravel..." -ForegroundColor Yellow
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear

# Tester CORS
Write-Host "🧪 Test de la configuration CORS..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method OPTIONS -Headers @{
        "Origin" = "http://localhost:3000"
        "Access-Control-Request-Method" = "POST"
        "Access-Control-Request-Headers" = "Content-Type,Authorization"
    } -UseBasicParsing
    
    Write-Host "✅ Test CORS réussi!" -ForegroundColor Green
    Write-Host "Headers CORS présents:" -ForegroundColor Cyan
    if ($response.Headers['Access-Control-Allow-Origin']) {
        Write-Host "  - Access-Control-Allow-Origin: $($response.Headers['Access-Control-Allow-Origin'])" -ForegroundColor Green
    }
    if ($response.Headers['Access-Control-Allow-Methods']) {
        Write-Host "  - Access-Control-Allow-Methods: $($response.Headers['Access-Control-Allow-Methods'])" -ForegroundColor Green
    }
    if ($response.Headers['Access-Control-Allow-Headers']) {
        Write-Host "  - Access-Control-Allow-Headers: $($response.Headers['Access-Control-Allow-Headers'])" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Test CORS échoué: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n✅ Redémarrage avec correction CORS terminé!" -ForegroundColor Green
Write-Host "🌐 API accessible à: http://localhost:8000/api" -ForegroundColor Cyan
Write-Host "📚 Documentation API: http://localhost:8000/docs" -ForegroundColor Cyan
Write-Host "🔧 Pour tester CORS: .\test-nginx-cors.ps1" -ForegroundColor Cyan
