# Script PowerShell pour tester la configuration Nginx CORS

Write-Host "🧪 Test de la configuration Nginx CORS..." -ForegroundColor Cyan

# Tester la configuration Nginx
Write-Host "📋 Vérification de la syntaxe Nginx..." -ForegroundColor Yellow
$nginxTest = docker-compose exec nginx nginx -t

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Configuration Nginx valide" -ForegroundColor Green
    
    # Recharger la configuration Nginx
    Write-Host "🔄 Rechargement de la configuration Nginx..." -ForegroundColor Yellow
    docker-compose exec nginx nginx -s reload
    
    Write-Host "✅ Configuration Nginx rechargée" -ForegroundColor Green
} else {
    Write-Host "❌ Erreur dans la configuration Nginx" -ForegroundColor Red
    exit 1
}

# Tester les requêtes CORS
Write-Host "🧪 Test des requêtes CORS..." -ForegroundColor Yellow

Write-Host "1. Test OPTIONS preflight pour /api/login:" -ForegroundColor Cyan
try {
    $response1 = Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method OPTIONS -Headers @{
        "Origin" = "http://localhost:3000"
        "Access-Control-Request-Method" = "POST"
        "Access-Control-Request-Headers" = "Content-Type,Authorization"
    } -UseBasicParsing
    
    Write-Host "✅ Test 1 réussi" -ForegroundColor Green
    Write-Host "Headers de réponse:" -ForegroundColor Cyan
    $response1.Headers | Format-Table
} catch {
    Write-Host "❌ Test 1 échoué: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n2. Test OPTIONS preflight pour /sanctum/csrf-cookie:" -ForegroundColor Cyan
try {
    $response2 = Invoke-WebRequest -Uri "http://localhost:8000/sanctum/csrf-cookie" -Method OPTIONS -Headers @{
        "Origin" = "http://localhost:3000"
        "Access-Control-Request-Method" = "GET"
        "Access-Control-Request-Headers" = "Content-Type"
    } -UseBasicParsing
    
    Write-Host "✅ Test 2 réussi" -ForegroundColor Green
    Write-Host "Headers de réponse:" -ForegroundColor Cyan
    $response2.Headers | Format-Table
} catch {
    Write-Host "❌ Test 2 échoué: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n3. Test GET simple:" -ForegroundColor Cyan
try {
    $response3 = Invoke-WebRequest -Uri "http://localhost:8000/api/health" -Method GET -Headers @{
        "Origin" = "http://localhost:3000"
    } -UseBasicParsing
    
    Write-Host "✅ Test 3 réussi" -ForegroundColor Green
    Write-Host "Headers de réponse:" -ForegroundColor Cyan
    $response3.Headers | Format-Table
} catch {
    Write-Host "❌ Test 3 échoué: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n✅ Tests CORS terminés!" -ForegroundColor Green
