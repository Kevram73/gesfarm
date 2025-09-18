# 🏗️ Diagrammes de Classes - GESFARM

## Vue d'Ensemble

Cette documentation présente les diagrammes de classes complets du système GESFARM, organisés par modules fonctionnels. Chaque diagramme montre les entités, leurs attributs, méthodes et relations.

---

## 1. 🔐 Module Authentification et Sécurité

```mermaid
classDiagram
    class User {
        +id: bigint
        +name: string
        +email: string
        +email_verified_at: timestamp
        +password: string
        +remember_token: string
        +created_at: timestamp
        +updated_at: timestamp
        +login()
        +logout()
        +hasRole(role)
        +hasPermission(permission)
        +assignRole(role)
        +removeRole(role)
    }
    
    class Role {
        +id: bigint
        +name: string
        +guard_name: string
        +created_at: timestamp
        +updated_at: timestamp
        +givePermissionTo(permission)
        +revokePermissionTo(permission)
        +hasPermissionTo(permission)
    }
    
    class Permission {
        +id: bigint
        +name: string
        +guard_name: string
        +created_at: timestamp
        +updated_at: timestamp
    }
    
    class AuditLog {
        +id: bigint
        +event: string
        +model_type: string
        +model_id: bigint
        +old_values: json
        +new_values: json
        +ip_address: string
        +user_agent: string
        +user_id: bigint
        +created_at: timestamp
        +logEvent()
        +getChanges()
    }
    
    User ||--o{ Role : has
    Role ||--o{ Permission : has
    User ||--o{ AuditLog : creates
    User }o--o{ Role : assigned
```

---

## 2. 📦 Module Gestion des Stocks

```mermaid
classDiagram
    class StockCategory {
        +id: bigint
        +name: string
        +description: text
        +created_at: timestamp
        +updated_at: timestamp
        +getItems()
        +getTotalValue()
    }
    
    class StockItem {
        +id: bigint
        +category_id: bigint
        +name: string
        +description: text
        +unit: string
        +current_quantity: decimal
        +minimum_quantity: decimal
        +unit_price: decimal
        +expiry_date: date
        +location: string
        +created_at: timestamp
        +updated_at: timestamp
        +isLowStock()
        +getTotalValue()
        +updateQuantity(amount)
    }
    
    class StockMovement {
        +id: bigint
        +item_id: bigint
        +movement_type: string
        +quantity: decimal
        +unit_price: decimal
        +total_value: decimal
        +reference: string
        +notes: text
        +movement_date: date
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +calculateTotal()
        +validateMovement()
    }
    
    StockCategory ||--o{ StockItem : contains
    StockItem ||--o{ StockMovement : has
    User ||--o{ StockMovement : creates
```

---

## 3. 🐔 Module Gestion Avicole

```mermaid
classDiagram
    class PoultryFlock {
        +id: bigint
        +name: string
        +breed: string
        +initial_count: integer
        +current_count: integer
        +age_weeks: integer
        +status: string
        +zone_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +getMortalityRate()
        +getProductionRate()
        +updateCount(newCount)
    }
    
    class PoultryRecord {
        +id: bigint
        +flock_id: bigint
        +record_date: date
        +eggs_collected: integer
        +feed_consumed: decimal
        +water_consumed: decimal
        +mortality_count: integer
        +laying_rate: decimal
        +weight_avg: decimal
        +health_status: string
        +notes: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +calculateLayingRate()
        +getFeedEfficiency()
    }
    
    class IncubationRecord {
        +id: bigint
        +flock_id: bigint
        +eggs_set: integer
        +incubation_date: date
        +expected_hatch_date: date
        +temperature: decimal
        +humidity: decimal
        +turning_frequency: integer
        +fertility_rate: decimal
        +hatch_rate: decimal
        +chicks_hatched: integer
        +status: string
        +notes: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +calculateHatchRate()
        +updateResults(hatched, fertile)
    }
    
    PoultryFlock ||--o{ PoultryRecord : has
    PoultryFlock ||--o{ IncubationRecord : has
    Zone ||--o{ PoultryFlock : contains
    User ||--o{ PoultryRecord : creates
    User ||--o{ IncubationRecord : creates
```

---

## 4. 🐄 Module Gestion Bovine

```mermaid
classDiagram
    class Cattle {
        +id: bigint
        +identification_number: string
        +name: string
        +breed: string
        +gender: string
        +birth_date: date
        +weight: decimal
        +status: string
        +zone_id: bigint
        +mother_id: bigint
        +father_id: bigint
        +purchase_date: date
        +purchase_price: decimal
        +notes: text
        +created_at: timestamp
        +updated_at: timestamp
        +getAge()
        +getProductionHistory()
        +updateStatus(status)
    }
    
    class CattleRecord {
        +id: bigint
        +cattle_id: bigint
        +record_date: date
        +milk_production: decimal
        +feed_consumed: decimal
        +water_consumed: decimal
        +weight: decimal
        +health_status: string
        +breeding_status: string
        +pregnancy_status: string
        +notes: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +getDailyProduction()
        +calculateFeedEfficiency()
    }
    
    Cattle ||--o{ CattleRecord : has
    Cattle ||--o{ Cattle : mother
    Cattle ||--o{ Cattle : father
    Zone ||--o{ Cattle : contains
    User ||--o{ CattleRecord : creates
```

---

## 5. 🌾 Module Gestion des Cultures

```mermaid
classDiagram
    class Crop {
        +id: bigint
        +name: string
        +crop_type: string
        +variety: string
        +planting_date: date
        +expected_harvest_date: date
        +actual_harvest_date: date
        +area_planted: decimal
        +expected_yield: decimal
        +actual_yield: decimal
        +status: string
        +zone_id: bigint
        +notes: text
        +created_at: timestamp
        +updated_at: timestamp
        +getGrowthPeriod()
        +calculateYield()
        +updateStatus(status)
    }
    
    class CropActivity {
        +id: bigint
        +crop_id: bigint
        +activity_type: string
        +activity_date: date
        +description: text
        +quantity_used: decimal
        +unit: string
        +cost: decimal
        +equipment_used: string
        +weather_conditions: string
        +notes: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +calculateCost()
        +getEfficiency()
    }
    
    Crop ||--o{ CropActivity : has
    Zone ||--o{ Crop : contains
    User ||--o{ CropActivity : creates
```

---

## 6. 🗺️ Module Cartographie

```mermaid
classDiagram
    class Zone {
        +id: bigint
        +name: string
        +type: string
        +area: decimal
        +coordinates: json
        +description: text
        +status: string
        +created_at: timestamp
        +updated_at: timestamp
        +getArea()
        +getCoordinates()
        +updateCoordinates(coords)
    }
    
    class Sensor {
        +id: bigint
        +name: string
        +type: string
        +model: string
        +serial_number: string
        +location: string
        +zone_id: bigint
        +configuration: json
        +status: string
        +last_reading_at: timestamp
        +last_reading: json
        +battery_level: decimal
        +notes: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +isOnline()
        +getLatestReading()
        +updateBatteryLevel(level)
    }
    
    class SensorReading {
        +id: bigint
        +sensor_id: bigint
        +value: decimal
        +unit: string
        +reading_time: timestamp
        +metadata: json
        +status: string
        +created_at: timestamp
        +updated_at: timestamp
        +isCritical()
        +getFormattedValue()
    }
    
    Zone ||--o{ Sensor : contains
    Sensor ||--o{ SensorReading : generates
    User ||--o{ Sensor : manages
```

---

## 7. 💰 Module Gestion Financière

```mermaid
classDiagram
    class Transaction {
        +id: bigint
        +type: string
        +category: string
        +description: string
        +amount: decimal
        +currency: string
        +transaction_date: date
        +payment_method: string
        +reference: string
        +notes: text
        +attachments: json
        +user_id: bigint
        +related_entity_id: bigint
        +related_entity_type: string
        +created_at: timestamp
        +updated_at: timestamp
        +isIncome()
        +isExpense()
        +getFormattedAmount()
    }
    
    class Budget {
        +id: bigint
        +name: string
        +description: text
        +category: string
        +allocated_amount: decimal
        +spent_amount: decimal
        +currency: string
        +start_date: date
        +end_date: date
        +status: string
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +getRemainingAmount()
        +getSpentPercentage()
        +isOverBudget()
    }
    
    class Customer {
        +id: bigint
        +name: string
        +email: string
        +phone: string
        +address: text
        +city: string
        +country: string
        +customer_type: string
        +tax_number: string
        +credit_limit: decimal
        +current_balance: decimal
        +status: string
        +notes: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +getTotalOrders()
        +getOutstandingBalance()
    }
    
    class SalesOrder {
        +id: bigint
        +order_number: string
        +customer_id: bigint
        +order_date: date
        +delivery_date: date
        +status: string
        +subtotal: decimal
        +tax_amount: decimal
        +discount_amount: decimal
        +total_amount: decimal
        +currency: string
        +payment_status: string
        +payment_method: string
        +notes: text
        +delivery_address: text
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +generateOrderNumber()
        +calculateTotal()
    }
    
    class SalesOrderItem {
        +id: bigint
        +sales_order_id: bigint
        +product_type: string
        +product_name: string
        +description: text
        +quantity: decimal
        +unit: string
        +unit_price: decimal
        +total_price: decimal
        +discount_percentage: decimal
        +notes: text
        +created_at: timestamp
        +updated_at: timestamp
        +calculateTotalPrice()
    }
    
    User ||--o{ Transaction : creates
    User ||--o{ Budget : creates
    User ||--o{ Customer : manages
    User ||--o{ SalesOrder : creates
    Customer ||--o{ SalesOrder : places
    SalesOrder ||--o{ SalesOrderItem : contains
```

---

## 8. 🔔 Module Notifications

```mermaid
classDiagram
    class Notification {
        +id: bigint
        +type: string
        +title: string
        +message: text
        +priority: string
        +status: string
        +data: json
        +read_at: timestamp
        +scheduled_at: timestamp
        +user_id: bigint
        +related_entity_id: bigint
        +related_entity_type: string
        +created_at: timestamp
        +updated_at: timestamp
        +markAsRead()
        +isScheduled()
        +getPriorityLevel()
    }
    
    User ||--o{ Notification : receives
    Notification }o--|| User : belongs_to
```

---

## 9. 🏥 Module Gestion Vétérinaire

```mermaid
classDiagram
    class VeterinaryTreatment {
        +id: bigint
        +treatment_type: string
        +treatment_name: string
        +description: text
        +treatment_date: date
        +treatment_time: time
        +animal_type: string
        +animal_id: bigint
        +animal_identifier: string
        +veterinarian_name: string
        +veterinarian_license: string
        +medications: json
        +dosages: json
        +cost: decimal
        +next_treatment_date: date
        +notes: text
        +attachments: json
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +isUpcoming()
        +getTotalCost()
        +scheduleNext()
    }
    
    User ||--o{ VeterinaryTreatment : creates
    VeterinaryTreatment }o--|| User : belongs_to
```

---

## 10. 📋 Module Gestion des Tâches

```mermaid
classDiagram
    class Task {
        +id: bigint
        +title: string
        +description: text
        +priority: string
        +status: string
        +due_date: date
        +completed_at: timestamp
        +assigned_to: bigint
        +created_by: bigint
        +related_entity_id: bigint
        +related_entity_type: string
        +notes: text
        +created_at: timestamp
        +updated_at: timestamp
        +isOverdue()
        +markAsCompleted()
        +assignTo(user)
    }
    
    User ||--o{ Task : creates
    User ||--o{ Task : assigned_to
    Task }o--|| User : belongs_to
```

---

## 11. 📄 Module Gestion des Documents

```mermaid
classDiagram
    class Document {
        +id: bigint
        +name: string
        +original_name: string
        +file_path: string
        +file_type: string
        +mime_type: string
        +file_size: bigint
        +category: string
        +description: text
        +tags: json
        +related_entity_id: bigint
        +related_entity_type: string
        +is_public: boolean
        +expiry_date: date
        +user_id: bigint
        +created_at: timestamp
        +updated_at: timestamp
        +getFormattedFileSize()
        +isExpired()
        +getDownloadUrl()
    }
    
    User ||--o{ Document : uploads
    Document }o--|| User : belongs_to
```

---

## 12. 🔄 Relations Inter-Modules

```mermaid
classDiagram
    class User {
        +id: bigint
        +name: string
        +email: string
        +password: string
    }
    
    class Zone {
        +id: bigint
        +name: string
        +type: string
        +coordinates: json
    }
    
    class PoultryFlock {
        +id: bigint
        +name: string
        +zone_id: bigint
    }
    
    class Cattle {
        +id: bigint
        +identification_number: string
        +zone_id: bigint
    }
    
    class Crop {
        +id: bigint
        +name: string
        +zone_id: bigint
    }
    
    class Transaction {
        +id: bigint
        +amount: decimal
        +related_entity_id: bigint
        +related_entity_type: string
    }
    
    class Notification {
        +id: bigint
        +type: string
        +related_entity_id: bigint
        +related_entity_type: string
    }
    
    class Task {
        +id: bigint
        +title: string
        +related_entity_id: bigint
        +related_entity_type: string
    }
    
    class Document {
        +id: bigint
        +name: string
        +related_entity_id: bigint
        +related_entity_type: string
    }
    
    %% Relations principales
    User ||--o{ PoultryFlock : manages
    User ||--o{ Cattle : manages
    User ||--o{ Crop : manages
    User ||--o{ Transaction : creates
    User ||--o{ Notification : receives
    User ||--o{ Task : creates
    User ||--o{ Document : uploads
    
    Zone ||--o{ PoultryFlock : contains
    Zone ||--o{ Cattle : contains
    Zone ||--o{ Crop : contains
    
    %% Relations polymorphes
    Transaction }o--|| PoultryFlock : related_to
    Transaction }o--|| Cattle : related_to
    Transaction }o--|| Crop : related_to
    
    Notification }o--|| PoultryFlock : related_to
    Notification }o--|| Cattle : related_to
    Notification }o--|| Crop : related_to
    
    Task }o--|| PoultryFlock : related_to
    Task }o--|| Cattle : related_to
    Task }o--|| Crop : related_to
    
    Document }o--|| PoultryFlock : related_to
    Document }o--|| Cattle : related_to
    Document }o--|| Crop : related_to
```

---

## 13. 📊 Diagramme de Classes Complet - Vue d'Ensemble

```mermaid
classDiagram
    %% Classes principales
    class User {
        +id: bigint
        +name: string
        +email: string
        +password: string
        +created_at: timestamp
        +updated_at: timestamp
    }
    
    class Role {
        +id: bigint
        +name: string
        +guard_name: string
    }
    
    class Zone {
        +id: bigint
        +name: string
        +type: string
        +coordinates: json
    }
    
    %% Module Stocks
    class StockCategory {
        +id: bigint
        +name: string
        +description: text
    }
    
    class StockItem {
        +id: bigint
        +name: string
        +current_quantity: decimal
        +unit_price: decimal
    }
    
    %% Module Avicole
    class PoultryFlock {
        +id: bigint
        +name: string
        +breed: string
        +current_count: integer
    }
    
    class PoultryRecord {
        +id: bigint
        +eggs_collected: integer
        +feed_consumed: decimal
        +record_date: date
    }
    
    %% Module Bovin
    class Cattle {
        +id: bigint
        +identification_number: string
        +breed: string
        +weight: decimal
    }
    
    class CattleRecord {
        +id: bigint
        +milk_production: decimal
        +record_date: date
    }
    
    %% Module Cultures
    class Crop {
        +id: bigint
        +name: string
        +crop_type: string
        +expected_yield: decimal
    }
    
    %% Module Financier
    class Transaction {
        +id: bigint
        +type: string
        +amount: decimal
        +transaction_date: date
    }
    
    class Budget {
        +id: bigint
        +name: string
        +allocated_amount: decimal
        +spent_amount: decimal
    }
    
    %% Relations principales
    User }o--o{ Role : has
    Zone ||--o{ PoultryFlock : contains
    Zone ||--o{ Cattle : contains
    Zone ||--o{ Crop : contains
    
    StockCategory ||--o{ StockItem : contains
    PoultryFlock ||--o{ PoultryRecord : has
    Cattle ||--o{ CattleRecord : has
    
    User ||--o{ Transaction : creates
    User ||--o{ Budget : creates
    User ||--o{ PoultryRecord : creates
    User ||--o{ CattleRecord : creates
```

---

## Résumé des Classes

### Classes Principales (24 classes)
1. **User** - Utilisateurs du système
2. **Role** - Rôles des utilisateurs
3. **Permission** - Permissions du système
4. **AuditLog** - Logs d'audit

### Module Stocks (3 classes)
5. **StockCategory** - Catégories de stock
6. **StockItem** - Articles en stock
7. **StockMovement** - Mouvements de stock

### Module Avicole (3 classes)
8. **PoultryFlock** - Lots de volailles
9. **PoultryRecord** - Enregistrements avicoles
10. **IncubationRecord** - Enregistrements d'incubation

### Module Bovin (2 classes)
11. **Cattle** - Bétail
12. **CattleRecord** - Enregistrements bovins

### Module Cultures (2 classes)
13. **Crop** - Cultures
14. **CropActivity** - Activités culturales

### Module Cartographie (3 classes)
15. **Zone** - Zones géographiques
16. **Sensor** - Capteurs IoT
17. **SensorReading** - Lectures des capteurs

### Module Financier (5 classes)
18. **Transaction** - Transactions financières
19. **Budget** - Budgets
20. **Customer** - Clients
21. **SalesOrder** - Commandes de vente
22. **SalesOrderItem** - Articles de commande

### Modules Spécialisés (4 classes)
23. **Notification** - Notifications
24. **VeterinaryTreatment** - Traitements vétérinaires
25. **Task** - Tâches
26. **Document** - Documents

### Relations Clés
- **Relations 1:N** : User → (StockMovement, Transaction, etc.)
- **Relations N:N** : User ↔ Role
- **Relations polymorphes** : Transaction, Notification, Task, Document
- **Relations géographiques** : Zone → (PoultryFlock, Cattle, Crop)

Cette architecture de classes permet une gestion complète et intégrée de tous les aspects d'une exploitation agropastorale moderne.
