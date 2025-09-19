# Script PowerShell pour tester CORS sur Vercel
param(
    [string]$ApiUrl = "https://your-app.vercel.app",
    [string]$FrontendUrl = "https://your-frontend.vercel.app"
)

Write-Host "🧪 Test CORS pour GESFARM sur Vercel..." -ForegroundColor Green
Write-Host "API URL: $ApiUrl" -ForegroundColor Cyan
Write-Host "Frontend URL: $FrontendUrl" -ForegroundColor Cyan

# Test 1: Requête OPTIONS (Preflight)
Write-Host "`n1. Test requête OPTIONS (Preflight)..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$ApiUrl/api/auth/login" -Method OPTIONS -Headers @{
        "Origin" = $FrontendUrl
        "Access-Control-Request-Method" = "POST"
        "Access-Control-Request-Headers" = "Content-Type, Authorization"
    } -UseBasicParsing
    
    Write-Host "✅ Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Headers CORS:" -ForegroundColor Cyan
    $response.Headers | Where-Object { $_.Key -like "*Access-Control*" } | ForEach-Object {
        Write-Host "  $($_.Key): $($_.Value)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Erreur OPTIONS: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 2: Requête GET simple
Write-Host "`n2. Test requête GET..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$ApiUrl/api/" -Method GET -Headers @{
        "Origin" = $FrontendUrl
    } -UseBasicParsing
    
    Write-Host "✅ Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Headers CORS:" -ForegroundColor Cyan
    $response.Headers | Where-Object { $_.Key -like "*Access-Control*" } | ForEach-Object {
        Write-Host "  $($_.Key): $($_.Value)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Erreur GET: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 3: Test d'authentification
Write-Host "`n3. Test d'authentification..." -ForegroundColor Yellow
try {
    $body = @{
        email = "test@example.com"
        password = "password"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "$ApiUrl/api/auth/login" -Method POST -Body $body -ContentType "application/json" -Headers @{
        "Origin" = $FrontendUrl
    } -UseBasicParsing
    
    Write-Host "✅ Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Headers CORS:" -ForegroundColor Cyan
    $response.Headers | Where-Object { $_.Key -like "*Access-Control*" } | ForEach-Object {
        Write-Host "  $($_.Key): $($_.Value)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Erreur POST: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 4: Test avec credentials
Write-Host "`n4. Test avec credentials..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$ApiUrl/api/user" -Method GET -Headers @{
        "Origin" = $FrontendUrl
        "Authorization" = "Bearer test-token"
    } -UseBasicParsing
    
    Write-Host "✅ Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Headers CORS:" -ForegroundColor Cyan
    $response.Headers | Where-Object { $_.Key -like "*Access-Control*" } | ForEach-Object {
        Write-Host "  $($_.Key): $($_.Value)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ Erreur avec credentials: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n🎯 Résumé des tests CORS:" -ForegroundColor Green
Write-Host "Si tous les tests montrent des headers Access-Control-*, CORS est configuré correctement." -ForegroundColor Cyan
Write-Host "Si vous voyez des erreurs, vérifiez:" -ForegroundColor Yellow
Write-Host "1. Les variables d'environnement dans Vercel" -ForegroundColor White
Write-Host "2. La configuration CORS dans config/cors.php" -ForegroundColor White
Write-Host "3. Le middleware VercelCorsMiddleware" -ForegroundColor White
Write-Host "4. Les headers dans vercel.json" -ForegroundColor White
