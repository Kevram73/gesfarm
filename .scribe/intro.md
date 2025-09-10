# Introduction

API de gestion d'une ferme agropastorale avec un accent sur la gestion avicole

<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>

Cette API vous permet de gérer tous les aspects de votre ferme agropastorale :

- **Gestion des stocks** : Suivi des intrants, aliments, équipements et produits vétérinaires
- **Élevage avicole** : Gestion des lots, suivi de la ponte, incubation et prophylaxie
- **Élevage bovin** : Suivi du troupeau, production laitière et santé animale
- **Gestion des cultures** : Suivi des parcelles, activités culturales et rendements
- **Cartographie** : Gestion des zones et visualisation spatiale
- **Tableau de bord** : KPIs et indicateurs de performance

## Authentification

Cette API utilise l'authentification par token Bearer. Incluez votre token dans l'en-tête `Authorization` de toutes vos requêtes :

```
Authorization: Bearer {YOUR_AUTH_KEY}
```

