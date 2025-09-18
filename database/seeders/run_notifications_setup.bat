@echo off
REM =====================================================
REM SCRIPT BATCH POUR CRÉER LA TABLE NOTIFICATIONS
REM =====================================================
REM Ce script exécute le fichier SQL pour créer la table notifications
REM =====================================================

echo =====================================================
echo CRÉATION DE LA TABLE NOTIFICATIONS
echo =====================================================

REM Configuration de la base de données (à adapter selon votre configuration)
set DB_HOST=localhost
set DB_PORT=3306
set DB_NAME=gesfarm
set DB_USER=root
set DB_PASSWORD=

echo Configuration de la base de données:
echo   Host: %DB_HOST%
echo   Port: %DB_PORT%
echo   Database: %DB_NAME%
echo   User: %DB_USER%

REM Vérifier si le fichier SQL existe
set SQL_FILE=create_notifications_table.sql
if not exist "%SQL_FILE%" (
    echo ERREUR: Le fichier %SQL_FILE% n'existe pas!
    echo Assurez-vous d'être dans le bon répertoire.
    pause
    exit /b 1
)

echo.
echo Fichier SQL trouvé: %SQL_FILE%

REM Construire la commande MySQL
set MYSQL_CMD=mysql -h %DB_HOST% -P %DB_PORT% -u %DB_USER%

if not "%DB_PASSWORD%"=="" (
    set MYSQL_CMD=%MYSQL_CMD% -p%DB_PASSWORD%
)

set MYSQL_CMD=%MYSQL_CMD% %DB_NAME% ^< %SQL_FILE%

echo.
echo Commande à exécuter:
echo %MYSQL_CMD%

REM Demander confirmation
echo.
set /p confirmation="Voulez-vous exécuter cette commande? (y/N): "
if /i not "%confirmation%"=="y" (
    echo Opération annulée.
    pause
    exit /b 0
)

REM Exécuter la commande
echo.
echo Exécution en cours...
%MYSQL_CMD%

if %errorlevel% equ 0 (
    echo.
    echo ✅ Table notifications créée avec succès!
    echo ✅ 10 enregistrements d'exemple insérés!
) else (
    echo.
    echo ❌ Erreur lors de l'exécution
    echo.
    echo Vérifiez:
    echo   - Que MySQL est démarré
    echo   - Que la base de données '%DB_NAME%' existe
    echo   - Que l'utilisateur '%DB_USER%' a les permissions
    echo   - Que la table 'users' existe (clé étrangère)
)

echo.
echo =====================================================
echo FIN DU SCRIPT
echo =====================================================
pause
