# 🏗️ Vue d'Ensemble du Système GESFARM

## Diagramme Global des Acteurs et Modules

```mermaid
graph TB
    %% Acteurs
    subgraph "👥 Acteurs du Système"
        A[👨‍💼 Administrateur]
        V[👨‍⚕️ Vétérinaire]
        G[👨‍💼 Gestionnaire]
        S[👨‍🌾 Superviseur]
        O[👷 Ouvrier]
    end
    
    %% Modules Principaux
    subgraph "📦 Modules Principaux"
        M1[🔐 Authentification & Sécurité]
        M2[📦 Gestion des Stocks]
        M3[🐔 Gestion Avicole]
        M4[🐄 Gestion Bovine]
        M5[🌾 Gestion des Cultures]
        M6[🗺️ Cartographie]
        M7[💰 Gestion Financière]
        M8[🔔 Notifications]
        M9[📈 Analytics]
        M10[🏥 Gestion Vétérinaire]
        M11[📋 Gestion des Tâches]
        M12[📊 Rapports & Dashboard]
    end
    
    %% Base de données
    subgraph "💾 Données"
        DB[(🗄️ Base de Données MySQL)]
        CACHE[(⚡ Cache Redis)]
        FILES[📁 Fichiers & Documents]
    end
    
    %% Services externes
    subgraph "🌐 Services Externes"
        EMAIL[📧 Service Email]
        SMS[📱 Service SMS]
        MAPS[🗺️ Services Cartographiques]
        WEATHER[🌤️ Données Météo]
    end
    
    %% Connexions Administrateur
    A --> M1
    A --> M7
    A --> M12
    A --> DB
    
    %% Connexions Vétérinaire
    V --> M10
    V --> M8
    V --> M3
    V --> M4
    V --> M12
    
    %% Connexions Gestionnaire
    G --> M7
    G --> M9
    G --> M12
    G --> M11
    G --> M2
    
    %% Connexions Superviseur
    S --> M11
    S --> M3
    S --> M4
    S --> M5
    S --> M8
    
    %% Connexions Ouvrier
    O --> M2
    O --> M3
    O --> M4
    O --> M5
    O --> M11
    O --> M8
    
    %% Interconnexions des modules
    M1 --> M2
    M1 --> M3
    M1 --> M4
    M1 --> M5
    M1 --> M6
    M1 --> M7
    M1 --> M8
    M1 --> M9
    M1 --> M10
    M1 --> M11
    M1 --> M12
    
    M2 --> M7
    M3 --> M7
    M3 --> M10
    M4 --> M7
    M4 --> M10
    M5 --> M7
    M6 --> M3
    M6 --> M4
    M6 --> M5
    M7 --> M9
    M8 --> M9
    M9 --> M12
    M10 --> M8
    M11 --> M8
    
    %% Connexions aux données
    M1 --> DB
    M2 --> DB
    M3 --> DB
    M4 --> DB
    M5 --> DB
    M6 --> DB
    M7 --> DB
    M8 --> DB
    M9 --> DB
    M10 --> DB
    M11 --> DB
    M12 --> DB
    
    M1 --> CACHE
    M8 --> CACHE
    M9 --> CACHE
    M12 --> CACHE
    
    M2 --> FILES
    M3 --> FILES
    M4 --> FILES
    M5 --> FILES
    M10 --> FILES
    
    %% Connexions aux services externes
    M8 --> EMAIL
    M8 --> SMS
    M6 --> MAPS
    M5 --> WEATHER
    M3 --> WEATHER
    M4 --> WEATHER
    
    %% Styles
    classDef actor fill:#e1f5fe,stroke:#01579b,stroke-width:2px
    classDef module fill:#f3e5f5,stroke:#4a148c,stroke-width:2px
    classDef data fill:#e8f5e8,stroke:#1b5e20,stroke-width:2px
    classDef external fill:#fff3e0,stroke:#e65100,stroke-width:2px
    
    class A,V,G,S,O actor
    class M1,M2,M3,M4,M5,M6,M7,M8,M9,M10,M11,M12 module
    class DB,CACHE,FILES data
    class EMAIL,SMS,MAPS,WEATHER external
```

---

## Flux de Données Principal

```mermaid
sequenceDiagram
    participant O as 👷 Ouvrier
    participant S as 👨‍🌾 Superviseur
    participant V as 👨‍⚕️ Vétérinaire
    participant G as 👨‍💼 Gestionnaire
    participant A as 👨‍💼 Administrateur
    participant SYS as 🖥️ Système GESFARM
    participant DB as 🗄️ Base de Données
    participant NOTIF as 🔔 Notifications
    
    Note over O,NOTIF: Flux de Production Avicole
    
    O->>SYS: Enregistrer la ponte quotidienne
    SYS->>DB: Sauvegarder les données
    SYS->>NOTIF: Vérifier les alertes
    NOTIF->>S: Notifier si anomalie détectée
    
    S->>SYS: Valider les données
    SYS->>DB: Marquer comme validé
    
    V->>SYS: Planifier une vaccination
    SYS->>DB: Enregistrer le planning
    SYS->>NOTIF: Programmer les rappels
    NOTIF->>V: Rappel de vaccination
    
    G->>SYS: Consulter les analytics
    SYS->>DB: Récupérer les données
    SYS->>G: Retourner les analyses
    
    A->>SYS: Configurer les paramètres
    SYS->>DB: Sauvegarder la configuration
    SYS->>NOTIF: Mettre à jour les alertes
```

---

## Matrice des Permissions

| Module | Administrateur | Gestionnaire | Vétérinaire | Superviseur | Ouvrier |
|--------|---------------|--------------|-------------|-------------|---------|
| **Authentification** | ✅ CRUD | 👁️ R | 👁️ R | 👁️ R | 👁️ R |
| **Stocks** | ✅ CRUD | ✅ CRUD | 👁️ R | ✅ CRUD | ✅ CRUD |
| **Avicole** | ✅ CRUD | ✅ CRUD | ✅ CRUD | ✅ CRUD | ✅ CRUD |
| **Bovine** | ✅ CRUD | ✅ CRUD | ✅ CRUD | ✅ CRUD | ✅ CRUD |
| **Cultures** | ✅ CRUD | ✅ CRUD | 👁️ R | ✅ CRUD | ✅ CRUD |
| **Cartographie** | ✅ CRUD | ✅ CRUD | 👁️ R | ✅ CRUD | 👁️ R |
| **Financier** | ✅ CRUD | ✅ CRUD | 👁️ R | 👁️ R | 👁️ R |
| **Notifications** | ✅ CRUD | ✅ CRUD | ✅ CRUD | ✅ CRUD | ✅ CRUD |
| **Analytics** | ✅ CRUD | ✅ CRUD | 👁️ R | 👁️ R | 👁️ R |
| **Vétérinaire** | ✅ CRUD | 👁️ R | ✅ CRUD | 👁️ R | 👁️ R |
| **Tâches** | ✅ CRUD | ✅ CRUD | 👁️ R | ✅ CRUD | ✅ CRUD |
| **Rapports** | ✅ CRUD | ✅ CRUD | ✅ CRUD | 👁️ R | 👁️ R |

**Légende :**
- ✅ CRUD : Créer, Lire, Modifier, Supprimer
- 👁️ R : Lecture seule

---

## Points d'Intégration Critiques

```mermaid
graph LR
    subgraph "🔄 Intégrations Critiques"
        I1[📊 Analytics ↔ Finances]
        I2[🔔 Notifications ↔ Tous modules]
        I3[🗺️ Cartographie ↔ Production]
        I4[🏥 Vétérinaire ↔ Santé Animale]
        I5[📦 Stocks ↔ Production]
    end
    
    I1 --> I1a[ROI par activité]
    I1 --> I1b[Prédictions financières]
    
    I2 --> I2a[Alertes automatiques]
    I2 --> I2b[Rappels programmés]
    
    I3 --> I3a[Optimisation spatiale]
    I3 --> I3b[Planification des zones]
    
    I4 --> I4a[Suivi sanitaire]
    I4 --> I4b[Prévention des maladies]
    
    I5 --> I5a[Gestion des intrants]
    I5 --> I5b[Optimisation des coûts]
```

---

## Architecture Technique

```mermaid
graph TB
    subgraph "🌐 Frontend"
        WEB[🌍 Interface Web]
        MOBILE[📱 Application Mobile]
        API_DOCS[📚 Documentation API]
    end
    
    subgraph "⚙️ Backend"
        API[🔌 API Laravel]
        AUTH[🔐 Authentification]
        MIDDLEWARE[🛡️ Middleware]
        CONTROLLERS[🎮 Controllers]
        MODELS[📊 Models]
    end
    
    subgraph "💾 Persistance"
        MYSQL[(🗄️ MySQL)]
        REDIS[(⚡ Redis)]
        FILES[📁 Storage]
    end
    
    subgraph "🐳 Infrastructure"
        NGINX[🌐 Nginx]
        PHP[🐘 PHP-FPM]
        DOCKER[🐳 Docker]
    end
    
    WEB --> API
    MOBILE --> API
    API_DOCS --> API
    
    API --> AUTH
    AUTH --> MIDDLEWARE
    MIDDLEWARE --> CONTROLLERS
    CONTROLLERS --> MODELS
    
    MODELS --> MYSQL
    MODELS --> REDIS
    MODELS --> FILES
    
    NGINX --> PHP
    PHP --> API
    DOCKER --> NGINX
    DOCKER --> PHP
    DOCKER --> MYSQL
    DOCKER --> REDIS
```

Cette architecture montre comment les différents acteurs interagissent avec le système GESFARM, avec des permissions et des responsabilités bien définies pour chaque rôle.
