-- =====================================================
-- DONNÉES DE TEST POUR GESFARM - SYSTÈME DE GESTION AGRICOLE
-- =====================================================
-- Ce fichier contient des données de test pour toutes les tables
-- Utilisation: mysql -u username -p database_name < sample_data.sql
-- =====================================================

-- Désactiver les vérifications de clés étrangères temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- 1. RÔLES ET PERMISSIONS
-- =====================================================

-- Rôles
INSERT INTO roles (id, name, guard_name, created_at, updated_at) VALUES
(1, 'admin', 'web', NOW(), NOW()),
(2, 'manager', 'web', NOW(), NOW()),
(3, 'worker', 'web', NOW(), NOW()),
(4, 'veterinarian', 'web', NOW(), NOW());

-- Permissions
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES
(1, 'view_dashboard', 'web', NOW(), NOW()),
(2, 'manage_users', 'web', NOW(), NOW()),
(3, 'manage_stock', 'web', NOW(), NOW()),
(4, 'manage_poultry', 'web', NOW(), NOW()),
(5, 'manage_cattle', 'web', NOW(), NOW()),
(6, 'manage_crops', 'web', NOW(), NOW()),
(7, 'manage_zones', 'web', NOW(), NOW()),
(8, 'view_reports', 'web', NOW(), NOW()),
(9, 'manage_financial', 'web', NOW(), NOW()),
(10, 'manage_veterinary', 'web', NOW(), NOW());

-- =====================================================
-- 2. UTILISATEURS
-- =====================================================

INSERT INTO users (id, name, email, email_verified_at, password, created_at, updated_at) VALUES
(1, 'Admin GESFARM', 'admin@gesfarm.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(2, 'Jean Kouassi', 'jean.kouassi@gesfarm.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(3, 'Marie Traoré', 'marie.traore@gesfarm.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(4, 'Dr. Paul N\'Guessan', 'paul.nguessan@gesfarm.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(5, 'Fatou Diallo', 'fatou.diallo@gesfarm.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- Attribution des rôles aux utilisateurs
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES
(1, 'App\\Models\\User', 1), -- Admin
(2, 'App\\Models\\User', 2), -- Manager
(2, 'App\\Models\\User', 3), -- Manager
(4, 'App\\Models\\User', 4), -- Veterinarian
(3, 'App\\Models\\User', 5); -- Worker

-- =====================================================
-- 3. CATÉGORIES DE STOCK
-- =====================================================

INSERT INTO stock_categories (id, name, description, type, created_at, updated_at) VALUES
(1, 'Aliments pour Volailles', 'Aliments spécialisés pour poulets, canards, dindes', 'animal_feed', NOW(), NOW()),
(2, 'Aliments pour Bovins', 'Aliments pour bétail, vaches laitières', 'animal_feed', NOW(), NOW()),
(3, 'Engrais', 'Engrais organiques et chimiques pour cultures', 'agricultural_inputs', NOW(), NOW()),
(4, 'Semences', 'Graines et semences pour plantations', 'agricultural_inputs', NOW(), NOW()),
(5, 'Produits Vétérinaires', 'Médicaments et vaccins pour animaux', 'veterinary_products', NOW(), NOW()),
(6, 'Équipements', 'Outils et équipements agricoles', 'equipment', NOW(), NOW()),
(7, 'Matériel d\'Élevage', 'Matériel spécifique à l\'élevage', 'equipment', NOW(), NOW());

-- =====================================================
-- 4. ARTICLES DE STOCK
-- =====================================================

INSERT INTO stock_items (id, name, description, sku, category_id, unit, current_quantity, minimum_quantity, unit_cost, expiry_date, supplier, notes, created_at, updated_at) VALUES
(1, 'Aliment Pondeuses Premium', 'Aliment complet pour poules pondeuses', 'ALM-PON-001', 1, 'kg', 2500.00, 500.00, 450.00, '2024-12-31', 'AgroFeed Côte d\'Ivoire', 'Stock suffisant pour 2 mois', NOW(), NOW()),
(2, 'Aliment Poulets de Chair', 'Aliment croissance pour poulets de chair', 'ALM-POU-002', 1, 'kg', 1800.00, 300.00, 380.00, '2024-11-30', 'AgroFeed Côte d\'Ivoire', 'Stock critique', NOW(), NOW()),
(3, 'Foin de Luzerne', 'Foin de qualité pour bovins', 'FOI-LUZ-001', 2, 'kg', 5000.00, 1000.00, 120.00, '2025-06-30', 'Fermes du Nord', 'Stock excellent', NOW(), NOW()),
(4, 'NPK 15-15-15', 'Engrais complet pour cultures', 'ENG-NPK-001', 3, 'kg', 800.00, 200.00, 850.00, '2025-12-31', 'Fertilizer Plus', 'Stock normal', NOW(), NOW()),
(5, 'Maïs Hybride F1', 'Semences de maïs haute performance', 'SEM-MAI-001', 4, 'kg', 150.00, 50.00, 2500.00, '2025-03-31', 'Semences Tropicales', 'Stock suffisant', NOW(), NOW()),
(6, 'Vaccin Newcastle', 'Vaccin contre la maladie de Newcastle', 'VAC-NEW-001', 5, 'dose', 500.00, 100.00, 150.00, '2024-10-31', 'VetPharma', 'Stock à surveiller', NOW(), NOW()),
(7, 'Pulvérisateur 20L', 'Pulvérisateur à dos pour traitements', 'EQU-PUL-001', 6, 'unité', 3.00, 1.00, 45000.00, NULL, 'AgriEquip', 'Équipement en bon état', NOW(), NOW()),
(8, 'Abreuvoir Automatique', 'Abreuvoir pour volailles', 'EQU-ABR-001', 7, 'unité', 8.00, 2.00, 25000.00, NULL, 'PoultryTech', 'Fonctionnel', NOW(), NOW());

-- =====================================================
-- 5. ZONES DE L'EXPLOITATION
-- =====================================================

INSERT INTO zones (id, name, description, type, coordinates, area, status, created_at, updated_at) VALUES
(1, 'Poulailler Principal', 'Poulailler principal pour pondeuses', 'building', '{"type": "Polygon", "coordinates": [[[-5.5, 6.8], [-5.4, 6.8], [-5.4, 6.9], [-5.5, 6.9], [-5.5, 6.8]]]}', 200.00, 'active', NOW(), NOW()),
(2, 'Poulailler Secondaire', 'Poulailler pour poulets de chair', 'building', '{"type": "Polygon", "coordinates": [[[-5.3, 6.8], [-5.2, 6.8], [-5.2, 6.9], [-5.3, 6.9], [-5.3, 6.8]]]}', 150.00, 'active', NOW(), NOW()),
(3, 'Pâturage Nord', 'Pâturage principal pour bovins', 'pasture', '{"type": "Polygon", "coordinates": [[[-5.6, 6.9], [-5.4, 6.9], [-5.4, 7.1], [-5.6, 7.1], [-5.6, 6.9]]]}', 5000.00, 'active', NOW(), NOW()),
(4, 'Parcelle Maïs A', 'Parcelle de maïs principale', 'cultivation', '{"type": "Polygon", "coordinates": [[[-5.7, 6.7], [-5.5, 6.7], [-5.5, 6.8], [-5.7, 6.8], [-5.7, 6.7]]]}', 2000.00, 'active', NOW(), NOW()),
(5, 'Parcelle Riz B', 'Parcelle de riz irrigué', 'cultivation', '{"type": "Polygon", "coordinates": [[[-5.8, 6.6], [-5.6, 6.6], [-5.6, 6.7], [-5.8, 6.7], [-5.8, 6.6]]]}', 1500.00, 'active', NOW(), NOW()),
(6, 'Enclos Bovins', 'Enclos pour bovins en stabulation', 'enclosure', '{"type": "Polygon", "coordinates": [[[-5.5, 6.9], [-5.3, 6.9], [-5.3, 7.0], [-5.5, 7.0], [-5.5, 6.9]]]}', 300.00, 'active', NOW(), NOW()),
(7, 'Point d\'Eau Principal', 'Bassin d\'eau pour irrigation', 'water_point', '{"type": "Point", "coordinates": [-5.6, 6.8]}', 50.00, 'active', NOW(), NOW()),
(8, 'Entrepôt Central', 'Entrepôt pour stockage des intrants', 'building', '{"type": "Polygon", "coordinates": [[[-5.4, 6.7], [-5.3, 6.7], [-5.3, 6.8], [-5.4, 6.8], [-5.4, 6.7]]]}', 100.00, 'active', NOW(), NOW());

-- =====================================================
-- 6. LOTS DE VOLAILLES
-- =====================================================

INSERT INTO poultry_flocks (id, flock_number, type, breed, initial_quantity, current_quantity, arrival_date, age_days, zone_id, status, notes, created_at, updated_at) VALUES
(1, 'FLK-2024-001', 'layer', 'Isa Brown', 500, 485, '2024-01-15', 120, 1, 'active', 'Poules pondeuses en production', NOW(), NOW()),
(2, 'FLK-2024-002', 'broiler', 'Cobb 500', 1000, 950, '2024-02-01', 45, 2, 'active', 'Poulets de chair en croissance', NOW(), NOW()),
(3, 'FLK-2024-003', 'layer', 'Leghorn', 300, 295, '2024-01-20', 115, 1, 'active', 'Poules pondeuses blanches', NOW(), NOW()),
(4, 'FLK-2024-004', 'broiler', 'Ross 308', 800, 780, '2024-02-10', 35, 2, 'active', 'Poulets de chair deuxième lot', NOW(), NOW()),
(5, 'FLK-2023-005', 'layer', 'Isa Brown', 400, 0, '2023-12-01', 365, 1, 'sold', 'Lot vendu après 12 mois', NOW(), NOW());

-- =====================================================
-- 7. BOVINS
-- =====================================================

INSERT INTO cattle (id, tag_number, name, breed, gender, birth_date, mother_tag, father_tag, current_weight, status, zone_id, notes, created_at, updated_at) VALUES
(1, 'BOV-001', 'Bella', 'Holstein', 'female', '2020-03-15', NULL, NULL, 520.00, 'active', 6, 'Vache laitière principale', NOW(), NOW()),
(2, 'BOV-002', 'Max', 'Holstein', 'male', '2019-08-20', NULL, NULL, 680.00, 'active', 6, 'Taureau reproducteur', NOW(), NOW()),
(3, 'BOV-003', 'Luna', 'Holstein', 'female', '2021-01-10', 'BOV-001', 'BOV-002', 380.00, 'active', 6, 'Génisse de 3 ans', NOW(), NOW()),
(4, 'BOV-004', 'Thunder', 'Holstein', 'male', '2022-05-25', 'BOV-001', 'BOV-002', 280.00, 'active', 6, 'Jeune taureau', NOW(), NOW()),
(5, 'BOV-005', 'Stella', 'Holstein', 'female', '2020-11-30', NULL, NULL, 450.00, 'active', 6, 'Vache laitière secondaire', NOW(), NOW()),
(6, 'BOV-006', 'Apollo', 'Holstein', 'male', '2023-02-14', 'BOV-005', 'BOV-002', 150.00, 'active', 6, 'Veau de l\'année', NOW(), NOW());

-- =====================================================
-- 8. CULTURES
-- =====================================================

INSERT INTO crops (id, name, variety, zone_id, planting_date, expected_harvest_date, actual_harvest_date, planted_area, expected_yield, actual_yield, status, notes, created_at, updated_at) VALUES
(1, 'Maïs', 'Hybride F1', 4, '2024-03-15', '2024-07-15', NULL, 2000.00, 8000.00, NULL, 'growing', 'Maïs en phase de croissance', NOW(), NOW()),
(2, 'Riz', 'NERICA 4', 5, '2024-04-01', '2024-08-01', NULL, 1500.00, 6000.00, NULL, 'growing', 'Riz irrigué en développement', NOW(), NOW()),
(3, 'Tomates', 'Roma', 4, '2024-02-20', '2024-06-20', '2024-06-25', 500.00, 2000.00, 1850.00, 'harvested', 'Tomates récoltées avec succès', NOW(), NOW()),
(4, 'Oignons', 'Violet de Galmi', 5, '2024-01-10', '2024-05-10', '2024-05-15', 300.00, 1200.00, 1100.00, 'harvested', 'Récolte d\'oignons terminée', NOW(), NOW()),
(5, 'Haricots', 'Niébé', 4, '2024-05-01', '2024-08-01', NULL, 800.00, 1600.00, NULL, 'planted', 'Haricots récemment plantés', NOW(), NOW());

-- =====================================================
-- 9. ENREGISTREMENTS VOLAILLES
-- =====================================================

INSERT INTO poultry_records (id, flock_id, record_date, eggs_collected, feed_consumed, mortality_count, average_weight, health_notes, observations, recorded_by, created_at, updated_at) VALUES
-- Enregistrements Lot 1 (Pondeuses Isa Brown) - Mai 2024
(1, 1, '2024-05-15', 450, 75.00, 0, NULL, 'Excellent état de santé', 'Production stable, bonne ponte', 2, NOW(), NOW()),
(2, 1, '2024-05-14', 445, 78.00, 2, NULL, 'Mortalité naturelle', '2 poules mortes naturellement', 2, NOW(), NOW()),
(3, 1, '2024-05-13', 460, 72.00, 0, NULL, 'Très bon état', 'Meilleure production de la semaine', 2, NOW(), NOW()),
(4, 1, '2024-05-12', 438, 76.00, 0, NULL, 'État normal', 'Production dans la moyenne', 2, NOW(), NOW()),
(5, 1, '2024-05-11', 442, 74.00, 1, NULL, 'Mortalité isolée', '1 poule morte, cause inconnue', 2, NOW(), NOW()),

-- Enregistrements Lot 2 (Poulets de chair Cobb 500) - Mai 2024
(6, 2, '2024-05-15', 0, 120.00, 0, 2.50, 'Croissance normale', 'Poids moyen excellent pour l\'âge', 3, NOW(), NOW()),
(7, 2, '2024-05-14', 0, 115.00, 1, 2.45, 'Mortalité faible', '1 poulet mort, croissance normale', 3, NOW(), NOW()),
(8, 2, '2024-05-13', 0, 118.00, 0, 2.40, 'Très bon état', 'Appétit excellent', 3, NOW(), NOW()),
(9, 2, '2024-05-12', 0, 122.00, 0, 2.35, 'État normal', 'Consommation d\'aliment stable', 3, NOW(), NOW()),
(10, 2, '2024-05-11', 0, 119.00, 0, 2.30, 'Bon développement', 'Croissance conforme aux standards', 3, NOW(), NOW()),

-- Enregistrements Lot 3 (Pondeuses Leghorn) - Mai 2024
(11, 3, '2024-05-15', 280, 45.00, 0, NULL, 'Excellent état', 'Production stable pour la race', 2, NOW(), NOW()),
(12, 3, '2024-05-14', 275, 47.00, 1, NULL, 'Mortalité naturelle', '1 poule morte, production maintenue', 2, NOW(), NOW()),
(13, 3, '2024-05-13', 285, 44.00, 0, NULL, 'Très bon état', 'Meilleure production du lot', 2, NOW(), NOW()),
(14, 3, '2024-05-12', 278, 46.00, 0, NULL, 'État normal', 'Production dans la moyenne', 2, NOW(), NOW()),
(15, 3, '2024-05-11', 272, 45.50, 0, NULL, 'Bon état général', 'Production stable', 2, NOW(), NOW()),

-- Enregistrements Lot 4 (Poulets de chair Ross 308) - Mai 2024
(16, 4, '2024-05-15', 0, 95.00, 0, 1.80, 'Croissance rapide', 'Poids excellent pour l\'âge', 3, NOW(), NOW()),
(17, 4, '2024-05-14', 0, 92.00, 0, 1.75, 'Très bon état', 'Appétit normal', 3, NOW(), NOW()),
(18, 4, '2024-05-13', 0, 98.00, 1, 1.70, 'Mortalité faible', '1 poulet mort, croissance normale', 3, NOW(), NOW()),
(19, 4, '2024-05-12', 0, 94.00, 0, 1.65, 'Bon développement', 'Croissance conforme', 3, NOW(), NOW()),
(20, 4, '2024-05-11', 0, 96.00, 0, 1.60, 'État normal', 'Développement standard', 3, NOW(), NOW()),

-- Enregistrements Lot 1 - Avril 2024 (historique)
(21, 1, '2024-04-30', 455, 73.00, 0, NULL, 'Fin de mois stable', 'Production maintenue', 2, NOW(), NOW()),
(22, 1, '2024-04-25', 448, 75.00, 1, NULL, 'Mortalité normale', '1 poule morte, production stable', 2, NOW(), NOW()),
(23, 1, '2024-04-20', 462, 71.00, 0, NULL, 'Excellent état', 'Pic de production', 2, NOW(), NOW()),

-- Enregistrements Lot 2 - Avril 2024 (historique)
(24, 2, '2024-04-30', 0, 110.00, 0, 2.20, 'Fin de mois', 'Poids conforme aux attentes', 3, NOW(), NOW()),
(25, 2, '2024-04-25', 0, 105.00, 0, 2.00, 'Croissance normale', 'Développement standard', 3, NOW(), NOW()),
(26, 2, '2024-04-20', 0, 100.00, 0, 1.80, 'Bon état général', 'Croissance régulière', 3, NOW(), NOW()),

-- Enregistrements Lot 3 - Avril 2024 (historique)
(27, 3, '2024-04-30', 285, 43.00, 0, NULL, 'Fin de mois', 'Production stable', 2, NOW(), NOW()),
(28, 3, '2024-04-25', 278, 45.00, 0, NULL, 'État normal', 'Production dans la moyenne', 2, NOW(), NOW()),
(29, 3, '2024-04-20', 290, 42.00, 0, NULL, 'Excellent état', 'Meilleure production du mois', 2, NOW(), NOW());

-- =====================================================
-- 10. ENREGISTREMENTS BOVINS
-- =====================================================

INSERT INTO cattle_records (id, cattle_id, record_type, value, unit, recorded_date, notes, created_at, updated_at) VALUES
(1, 1, 'milk_production', 25.5, 'liters', '2024-05-15', 'Production laitière du jour', NOW(), NOW()),
(2, 5, 'milk_production', 22.0, 'liters', '2024-05-15', 'Production laitière du jour', NOW(), NOW()),
(3, 1, 'weight', 520.0, 'kg', '2024-05-10', 'Pesée mensuelle', NOW(), NOW()),
(4, 2, 'weight', 680.0, 'kg', '2024-05-10', 'Pesée mensuelle', NOW(), NOW()),
(5, 3, 'weight', 380.0, 'kg', '2024-05-10', 'Pesée mensuelle', NOW(), NOW());

-- =====================================================
-- 11. ACTIVITÉS CULTURALES
-- =====================================================

INSERT INTO crop_activities (id, crop_id, activity_type, description, date_performed, cost, notes, created_at, updated_at) VALUES
(1, 1, 'fertilization', 'Application d\'engrais NPK', '2024-04-01', 150000.00, 'Fertilisation de base', NOW(), NOW()),
(2, 1, 'irrigation', 'Irrigation par aspersion', '2024-04-15', 25000.00, 'Irrigation d\'appoint', NOW(), NOW()),
(3, 2, 'planting', 'Plantation du riz', '2024-04-01', 80000.00, 'Plantation en ligne', NOW(), NOW()),
(4, 3, 'harvesting', 'Récolte des tomates', '2024-06-25', 120000.00, 'Récolte manuelle', NOW(), NOW()),
(5, 4, 'harvesting', 'Récolte des oignons', '2024-05-15', 90000.00, 'Récolte et séchage', NOW(), NOW());

-- =====================================================
-- 12. MOUVEMENTS DE STOCK
-- =====================================================

INSERT INTO stock_movements (id, stock_item_id, movement_type, quantity, unit_cost, total_cost, reason, reference, date, notes, created_at, updated_at) VALUES
(1, 1, 'in', 1000.00, 450.00, 450000.00, 'purchase', 'FACT-2024-001', '2024-04-01', 'Achat d\'aliment pondeuses', NOW(), NOW()),
(2, 1, 'out', 50.00, 450.00, 22500.00, 'consumption', 'CONS-2024-001', '2024-05-15', 'Consommation quotidienne', NOW(), NOW()),
(3, 2, 'in', 500.00, 380.00, 190000.00, 'purchase', 'FACT-2024-002', '2024-04-05', 'Achat d\'aliment poulets', NOW(), NOW()),
(4, 3, 'in', 2000.00, 120.00, 240000.00, 'purchase', 'FACT-2024-003', '2024-03-20', 'Achat de foin', NOW(), NOW()),
(5, 4, 'in', 200.00, 850.00, 170000.00, 'purchase', 'FACT-2024-004', '2024-03-15', 'Achat d\'engrais', NOW(), NOW());

-- =====================================================
-- 13. TÂCHES
-- =====================================================

INSERT INTO tasks (id, title, description, priority, status, assigned_to, due_date, completed_at, created_at, updated_at) VALUES
(1, 'Nettoyage Poulailler Principal', 'Nettoyer et désinfecter le poulailler principal', 'high', 'pending', 5, '2024-05-20', NULL, NOW(), NOW()),
(2, 'Vaccination Volailles', 'Vacciner les poulets contre Newcastle', 'high', 'in_progress', 4, '2024-05-18', NULL, NOW(), NOW()),
(3, 'Récolte Maïs', 'Préparer la récolte du maïs', 'medium', 'pending', 2, '2024-07-10', NULL, NOW(), NOW()),
(4, 'Maintenance Équipements', 'Vérifier et entretenir les équipements', 'low', 'completed', 3, '2024-05-10', '2024-05-10', NOW(), NOW()),
(5, 'Inventaire Stock', 'Faire l\'inventaire mensuel du stock', 'medium', 'pending', 2, '2024-05-25', NULL, NOW(), NOW());

-- =====================================================
-- 14. NOTIFICATIONS
-- =====================================================

INSERT INTO notifications (id, title, message, type, is_read, created_at, updated_at) VALUES
(1, 'Stock Bas - Aliment Poulets', 'Le stock d\'aliment pour poulets est en dessous du minimum', 'warning', 0, NOW(), NOW()),
(2, 'Vaccination Requise', 'Vaccination des volailles prévue demain', 'info', 0, NOW(), NOW()),
(3, 'Récolte Prête', 'Le maïs est prêt pour la récolte', 'success', 0, NOW(), NOW()),
(4, 'Maintenance Équipement', 'Le pulvérisateur nécessite une maintenance', 'warning', 1, NOW(), NOW()),
(5, 'Nouvelle Vente', 'Vente de 1000 œufs enregistrée', 'success', 1, NOW(), NOW());

-- =====================================================
-- 15. TRANSACTIONS FINANCIÈRES
-- =====================================================

INSERT INTO transactions (id, type, amount, description, category, date, reference, created_at, updated_at) VALUES
(1, 'income', 150000.00, 'Vente d\'œufs', 'sales', '2024-05-15', 'SALE-2024-001', NOW(), NOW()),
(2, 'expense', 450000.00, 'Achat d\'aliment pondeuses', 'feed', '2024-04-01', 'PUR-2024-001', NOW(), NOW()),
(3, 'income', 250000.00, 'Vente de lait', 'sales', '2024-05-15', 'SALE-2024-002', NOW(), NOW()),
(4, 'expense', 190000.00, 'Achat d\'aliment poulets', 'feed', '2024-04-05', 'PUR-2024-002', NOW(), NOW()),
(5, 'expense', 170000.00, 'Achat d\'engrais', 'inputs', '2024-03-15', 'PUR-2024-003', NOW(), NOW());

-- =====================================================
-- 16. TRAITEMENTS VÉTÉRINAIRES
-- =====================================================

INSERT INTO veterinary_treatments (id, animal_type, animal_id, treatment_type, medication, dosage, veterinarian, treatment_date, next_due_date, notes, created_at, updated_at) VALUES
(1, 'poultry', 1, 'vaccination', 'Vaccin Newcastle', '1 dose/animal', 'Dr. Paul N\'Guessan', '2024-05-01', '2024-11-01', 'Vaccination préventive', NOW(), NOW()),
(2, 'cattle', 1, 'deworming', 'Ivermectine', '1ml/50kg', 'Dr. Paul N\'Guessan', '2024-04-15', '2024-07-15', 'Déparasitage trimestriel', NOW(), NOW()),
(3, 'poultry', 2, 'treatment', 'Antibiotique', '0.5ml/animal', 'Dr. Paul N\'Guessan', '2024-05-10', NULL, 'Traitement infection respiratoire', NOW(), NOW()),
(4, 'cattle', 3, 'vaccination', 'Vaccin FMD', '2ml/animal', 'Dr. Paul N\'Guessan', '2024-03-20', '2024-09-20', 'Vaccination fièvre aphteuse', NOW(), NOW());

-- =====================================================
-- 17. CAPTURES DE DONNÉES SENSORS
-- =====================================================

INSERT INTO sensors (id, name, type, location, zone_id, status, created_at, updated_at) VALUES
(1, 'Capteur Température Poulailler 1', 'temperature', 'Poulailler Principal', 1, 'active', NOW(), NOW()),
(2, 'Capteur Humidité Poulailler 1', 'humidity', 'Poulailler Principal', 1, 'active', NOW(), NOW()),
(3, 'Capteur Température Pâturage', 'temperature', 'Pâturage Nord', 3, 'active', NOW(), NOW()),
(4, 'Capteur pH Sol Parcelle A', 'ph', 'Parcelle Maïs A', 4, 'active', NOW(), NOW());

INSERT INTO sensor_readings (id, sensor_id, value, unit, reading_date, created_at, updated_at) VALUES
(1, 1, 28.5, '°C', '2024-05-15 08:00:00', NOW(), NOW()),
(2, 2, 65.0, '%', '2024-05-15 08:00:00', NOW(), NOW()),
(3, 3, 32.0, '°C', '2024-05-15 08:00:00', NOW(), NOW()),
(4, 4, 6.8, 'pH', '2024-05-15 08:00:00', NOW(), NOW());

-- =====================================================
-- 18. DOCUMENTS
-- =====================================================

INSERT INTO documents (id, title, type, file_path, description, created_at, updated_at) VALUES
(1, 'Plan d\'Exploitation 2024', 'plan', '/documents/plan_exploitation_2024.pdf', 'Plan annuel d\'exploitation', NOW(), NOW()),
(2, 'Certificat Vétérinaire', 'certificate', '/documents/cert_vet_2024.pdf', 'Certificat sanitaire vétérinaire', NOW(), NOW()),
(3, 'Rapport Mensuel Mai', 'report', '/documents/rapport_mai_2024.pdf', 'Rapport mensuel de production', NOW(), NOW()),
(4, 'Contrat Fournisseur', 'contract', '/documents/contrat_agrofeed.pdf', 'Contrat avec AgroFeed', NOW(), NOW());

-- =====================================================
-- 19. LOGS D'AUDIT
-- =====================================================

INSERT INTO audit_logs (id, user_id, action, model_type, model_id, old_values, new_values, ip_address, user_agent, created_at) VALUES
(1, 1, 'created', 'App\\Models\\StockItem', 1, NULL, '{"name":"Aliment Pondeuses Premium"}', '192.168.1.100', 'Mozilla/5.0...', NOW()),
(2, 2, 'updated', 'App\\Models\\PoultryFlock', 1, '{"current_quantity":500}', '{"current_quantity":485}', '192.168.1.101', 'Mozilla/5.0...', NOW()),
(3, 3, 'created', 'App\\Models\\Crop', 1, NULL, '{"name":"Maïs"}', '192.168.1.102', 'Mozilla/5.0...', NOW()),
(4, 4, 'created', 'App\\Models\\VeterinaryTreatment', 1, NULL, '{"treatment_type":"vaccination"}', '192.168.1.103', 'Mozilla/5.0...', NOW());

-- =====================================================
-- 20. SAUVEGARDES
-- =====================================================

INSERT INTO backups (id, name, type, file_path, size, status, created_at, updated_at) VALUES
(1, 'Sauvegarde Quotidienne', 'daily', '/backups/gesfarm_2024-05-15.sql', 15728640, 'completed', NOW(), NOW()),
(2, 'Sauvegarde Hebdomadaire', 'weekly', '/backups/gesfarm_week_20_2024.sql', 52428800, 'completed', NOW(), NOW()),
(3, 'Sauvegarde Mensuelle', 'monthly', '/backups/gesfarm_2024-04.sql', 104857600, 'completed', NOW(), NOW());

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- FIN DU FICHIER DE DONNÉES DE TEST
-- =====================================================
-- 
-- RÉSUMÉ DES DONNÉES INSÉRÉES :
-- - 4 rôles et 10 permissions
-- - 5 utilisateurs avec attribution de rôles
-- - 7 catégories de stock
-- - 8 articles de stock
-- - 8 zones d'exploitation
-- - 5 lots de volailles
-- - 6 bovins
-- - 5 cultures
-- - 5 enregistrements volailles
-- - 5 enregistrements bovins
-- - 5 activités culturales
-- - 5 mouvements de stock
-- - 5 tâches
-- - 5 notifications
-- - 5 transactions financières
-- - 4 traitements vétérinaires
-- - 4 capteurs et lectures
-- - 4 documents
-- - 4 logs d'audit
-- - 3 sauvegardes
-- 
-- TOTAL : Plus de 100 enregistrements de données de test
-- =====================================================
