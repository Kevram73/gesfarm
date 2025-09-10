# GESFARM API - Documentation Complète

## Vue d'ensemble

L'API GESFARM est une solution complète de gestion d'exploitation agropastorale avec un accent particulier sur la gestion avicole. Elle offre des fonctionnalités avancées pour le suivi des stocks, des élevages, des cultures et la cartographie interactive.

## Fonctionnalités Principales

### 🔐 Authentification et Sécurité
- Authentification par token Bearer
- Gestion des rôles et permissions (RBAC)
- Sessions sécurisées avec Laravel Sanctum

### 📦 Gestion des Stocks
- Suivi des intrants agricoles (semences, engrais, pesticides)
- Gestion des aliments pour bétail
- Inventaire des équipements et pièces détachées
- Suivi des produits vétérinaires
- Alertes de stock bas et dates d'expiration
- Mouvements d'entrée/sortie avec historique

### 🐔 Gestion Avicole Avancée
- Gestion des lots de volailles (poules, dindes, canards)
- Suivi de la ponte et production d'œufs
- Gestion de l'incubation avec paramètres optimaux
- Suivi de la prophylaxie et santé
- Calcul automatique des taux de mortalité
- Gestion de l'alimentation par lot

### 🐄 Élevage Bovin
- Suivi individuel et collectif du troupeau
- Enregistrement de la production laitière
- Gestion de la généalogie
- Suivi sanitaire et vétérinaire
- Calcul des performances

### 🌾 Gestion des Cultures
- Suivi des parcelles et cultures
- Enregistrement des activités culturales
- Calcul des rendements
- Planification des semis et récoltes
- Gestion des intrants par culture

### 🗺️ Cartographie Interactive
- Gestion des zones (parcelles, enclos, bâtiments)
- Support GeoJSON pour la géolocalisation
- Visualisation spatiale des activités
- Statistiques par zone

### 💰 Gestion Financière
- Suivi des transactions (revenus, dépenses, transferts)
- Gestion des budgets par catégorie
- Rapports financiers détaillés
- Analyse des coûts de production
- Prédictions de rentabilité

### 🔔 Système de Notifications
- Alertes de stock bas et dates d'expiration
- Rappels de vaccination et soins vétérinaires
- Notifications de ponte et collecte d'œufs
- Alertes météo et conditions environnementales
- Notifications personnalisées par utilisateur

### 📈 Analytics Avancés
- Prédictions de production basées sur l'IA
- Analyse des tendances et performances
- ROI par activité et zone
- Comparaisons saisonnières
- Indicateurs de performance clés (KPIs)

### 🏥 Gestion Vétérinaire
- Planning des vaccinations et traitements
- Suivi des soins par animal
- Historique médical complet
- Rappels automatiques de soins
- Gestion des médicaments et dosages

### 📊 Tableau de Bord et KPIs
- Indicateurs de performance en temps réel
- Alertes automatiques
- Rapports de production
- Statistiques de mortalité et rendement

## Installation et Configuration

### Prérequis
- PHP 8.1+
- Laravel 10+
- MySQL/PostgreSQL
- Composer

### Installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd gesfarm
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
Modifiez le fichier `.env` avec vos paramètres de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gesfarm
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Exécution des migrations**
```bash
php artisan migrate
```

6. **Génération des données de test**
```bash
php artisan db:seed
```

7. **Génération de la documentation API**
```bash
php artisan scribe:generate
```

## Utilisation de l'API

### Authentification

Toutes les routes (sauf `/api/login`) nécessitent une authentification. Incluez le token dans l'en-tête `Authorization` :

```bash
Authorization: Bearer {YOUR_TOKEN}
```

#### Connexion
```bash
POST /api/login
Content-Type: application/json

{
    "email": "admin@gesfarm.com",
    "password": "password"
}
```

**Réponse :**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Administrateur",
            "email": "admin@gesfarm.com",
            "roles": ["admin"]
        },
        "token": "1|abc123..."
    }
}
```

### Gestion des Stocks

#### Lister les articles de stock
```bash
GET /api/stock/items
Authorization: Bearer {TOKEN}
```

#### Créer un article de stock
```bash
POST /api/stock/items
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "name": "Aliment pour poules pondeuses",
    "description": "Granulés 16% protéines",
    "sku": "ALIM-POULE-001",
    "category_id": 5,
    "unit": "kg",
    "current_quantity": 1000,
    "minimum_quantity": 100,
    "unit_cost": 0.45,
    "supplier": "Fournisseur ABC"
}
```

#### Enregistrer un mouvement de stock
```bash
POST /api/stock/movements
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "stock_item_id": 1,
    "type": "out",
    "quantity": 50,
    "reason": "Consommation lot poules #001",
    "notes": "Distribution quotidienne"
}
```

### Gestion Avicole

#### Créer un lot de volailles
```bash
POST /api/poultry/flocks
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "flock_number": "FLOCK-001",
    "type": "layer",
    "breed": "Rhode Island Red",
    "initial_quantity": 500,
    "arrival_date": "2024-01-15",
    "zone_id": 1,
    "notes": "Lot de pondeuses"
}
```

#### Enregistrer des données quotidiennes
```bash
POST /api/poultry/records
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "flock_id": 1,
    "record_date": "2024-01-20",
    "eggs_collected": 450,
    "feed_consumed": 75.5,
    "mortality_count": 2,
    "average_weight": 1.8,
    "health_notes": "Bon état général",
    "observations": "Ponte stable"
}
```

#### Gestion de l'incubation
```bash
POST /api/poultry/incubation
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "batch_number": "INC-001",
    "egg_type": "chicken",
    "breed": "Rhode Island Red",
    "egg_count": 100,
    "start_date": "2024-01-20",
    "incubation_days": 21,
    "temperature": 37.5,
    "humidity_percentage": 55.0,
    "egg_size": "large"
}
```

### Gestion Bovine

#### Créer un animal
```bash
POST /api/cattle
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "tag_number": "BOV-001",
    "name": "Belle",
    "breed": "Holstein",
    "gender": "female",
    "birth_date": "2020-03-15",
    "current_weight": 650,
    "zone_id": 2
}
```

#### Enregistrer des données de production
```bash
POST /api/cattle/records
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "cattle_id": 1,
    "record_date": "2024-01-20",
    "milk_production": 25.5,
    "weight": 655,
    "health_status": "healthy",
    "health_notes": "Excellent état",
    "feeding_notes": "Ration complète"
}
```

### Gestion des Cultures

#### Créer une culture
```bash
POST /api/crops
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "name": "Maïs",
    "variety": "Pioneer 3394",
    "zone_id": 3,
    "planting_date": "2024-03-01",
    "expected_harvest_date": "2024-08-15",
    "planted_area": 5000,
    "expected_yield": 15000
}
```

#### Enregistrer une activité
```bash
POST /api/crops/activities
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "crop_id": 1,
    "activity_type": "fertilizing",
    "activity_date": "2024-03-15",
    "description": "Application d'engrais NPK",
    "materials_used": [{"item_id": 2, "quantity": 100}],
    "cost": 250.00
}
```

### Cartographie

#### Créer une zone
```bash
POST /api/zones
Authorization: Bearer {TOKEN}
Content-Type: application/json

{
    "name": "Parcelle Nord",
    "description": "Parcelle principale de culture",
    "type": "cultivation",
    "coordinates": {
        "type": "Polygon",
        "coordinates": [[
            [2.3522, 48.8566],
            [2.3522, 48.8576],
            [2.3532, 48.8576],
            [2.3532, 48.8566],
            [2.3522, 48.8566]
        ]]
    },
    "area": 10000
}
```

### Tableau de Bord

#### Obtenir les KPIs
```bash
GET /api/dashboard
Authorization: Bearer {TOKEN}
```

#### Alertes de stock
```bash
GET /api/dashboard/stock-alerts
Authorization: Bearer {TOKEN}
```

## Rôles et Permissions

### Rôles Disponibles

1. **Admin** : Accès complet à toutes les fonctionnalités
2. **Vétérinaire** : Gestion des animaux et santé
3. **Gestionnaire** : Gestion des stocks, cultures et rapports
4. **Superviseur** : Supervision des opérations
5. **Ouvrier** : Saisie des données de terrain

### Permissions par Module

| Permission | Admin | Vétérinaire | Gestionnaire | Superviseur | Ouvrier |
|------------|-------|-------------|--------------|-------------|---------|
| Gestion utilisateurs | ✅ | ❌ | ❌ | ❌ | ❌ |
| Gestion stocks | ✅ | 👁️ | ✅ | ✅ | 👁️ |
| Gestion avicole | ✅ | ✅ | 👁️ | ✅ | 📝 |
| Gestion bovine | ✅ | ✅ | 👁️ | ✅ | 📝 |
| Gestion cultures | ✅ | ❌ | ✅ | ✅ | 📝 |
| Cartographie | ✅ | ❌ | ✅ | ✅ | 👁️ |
| Tableau de bord | ✅ | 👁️ | ✅ | 👁️ | ❌ |

Légende : ✅ Création/Modification, 👁️ Consultation, 📝 Saisie données

## Codes de Réponse

- **200** : Succès
- **201** : Création réussie
- **400** : Erreur de validation
- **401** : Non authentifié
- **403** : Accès refusé
- **404** : Ressource non trouvée
- **422** : Erreur de validation des données
- **500** : Erreur serveur

## Format des Réponses

Toutes les réponses suivent le format standard :

```json
{
    "status": "success|error",
    "message": "Message descriptif",
    "data": { ... },
    "errors": { ... } // En cas d'erreur
}
```

## Pagination

Les listes paginées incluent des métadonnées :

```json
{
    "status": "success",
    "data": {
        "items": [...],
        "pagination": {
            "current_page": 1,
            "last_page": 10,
            "per_page": 15,
            "total": 150
        }
    }
}
```

## Filtrage et Recherche

La plupart des endpoints supportent :
- **Filtrage** : `?filter[field]=value`
- **Tri** : `?sort=field` ou `?sort=-field` (descendant)
- **Recherche** : `?search=term`
- **Pagination** : `?page=1&per_page=20`

## Documentation Interactive

La documentation complète de l'API est disponible après génération avec Scribe :

```bash
php artisan scribe:generate
```

Accédez à la documentation à l'adresse : `http://your-domain/docs`

## 🆕 Nouveaux Modules API

### 💰 Gestion Financière

#### Transactions
- `GET /api/financial/transactions` - Liste des transactions
- `POST /api/financial/transactions` - Créer une transaction
- `GET /api/financial/transactions/{id}` - Détails d'une transaction
- `PUT /api/financial/transactions/{id}` - Modifier une transaction
- `DELETE /api/financial/transactions/{id}` - Supprimer une transaction

#### Budgets
- `GET /api/financial/budgets` - Liste des budgets
- `POST /api/financial/budgets` - Créer un budget

#### Rapports Financiers
- `GET /api/financial/reports` - Rapports financiers détaillés

### 🔔 Système de Notifications

- `GET /api/notifications` - Liste des notifications
- `GET /api/notifications/unread` - Notifications non lues
- `POST /api/notifications` - Créer une notification
- `PUT /api/notifications/{id}/read` - Marquer comme lue
- `PUT /api/notifications/mark-all-read` - Marquer toutes comme lues
- `DELETE /api/notifications/{id}` - Supprimer une notification
- `GET /api/notifications/stats` - Statistiques des notifications

### 📈 Analytics Avancés

- `GET /api/analytics/poultry` - Analytics avicoles
- `GET /api/analytics/cattle` - Analytics bovins
- `GET /api/analytics/crops` - Analytics cultures
- `GET /api/analytics/farm-overview` - Vue d'ensemble de la ferme

### 🏥 Gestion Vétérinaire

#### Traitements
- `GET /api/veterinary/treatments` - Liste des traitements
- `POST /api/veterinary/treatments` - Créer un traitement
- `GET /api/veterinary/treatments/{id}` - Détails d'un traitement
- `PUT /api/veterinary/treatments/{id}` - Modifier un traitement
- `DELETE /api/veterinary/treatments/{id}` - Supprimer un traitement

#### Planning et Rappels
- `GET /api/veterinary/schedule` - Planning des soins
- `GET /api/veterinary/reminders` - Rappels de soins
- `GET /api/veterinary/animal-history` - Historique médical d'un animal
- `GET /api/veterinary/stats` - Statistiques vétérinaires

## Exemples d'Utilisation des Nouveaux Modules

### Créer une Transaction Financière
```bash
curl -X POST http://localhost/api/financial/transactions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "expense",
    "category": "feed",
    "description": "Achat d'aliments pour volailles",
    "amount": 150000,
    "currency": "XOF",
    "transaction_date": "2024-01-15",
    "payment_method": "bank_transfer"
  }'
```

### Créer une Notification
```bash
curl -X POST http://localhost/api/notifications \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "stock_alert",
    "title": "Stock bas - Aliments volailles",
    "message": "Le stock d'aliments pour volailles est en dessous du seuil minimum",
    "priority": "high",
    "related_entity_type": "StockItem",
    "related_entity_id": 123
  }'
```

### Enregistrer un Traitement Vétérinaire
```bash
curl -X POST http://localhost/api/veterinary/treatments \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "treatment_type": "vaccination",
    "treatment_name": "Vaccination Newcastle",
    "description": "Vaccination préventive contre la maladie de Newcastle",
    "treatment_date": "2024-01-15",
    "animal_type": "poultry",
    "animal_id": 456,
    "veterinarian_name": "Dr. Kone",
    "cost": 25000,
    "next_treatment_date": "2024-02-15"
  }'
```

### Obtenir les Analytics Avicoles
```bash
curl -X GET "http://localhost/api/analytics/poultry?start_date=2024-01-01&end_date=2024-01-31" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Support et Contact

Pour toute question ou support technique, contactez l'équipe de développement.

---

**GESFARM API v2.0** - Solution complète de gestion d'exploitation agropastorale avec modules avancés  
**Dernière mise à jour** : Janvier 2024
