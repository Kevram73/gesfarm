-- =====================================================
-- CRÉATION DE LA TABLE NOTIFICATIONS
-- =====================================================
-- Ce script crée la table notifications et insère des données d'exemple
-- Utilisation: mysql -u username -p database_name < create_notifications_table.sql
-- =====================================================

-- Désactiver les vérifications de clés étrangères temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- Créer la table notifications
CREATE TABLE IF NOT EXISTS `notifications` (
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

-- Insérer des données d'exemple
INSERT INTO notifications (id, type, title, message, priority, status, data, read_at, scheduled_at, user_id, related_entity_id, related_entity_type, created_at, updated_at) VALUES
(1, 'stock_alert', 'Stock Faible - Aliments Volailles', 'Le stock d\'aliments pour volailles est en dessous du seuil minimum (50 kg restants).', 'high', 'unread', '{"item_id": 1, "current_stock": 45, "minimum_stock": 50}', NULL, NULL, 1, 1, 'stock_item', NOW(), NOW()),
(2, 'vaccination_reminder', 'Rappel Vaccination - Troupeau Bovin', 'La vaccination annuelle du troupeau bovin est prévue dans 3 jours.', 'medium', 'unread', '{"cattle_count": 25, "vaccine_type": "FMD", "due_date": "2025-01-25"}', NULL, '2025-01-25 08:00:00', 1, NULL, NULL, NOW(), NOW()),
(3, 'egg_collection', 'Collecte d\'Œufs - Poulailler A', 'Collecte quotidienne d\'œufs effectuée : 45 œufs collectés.', 'low', 'read', '{"eggs_collected": 45, "flock_id": 1, "collection_time": "2025-01-22 16:30:00"}', '2025-01-22 16:35:00', NULL, 2, 1, 'poultry_flock', NOW(), NOW()),
(4, 'weather_alert', 'Alerte Météo - Pluie Prévue', 'Pluie prévue dans les prochaines heures. Pensez à protéger les cultures sensibles.', 'medium', 'unread', '{"weather_type": "rain", "intensity": "moderate", "duration": "2-3 hours"}', NULL, NULL, 1, NULL, NULL, NOW(), NOW()),
(5, 'maintenance_reminder', 'Maintenance Équipement - Tracteur', 'Maintenance périodique du tracteur prévue dans 1 semaine.', 'medium', 'unread', '{"equipment_id": 1, "maintenance_type": "periodic", "due_date": "2025-01-29"}', NULL, '2025-01-29 09:00:00', 1, 1, 'equipment', NOW(), NOW()),
(6, 'production_milestone', 'Objectif Production Atteint', 'Félicitations ! L\'objectif de production d\'œufs du mois a été atteint (120%).', 'low', 'read', '{"target": 1000, "achieved": 1200, "percentage": 120, "period": "2025-01"}', '2025-01-22 14:20:00', NULL, 2, NULL, NULL, NOW(), NOW()),
(7, 'health_alert', 'Alerte Santé - Poulailler B', 'Symptômes de maladie détectés dans le poulailler B. Consultation vétérinaire recommandée.', 'urgent', 'unread', '{"flock_id": 2, "symptoms": ["lethargy", "reduced_appetite"], "affected_birds": 3}', NULL, NULL, 4, 2, 'poultry_flock', NOW(), NOW()),
(8, 'financial_alert', 'Dépense Importante - Achat Aliments', 'Dépense importante enregistrée : 250,000 FCFA pour l\'achat d\'aliments.', 'medium', 'unread', '{"amount": 250000, "category": "feed", "supplier": "AgroSupply CI"}', NULL, NULL, 1, 3, 'transaction', NOW(), NOW()),
(9, 'task_reminder', 'Tâche en Retard - Nettoyage Zones', 'La tâche de nettoyage des zones de pâturage est en retard de 2 jours.', 'high', 'unread', '{"task_id": 1, "due_date": "2025-01-20", "days_overdue": 2}', NULL, NULL, 3, 1, 'task', NOW(), NOW()),
(10, 'system_backup', 'Sauvegarde Système Réussie', 'La sauvegarde automatique du système a été effectuée avec succès.', 'low', 'read', '{"backup_size": "2.5GB", "duration": "15 minutes", "status": "success"}', '2025-01-22 02:15:00', NULL, 1, 1, 'backup', NOW(), NOW());

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Afficher un message de confirmation
SELECT 'Table notifications créée avec succès avec 10 enregistrements d\'exemple' AS message;
