# 🎮 Diagrammes des Contrôleurs et Services - GESFARM

## 1. 🎮 Architecture des Contrôleurs

```mermaid
classDiagram
    class BaseController {
        <<abstract>>
        +validateRequest(data)
        +handleResponse(data)
        +handleError(error)
        +authorize(user, action)
    }
    
    class AuthController {
        +login(credentials)
        +logout()
        +profile()
        +refreshToken()
        +validateToken()
    }
    
    class StockController {
        +index()
        +store(request)
        +show(id)
        +update(id, request)
        +destroy(id)
        +recordMovement(request)
    }
    
    class PoultryController {
        +index()
        +store(request)
        +show(id)
        +update(id, request)
        +destroy(id)
        +recordData(request)
        +incubationRecords()
        +createIncubationRecord(request)
        +updateIncubationResults(id, request)
    }
    
    class CattleController {
        +index()
        +store(request)
        +show(id)
        +update(id, request)
        +destroy(id)
        +recordData(request)
    }
    
    class CropController {
        +index()
        +store(request)
        +show(id)
        +update(id, request)
        +destroy(id)
        +recordActivity(request)
        +activities(id)
    }
    
    class FinancialController {
        +getTransactions(request)
        +createTransaction(request)
        +getTransaction(id)
        +updateTransaction(id, request)
        +deleteTransaction(id)
        +getBudgets(request)
        +createBudget(request)
        +getFinancialReports(request)
    }
    
    class VeterinaryController {
        +getTreatments(request)
        +createTreatment(request)
        +getTreatment(id)
        +updateTreatment(id, request)
        +deleteTreatment(id)
        +getSchedule(request)
        +getReminders()
        +getAnimalHistory(request)
        +getVeterinaryStats(request)
    }
    
    class NotificationController {
        +getNotifications(request)
        +getUnreadNotifications()
        +markAsRead(id)
        +markAllAsRead()
        +createNotification(request)
        +deleteNotification(id)
        +getNotificationStats()
    }
    
    class AnalyticsController {
        +getPoultryAnalytics(request)
        +getCattleAnalytics(request)
        +getCropAnalytics(request)
        +getFarmOverview()
    }
    
    class DashboardController {
        +index()
        +stockAlerts()
        +poultryStats()
        +cattleStats()
        +cropStats()
        +taskStats()
    }
    
    BaseController <|-- AuthController
    BaseController <|-- StockController
    BaseController <|-- PoultryController
    BaseController <|-- CattleController
    BaseController <|-- CropController
    BaseController <|-- FinancialController
    BaseController <|-- VeterinaryController
    BaseController <|-- NotificationController
    BaseController <|-- AnalyticsController
    BaseController <|-- DashboardController
```

---

## 2. 🔧 Architecture des Services

```mermaid
classDiagram
    class ServiceInterface {
        <<interface>>
        +execute(data)
        +validate(data)
    }
    
    class PoultryService {
        +createFlock(data)
        +updateFlock(id, data)
        +recordProduction(data)
        +calculateMetrics(flockId)
        +getProductionHistory(flockId)
        +scheduleVaccination(flockId, data)
    }
    
    class CattleService {
        +createCattle(data)
        +updateCattle(id, data)
        +recordProduction(data)
        +calculateMetrics(cattleId)
        +getProductionHistory(cattleId)
        +scheduleTreatment(cattleId, data)
    }
    
    class CropService {
        +createCrop(data)
        +updateCrop(id, data)
        +recordActivity(data)
        +calculateYield(cropId)
        +getActivityHistory(cropId)
        +scheduleHarvest(cropId, data)
    }
    
    class FinancialService {
        +processTransaction(data)
        +createBudget(data)
        +updateBudget(id, data)
        +calculateROI(activityId)
        +generateFinancialReport(period)
        +validateTransaction(data)
    }
    
    class NotificationService {
        +sendNotification(data)
        +scheduleNotification(data)
        +markAsRead(id)
        +getUserNotifications(userId)
        +createAlert(data)
        +processScheduledNotifications()
    }
    
    class AnalyticsService {
        +calculatePoultryAnalytics(period)
        +calculateCattleAnalytics(period)
        +calculateCropAnalytics(period)
        +generateFarmOverview()
        +predictProduction(data)
        +optimizeResources(data)
    }
    
    class ValidationService {
        +validatePoultryData(data)
        +validateCattleData(data)
        +validateCropData(data)
        +validateFinancialData(data)
        +validateUserPermissions(user, action)
    }
    
    class ReportService {
        +generatePoultryReport(period)
        +generateCattleReport(period)
        +generateCropReport(period)
        +generateFinancialReport(period)
        +exportReport(type, format)
    }
    
    ServiceInterface <|-- PoultryService
    ServiceInterface <|-- CattleService
    ServiceInterface <|-- CropService
    ServiceInterface <|-- FinancialService
    ServiceInterface <|-- NotificationService
    ServiceInterface <|-- AnalyticsService
    ServiceInterface <|-- ValidationService
    ServiceInterface <|-- ReportService
```

---

## 3. 🔄 Relations Contrôleurs-Services

```mermaid
classDiagram
    class PoultryController {
        -poultryService: PoultryService
        -validationService: ValidationService
        -notificationService: NotificationService
        +store(request)
        +update(id, request)
        +recordData(request)
    }
    
    class PoultryService {
        -poultryRepository: PoultryRepository
        -analyticsService: AnalyticsService
        +createFlock(data)
        +updateFlock(id, data)
        +recordProduction(data)
    }
    
    class ValidationService {
        +validatePoultryData(data)
        +validatePermissions(user, action)
    }
    
    class NotificationService {
        +sendNotification(data)
        +createAlert(data)
    }
    
    class AnalyticsService {
        +calculatePoultryAnalytics(period)
        +predictProduction(data)
    }
    
    class PoultryRepository {
        +find(id)
        +create(data)
        +update(id, data)
        +findByZone(zoneId)
    }
    
    PoultryController --> PoultryService : uses
    PoultryController --> ValidationService : uses
    PoultryController --> NotificationService : uses
    
    PoultryService --> PoultryRepository : uses
    PoultryService --> AnalyticsService : uses
    
    PoultryService --> NotificationService : triggers
```

---

## 4. 🎯 Pattern Service Layer

```mermaid
classDiagram
    class ServiceLayer {
        <<interface>>
        +execute(data)
        +validate(data)
        +authorize(user, action)
        +audit(action, data)
    }
    
    class BusinessLogicService {
        +processBusinessRules(data)
        +calculateMetrics(data)
        +validateBusinessConstraints(data)
        +applyBusinessPolicies(data)
    }
    
    class DataAccessService {
        +create(data)
        +read(id)
        +update(id, data)
        +delete(id)
        +search(criteria)
    }
    
    class IntegrationService {
        +sendToExternalAPI(data)
        +receiveFromExternalAPI()
        +syncData()
        +handleWebhooks()
    }
    
    class CacheService {
        +get(key)
        +set(key, value)
        +delete(key)
        +clear()
        +invalidate(pattern)
    }
    
    class EventService {
        +publish(event)
        +subscribe(event, handler)
        +unsubscribe(event, handler)
        +handleEvent(event)
    }
    
    ServiceLayer <|-- BusinessLogicService
    ServiceLayer <|-- DataAccessService
    ServiceLayer <|-- IntegrationService
    ServiceLayer <|-- CacheService
    ServiceLayer <|-- EventService
```

---

## 5. 🔐 Architecture de Sécurité

```mermaid
classDiagram
    class SecurityManager {
        +authenticate(credentials)
        +authorize(user, resource, action)
        +validateToken(token)
        +refreshToken(token)
        +revokeToken(token)
    }
    
    class PermissionManager {
        +checkPermission(user, permission)
        +grantPermission(user, permission)
        +revokePermission(user, permission)
        +getUserPermissions(user)
    }
    
    class RoleManager {
        +assignRole(user, role)
        +removeRole(user, role)
        +getUserRoles(user)
        +getRolePermissions(role)
    }
    
    class AuditManager {
        +logAction(user, action, resource)
        +getAuditLog(user, period)
        +exportAuditLog(period)
        +analyzeAuditLog(period)
    }
    
    class EncryptionService {
        +encrypt(data)
        +decrypt(data)
        +hashPassword(password)
        +verifyPassword(password, hash)
    }
    
    SecurityManager --> PermissionManager
    SecurityManager --> RoleManager
    SecurityManager --> AuditManager
    SecurityManager --> EncryptionService
```

---

## 6. 📊 Architecture des Analytics

```mermaid
classDiagram
    class AnalyticsEngine {
        +processData(data)
        +calculateMetrics(data)
        +generateInsights(data)
        +predictTrends(data)
    }
    
    class DataProcessor {
        +cleanData(data)
        +transformData(data)
        +aggregateData(data)
        +validateData(data)
    }
    
    class MetricsCalculator {
        +calculateProductionMetrics(data)
        +calculateFinancialMetrics(data)
        +calculateHealthMetrics(data)
        +calculateEfficiencyMetrics(data)
    }
    
    class PredictionEngine {
        +predictProduction(data)
        +predictCosts(data)
        +predictHealth(data)
        +predictWeather(data)
    }
    
    class ReportGenerator {
        +generateChart(data)
        +generateTable(data)
        +generateDashboard(data)
        +exportReport(data, format)
    }
    
    AnalyticsEngine --> DataProcessor
    AnalyticsEngine --> MetricsCalculator
    AnalyticsEngine --> PredictionEngine
    AnalyticsEngine --> ReportGenerator
```

---

## 7. 🔔 Architecture des Notifications

```mermaid
classDiagram
    class NotificationManager {
        +sendNotification(data)
        +scheduleNotification(data)
        +processNotifications()
        +getNotificationHistory(user)
    }
    
    class AlertEngine {
        +checkStockAlerts()
        +checkHealthAlerts()
        +checkBudgetAlerts()
        +checkWeatherAlerts()
    }
    
    class NotificationChannel {
        <<interface>>
        +send(data)
        +validate(data)
    }
    
    class EmailChannel {
        +send(data)
        +validate(data)
        +formatEmail(data)
    }
    
    class SMSChannel {
        +send(data)
        +validate(data)
        +formatSMS(data)
    }
    
    class PushChannel {
        +send(data)
        +validate(data)
        +formatPush(data)
    }
    
    class WebhookChannel {
        +send(data)
        +validate(data)
        +formatWebhook(data)
    }
    
    NotificationManager --> AlertEngine
    NotificationManager --> NotificationChannel
    
    NotificationChannel <|-- EmailChannel
    NotificationChannel <|-- SMSChannel
    NotificationChannel <|-- PushChannel
    NotificationChannel <|-- WebhookChannel
```

---

## 8. 🗄️ Architecture des Repositories

```mermaid
classDiagram
    class RepositoryInterface {
        <<interface>>
        +find(id)
        +findAll()
        +create(data)
        +update(id, data)
        +delete(id)
        +search(criteria)
    }
    
    class BaseRepository {
        <<abstract>>
        +find(id)
        +findAll()
        +create(data)
        +update(id, data)
        +delete(id)
        +search(criteria)
        +validateData(data)
        +handleErrors(error)
    }
    
    class PoultryRepository {
        +findByZone(zoneId)
        +findByStatus(status)
        +findByBreed(breed)
        +getProductionStats(period)
        +getHealthStats(period)
    }
    
    class CattleRepository {
        +findByZone(zoneId)
        +findByBreed(breed)
        +findByStatus(status)
        +getMilkProductionStats(period)
        +getHealthStats(period)
    }
    
    class TransactionRepository {
        +findByType(type)
        +findByDateRange(start, end)
        +findByCategory(category)
        +getFinancialSummary(period)
        +getBudgetStatus(period)
    }
    
    class AnalyticsRepository {
        +getPoultryAnalytics(period)
        +getCattleAnalytics(period)
        +getCropAnalytics(period)
        +getFinancialAnalytics(period)
        +getFarmOverview()
    }
    
    RepositoryInterface <|-- BaseRepository
    BaseRepository <|-- PoultryRepository
    BaseRepository <|-- CattleRepository
    BaseRepository <|-- TransactionRepository
    BaseRepository <|-- AnalyticsRepository
```

---

## 9. 🔄 Architecture des Middlewares

```mermaid
classDiagram
    class MiddlewareInterface {
        <<interface>>
        +handle(request, next)
    }
    
    class AuthenticationMiddleware {
        +handle(request, next)
        +validateToken(token)
        +getUserFromToken(token)
    }
    
    class AuthorizationMiddleware {
        +handle(request, next)
        +checkPermissions(user, resource, action)
        +validateRole(user, requiredRole)
    }
    
    class ValidationMiddleware {
        +handle(request, next)
        +validateRequestData(data)
        +sanitizeInput(data)
    }
    
    class AuditMiddleware {
        +handle(request, next)
        +logRequest(request)
        +logResponse(response)
        +createAuditEntry(request, response)
    }
    
    class RateLimitMiddleware {
        +handle(request, next)
        +checkRateLimit(user, endpoint)
        +incrementCounter(user, endpoint)
    }
    
    class CorsMiddleware {
        +handle(request, next)
        +addCorsHeaders(response)
        +validateOrigin(origin)
    }
    
    MiddlewareInterface <|-- AuthenticationMiddleware
    MiddlewareInterface <|-- AuthorizationMiddleware
    MiddlewareInterface <|-- ValidationMiddleware
    MiddlewareInterface <|-- AuditMiddleware
    MiddlewareInterface <|-- RateLimitMiddleware
    MiddlewareInterface <|-- CorsMiddleware
```

---

## 10. 🎯 Architecture des Form Requests

```mermaid
classDiagram
    class FormRequest {
        <<abstract>>
        +authorize()
        +rules()
        +messages()
        +attributes()
        +prepareForValidation()
    }
    
    class CreatePoultryFlockRequest {
        +authorize()
        +rules()
        +messages()
        +attributes()
    }
    
    class UpdateCattleRequest {
        +authorize()
        +rules()
        +messages()
        +attributes()
    }
    
    class CreateTransactionRequest {
        +authorize()
        +rules()
        +messages()
        +attributes()
    }
    
    class CreateNotificationRequest {
        +authorize()
        +rules()
        +messages()
        +attributes()
    }
    
    class CreateVeterinaryTreatmentRequest {
        +authorize()
        +rules()
        +messages()
        +attributes()
    }
    
    FormRequest <|-- CreatePoultryFlockRequest
    FormRequest <|-- UpdateCattleRequest
    FormRequest <|-- CreateTransactionRequest
    FormRequest <|-- CreateNotificationRequest
    FormRequest <|-- CreateVeterinaryTreatmentRequest
```

---

## 11. 🔄 Flux de Données Complet

```mermaid
sequenceDiagram
    participant C as Controller
    participant V as ValidationService
    participant S as Service
    participant R as Repository
    participant N as NotificationService
    participant A as AnalyticsService
    participant DB as Database
    
    Note over C,DB: Flux de Création d'un Lot de Volailles
    
    C->>V: validateRequest(data)
    V->>C: validation result
    
    alt Validation successful
        C->>S: createFlock(data)
        S->>R: create(data)
        R->>DB: insert(data)
        DB->>R: success
        R->>S: flock created
        S->>N: sendNotification(flock created)
        S->>A: updateAnalytics(flock data)
        S->>C: flock data
        C->>C: handleResponse(flock data)
    else Validation failed
        C->>C: handleError(validation errors)
    end
```

---

## Résumé de l'Architecture

### 🎮 Contrôleurs (10)
- **BaseController** : Contrôleur de base avec fonctionnalités communes
- **AuthController** : Authentification et autorisation
- **StockController** : Gestion des stocks
- **PoultryController** : Gestion avicole
- **CattleController** : Gestion bovine
- **CropController** : Gestion des cultures
- **FinancialController** : Gestion financière
- **VeterinaryController** : Gestion vétérinaire
- **NotificationController** : Gestion des notifications
- **AnalyticsController** : Analytics et rapports
- **DashboardController** : Tableau de bord

### 🔧 Services (8)
- **PoultryService** : Logique métier avicole
- **CattleService** : Logique métier bovine
- **CropService** : Logique métier des cultures
- **FinancialService** : Logique métier financière
- **NotificationService** : Gestion des notifications
- **AnalyticsService** : Calculs et analytics
- **ValidationService** : Validation des données
- **ReportService** : Génération de rapports

### 🗄️ Repositories (4)
- **PoultryRepository** : Accès aux données avicoles
- **CattleRepository** : Accès aux données bovines
- **TransactionRepository** : Accès aux données financières
- **AnalyticsRepository** : Accès aux données d'analytics

### 🔐 Sécurité (4)
- **SecurityManager** : Gestion de la sécurité
- **PermissionManager** : Gestion des permissions
- **RoleManager** : Gestion des rôles
- **AuditManager** : Gestion de l'audit

Cette architecture respecte les principes SOLID et les patterns de conception modernes, offrant une base solide et extensible pour le système GESFARM.

