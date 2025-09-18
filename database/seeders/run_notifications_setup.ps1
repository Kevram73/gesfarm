# =====================================================
# SCRIPT POWERSHELL POUR CRÉER LA TABLE NOTIFICATIONS
# =====================================================
# Ce script exécute le fichier SQL pour créer la table notifications
# =====================================================

Write-Host "=====================================================" -ForegroundColor Green
Write-Host "CRÉATION DE LA TABLE NOTIFICATIONS" -ForegroundColor Green
Write-Host "=====================================================" -ForegroundColor Green

# Configuration de la base de données (à adapter selon votre configuration)
$DB_HOST = "localhost"
$DB_PORT = "3306"
$DB_NAME = "gesfarm"
$DB_USER = "root"
$DB_PASSWORD = ""

Write-Host "Configuration de la base de données:" -ForegroundColor Yellow
Write-Host "  Host: $DB_HOST" -ForegroundColor White
Write-Host "  Port: $DB_PORT" -ForegroundColor White
Write-Host "  Database: $DB_NAME" -ForegroundColor White
Write-Host "  User: $DB_USER" -ForegroundColor White

# Vérifier si le fichier SQL existe
$SQL_FILE = "create_notifications_table.sql"
if (-not (Test-Path $SQL_FILE)) {
    Write-Host "ERREUR: Le fichier $SQL_FILE n'existe pas!" -ForegroundColor Red
    Write-Host "Assurez-vous d'être dans le bon répertoire." -ForegroundColor Red
    exit 1
}

Write-Host "`nFichier SQL trouvé: $SQL_FILE" -ForegroundColor Green

# Construire la commande MySQL
$MYSQL_CMD = "mysql -h $DB_HOST -P $DB_PORT -u $DB_USER"

if ($DB_PASSWORD -ne "") {
    $MYSQL_CMD += " -p$DB_PASSWORD"
}

$MYSQL_CMD += " $DB_NAME < $SQL_FILE"

Write-Host "`nCommande à exécuter:" -ForegroundColor Yellow
Write-Host $MYSQL_CMD -ForegroundColor White

# Demander confirmation
$confirmation = Read-Host "`nVoulez-vous exécuter cette commande? (y/N)"
if ($confirmation -ne 'y' -and $confirmation -ne 'Y') {
    Write-Host "Opération annulée." -ForegroundColor Yellow
    exit 0
}

# Exécuter la commande
Write-Host "`nExécution en cours..." -ForegroundColor Yellow
try {
    Invoke-Expression $MYSQL_CMD
    Write-Host "`n✅ Table notifications créée avec succès!" -ForegroundColor Green
    Write-Host "✅ 10 enregistrements d'exemple insérés!" -ForegroundColor Green
} catch {
    Write-Host "`n❌ Erreur lors de l'exécution:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host "`nVérifiez:" -ForegroundColor Yellow
    Write-Host "  - Que MySQL est démarré" -ForegroundColor White
    Write-Host "  - Que la base de données '$DB_NAME' existe" -ForegroundColor White
    Write-Host "  - Que l'utilisateur '$DB_USER' a les permissions" -ForegroundColor White
    Write-Host "  - Que la table 'users' existe (clé étrangère)" -ForegroundColor White
}

Write-Host "`n=====================================================" -ForegroundColor Green
Write-Host "FIN DU SCRIPT" -ForegroundColor Green
Write-Host "=====================================================" -ForegroundColor Green
