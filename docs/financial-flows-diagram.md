# 💰 Diagrammes des Flux Financiers - GESFARM

## Flux de Données Financières

```mermaid
graph TD
    subgraph "💰 Sources de Revenus"
        R1[🥚 Vente d'œufs]
        R2[🐄 Vente de lait]
        R3[🌾 Vente de cultures]
        R4[🐔 Vente de volailles]
        R5[🐄 Vente de bétail]
        R6[💼 Autres revenus]
    end
    
    subgraph "💸 Sources de Dépenses"
        D1[🌾 Achat d'aliments]
        D2[💊 Médicaments vétérinaires]
        D3[🔧 Équipements]
        D4[👷 Salaires]
        D5[⚡ Énergie]
        D6[🚰 Eau]
        D7[🏠 Maintenance]
        D8[📦 Intrants agricoles]
    end
    
    subgraph "📊 Traitement des Données"
        T1[📝 Saisie des Transactions]
        T2[✅ Validation]
        T3[📊 Classification]
        T4[💾 Stockage]
        T5[📈 Analytics]
    end
    
    subgraph "📋 Rapports et Analyses"
        A1[📊 Tableau de Bord]
        A2[📈 Tendances]
        A3[🎯 Prédictions]
        A4[💰 ROI par Activité]
        A5[📋 Rapports Mensuels]
    end
    
    %% Flux des revenus
    R1 --> T1
    R2 --> T1
    R3 --> T1
    R4 --> T1
    R5 --> T1
    R6 --> T1
    
    %% Flux des dépenses
    D1 --> T1
    D2 --> T1
    D3 --> T1
    D4 --> T1
    D5 --> T1
    D6 --> T1
    D7 --> T1
    D8 --> T1
    
    %% Traitement
    T1 --> T2
    T2 --> T3
    T3 --> T4
    T4 --> T5
    
    %% Analyses
    T5 --> A1
    T5 --> A2
    T5 --> A3
    T5 --> A4
    T5 --> A5
    
    %% Styles
    classDef revenue fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef expense fill:#ffebee,stroke:#c62828,stroke-width:2px
    classDef process fill:#e3f2fd,stroke:#1565c0,stroke-width:2px
    classDef analysis fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    
    class R1,R2,R3,R4,R5,R6 revenue
    class D1,D2,D3,D4,D5,D6,D7,D8 expense
    class T1,T2,T3,T4,T5 process
    class A1,A2,A3,A4,A5 analysis
```

---

## Processus de Gestion Budgétaire

```mermaid
graph TD
    subgraph "📋 Planification"
        P1[🎯 Définition des Objectifs]
        P2[💰 Allocation des Budgets]
        P3[📅 Planification Temporelle]
        P4[👥 Attribution des Responsabilités]
    end
    
    subgraph "📊 Suivi et Contrôle"
        S1[📈 Suivi des Dépenses]
        S2[⚠️ Alertes de Dépassement]
        S3[🔄 Ajustements]
        S4[📊 Reporting]
    end
    
    subgraph "📈 Analyse et Optimisation"
        O1[📊 Analyse des Écarts]
        O2[🎯 Identification des Optimisations]
        O3[💡 Recommandations]
        O4[🔄 Mise à Jour des Budgets]
    end
    
    P1 --> P2
    P2 --> P3
    P3 --> P4
    
    P4 --> S1
    S1 --> S2
    S2 --> S3
    S3 --> S4
    
    S4 --> O1
    O1 --> O2
    O2 --> O3
    O3 --> O4
    
    O4 --> P1
    
    %% Styles
    classDef planning fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef monitoring fill:#fff3e0,stroke:#ef6c00,stroke-width:2px
    classDef optimization fill:#e3f2fd,stroke:#1565c0,stroke-width:2px
    
    class P1,P2,P3,P4 planning
    class S1,S2,S3,S4 monitoring
    class O1,O2,O3,O4 optimization
```

---

## Flux de Validation des Transactions

```mermaid
sequenceDiagram
    participant U as 👤 Utilisateur
    participant S as 🖥️ Système
    participant V as ✅ Validateur
    participant DB as 🗄️ Base de Données
    participant N as 🔔 Notifications
    participant A as 📊 Analytics
    
    Note over U,A: Processus de Validation d'une Transaction
    
    U->>S: Saisir une transaction
    S->>S: Validation des données
    alt Données valides
        S->>DB: Enregistrer en attente
        S->>N: Notifier le validateur
        N->>V: Notification de validation
        
        V->>S: Consulter la transaction
        alt Transaction approuvée
            V->>S: Approuver la transaction
            S->>DB: Marquer comme validée
            S->>A: Mettre à jour les analytics
            S->>N: Confirmer à l'utilisateur
            N->>U: Notification d'approbation
        else Transaction rejetée
            V->>S: Rejeter la transaction
            S->>DB: Marquer comme rejetée
            S->>N: Notifier le rejet
            N->>U: Notification de rejet
        end
    else Données invalides
        S->>U: Retourner les erreurs
    end
```

---

## Matrice des Coûts par Activité

```mermaid
graph LR
    subgraph "🐔 Activité Avicole"
        A1[🌾 Aliments]
        A2[💊 Santé]
        A3[👷 Main d'œuvre]
        A4[⚡ Énergie]
        A5[🏠 Infrastructure]
    end
    
    subgraph "🐄 Activité Bovine"
        B1[🌾 Aliments]
        B2[💊 Santé]
        B3[👷 Main d'œuvre]
        B4[⚡ Énergie]
        B5[🏠 Infrastructure]
    end
    
    subgraph "🌾 Activité Agricole"
        C1[🌱 Semences]
        C2[🌿 Engrais]
        C3[👷 Main d'œuvre]
        C4[⚡ Énergie]
        C5[🏠 Infrastructure]
    end
    
    subgraph "💰 Analyse des Coûts"
        D1[📊 Coût Total par Activité]
        D2[📈 Évolution des Coûts]
        D3[🎯 Coût par Unité Produite]
        D4[💡 Optimisations Possibles]
    end
    
    A1 --> D1
    A2 --> D1
    A3 --> D1
    A4 --> D1
    A5 --> D1
    
    B1 --> D1
    B2 --> D1
    B3 --> D1
    B4 --> D1
    B5 --> D1
    
    C1 --> D1
    C2 --> D1
    C3 --> D1
    C4 --> D1
    C5 --> D1
    
    D1 --> D2
    D1 --> D3
    D1 --> D4
    
    %% Styles
    classDef poultry fill:#fff8e1,stroke:#f57f17,stroke-width:2px
    classDef cattle fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef crops fill:#e3f2fd,stroke:#1565c0,stroke-width:2px
    classDef analysis fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    
    class A1,A2,A3,A4,A5 poultry
    class B1,B2,B3,B4,B5 cattle
    class C1,C2,C3,C4,C5 crops
    class D1,D2,D3,D4 analysis
```

---

## Dashboard Financier Interactif

```mermaid
graph TD
    subgraph "📊 Dashboard Principal"
        D1[💰 Revenus du Mois]
        D2[💸 Dépenses du Mois]
        D3[📈 Bénéfice Net]
        D4[🎯 Objectifs Atteints]
    end
    
    subgraph "📈 Graphiques Dynamiques"
        G1[📊 Évolution Mensuelle]
        G2[🥧 Répartition par Catégorie]
        G3[📈 Tendances]
        G4[🎯 Prédictions]
    end
    
    subgraph "⚠️ Alertes et Notifications"
        N1[🚨 Budget Dépassé]
        N2[📅 Échéances Proches]
        N3[💰 Revenus Exceptionnels]
        N4[📉 Baisse de Performance]
    end
    
    subgraph "🔍 Analyses Détaillées"
        A1[📊 ROI par Activité]
        A2[💡 Optimisations]
        A3[📋 Rapports Personnalisés]
        A4[🎯 Recommandations]
    end
    
    D1 --> G1
    D2 --> G2
    D3 --> G3
    D4 --> G4
    
    G1 --> N1
    G2 --> N2
    G3 --> N3
    G4 --> N4
    
    N1 --> A1
    N2 --> A2
    N3 --> A3
    N4 --> A4
    
    %% Styles
    classDef dashboard fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef graphs fill:#e3f2fd,stroke:#1565c0,stroke-width:2px
    classDef alerts fill:#ffebee,stroke:#c62828,stroke-width:2px
    classDef analysis fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    
    class D1,D2,D3,D4 dashboard
    class G1,G2,G3,G4 graphs
    class N1,N2,N3,N4 alerts
    class A1,A2,A3,A4 analysis
```

---

## Intégration avec les Autres Modules

```mermaid
graph TB
    subgraph "💰 Module Financier"
        F1[📝 Transactions]
        F2[📊 Budgets]
        F3[📈 Analytics]
        F4[📋 Rapports]
    end
    
    subgraph "🐔 Module Avicole"
        A1[🥚 Production d'œufs]
        A2[🌾 Consommation d'aliments]
        A3[💊 Coûts vétérinaires]
    end
    
    subgraph "🐄 Module Bovin"
        B1[🥛 Production laitière]
        B2[🌾 Consommation d'aliments]
        B3[💊 Coûts vétérinaires]
    end
    
    subgraph "🌾 Module Agricole"
        C1[🌱 Coûts des semences]
        C2[🌿 Coûts des engrais]
        C3[👷 Main d'œuvre]
    end
    
    subgraph "📦 Module Stocks"
        S1[📦 Valeur des stocks]
        S2[📉 Dépenses d'achat]
        S3[📊 Rotation des stocks]
    end
    
    %% Intégrations
    A1 --> F1
    A2 --> F1
    A3 --> F1
    
    B1 --> F1
    B2 --> F1
    B3 --> F1
    
    C1 --> F1
    C2 --> F1
    C3 --> F1
    
    S1 --> F1
    S2 --> F1
    S3 --> F1
    
    F1 --> F2
    F1 --> F3
    F1 --> F4
    
    %% Styles
    classDef financial fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef poultry fill:#fff8e1,stroke:#f57f17,stroke-width:2px
    classDef cattle fill:#e3f2fd,stroke:#1565c0,stroke-width:2px
    classDef crops fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    classDef stock fill:#fff3e0,stroke:#ef6c00,stroke-width:2px
    
    class F1,F2,F3,F4 financial
    class A1,A2,A3 poultry
    class B1,B2,B3 cattle
    class C1,C2,C3 crops
    class S1,S2,S3 stock
```

Ces diagrammes montrent comment le module financier s'intègre avec tous les autres modules du système GESFARM, permettant une gestion financière complète et intégrée de l'exploitation agropastorale.

