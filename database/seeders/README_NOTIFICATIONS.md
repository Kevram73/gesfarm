# 🔔 Résolution du Problème de Table Notifications

## ❌ Problème Identifié

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'gesfarm.notifications' doesn't exist
```

L'erreur indique que la table `notifications` n'existe pas dans la base de données, bien que la migration et le modèle existent.

## ✅ Solutions Disponibles

### Option 1: Exécuter les Migrations Laravel (Recommandé)

```bash
cd C:\Users\LENOVO\Documents\codes\gesfarm
php artisan migrate
```

### Option 2: Créer Manuellement la Table (Alternative)

Si les migrations Laravel ne fonctionnent pas, utilisez les scripts fournis :

#### Avec PowerShell :
```powershell
cd C:\Users\LENOVO\Documents\codes\gesfarm\database\seeders
.\run_notifications_setup.ps1
```

#### Avec Batch :
```cmd
cd C:\Users\LENOVO\Documents\codes\gesfarm\database\seeders
run_notifications_setup.bat
```

#### Manuellement avec MySQL :
```sql
mysql -u root -p gesfarm < create_notifications_table.sql
```

## 📋 Fichiers Créés

1. **`create_notifications_table.sql`** - Script SQL pour créer la table et insérer les données
2. **`run_notifications_setup.ps1`** - Script PowerShell automatisé
3. **`run_notifications_setup.bat`** - Script Batch pour Windows
4. **`README_NOTIFICATIONS.md`** - Ce fichier d'instructions

## 🗃️ Structure de la Table

```sql
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('unread','read','archived') NOT NULL DEFAULT 'unread',
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `related_entity_id` bigint(20) unsigned DEFAULT NULL,
  `related_entity_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_type_status_index` (`type`,`status`),
  KEY `notifications_user_id_status_index` (`user_id`,`status`),
  KEY `notifications_scheduled_at_index` (`scheduled_at`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 📊 Données d'Exemple

Le script insère **10 notifications d'exemple** avec différents types :

- **Stock Alert** - Alerte de stock faible
- **Vaccination Reminder** - Rappel de vaccination
- **Egg Collection** - Collecte d'œufs
- **Weather Alert** - Alerte météo
- **Maintenance Reminder** - Rappel de maintenance
- **Production Milestone** - Objectif de production atteint
- **Health Alert** - Alerte santé
- **Financial Alert** - Alerte financière
- **Task Reminder** - Rappel de tâche
- **System Backup** - Sauvegarde système

## 🔧 Vérification

Après avoir exécuté le script, vérifiez que la table existe :

```sql
USE gesfarm;
SHOW TABLES LIKE 'notifications';
SELECT COUNT(*) FROM notifications;
```

## 🚀 Test de l'API

Une fois la table créée, testez l'endpoint :

```
GET http://localhost:8000/api/notifications?per_page=5
```

## 📝 Notes Importantes

1. **Prérequis** : La table `users` doit exister (clé étrangère)
2. **Permissions** : L'utilisateur MySQL doit avoir les droits de création de table
3. **Base de données** : Assurez-vous que la base `gesfarm` existe
4. **Configuration** : Adaptez les paramètres de connexion dans les scripts si nécessaire

## 🆘 En Cas de Problème

Si vous rencontrez des erreurs :

1. Vérifiez que MySQL est démarré
2. Vérifiez que la base de données `gesfarm` existe
3. Vérifiez que l'utilisateur a les permissions nécessaires
4. Vérifiez que la table `users` existe
5. Consultez les logs MySQL pour plus de détails

## 📞 Support

Pour toute question ou problème, consultez la documentation Laravel ou les logs d'erreur.
