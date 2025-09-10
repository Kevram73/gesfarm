<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Farm Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings specific to the farm management system
    |
    */

    'poultry' => [
        'types' => [
            'layer' => 'Poule pondeuse',
            'broiler' => 'Poulet de chair',
            'duck' => 'Canard',
            'turkey' => 'Dinde',
        ],
        
        'egg_sizes' => [
            'small' => 'Petit',
            'medium' => 'Moyen',
            'large' => 'Grand',
            'extra_large' => 'Très grand',
        ],
        
        'incubation_settings' => [
            'chicken' => [
                'days' => 21,
                'temperature' => 37.5,
                'humidity' => 55.0,
            ],
            'duck' => [
                'days' => 28,
                'temperature' => 37.5,
                'humidity' => 60.0,
            ],
            'turkey' => [
                'days' => 28,
                'temperature' => 37.5,
                'humidity' => 55.0,
            ],
        ],
    ],

    'cattle' => [
        'genders' => [
            'male' => 'Mâle',
            'female' => 'Femelle',
        ],
        
        'health_statuses' => [
            'healthy' => 'En bonne santé',
            'sick' => 'Malade',
            'treated' => 'En traitement',
        ],
    ],

    'crops' => [
        'statuses' => [
            'planted' => 'Planté',
            'growing' => 'En croissance',
            'harvested' => 'Récolté',
            'failed' => 'Échec',
        ],
        
        'activity_types' => [
            'planting' => 'Plantation',
            'fertilizing' => 'Fertilisation',
            'irrigation' => 'Irrigation',
            'pest_control' => 'Lutte contre les ravageurs',
            'harvesting' => 'Récolte',
        ],
    ],

    'zones' => [
        'types' => [
            'cultivation' => 'Culture',
            'pasture' => 'Pâturage',
            'enclosure' => 'Enclos',
            'building' => 'Bâtiment',
            'water_point' => 'Point d\'eau',
        ],
        
        'statuses' => [
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'maintenance' => 'Maintenance',
        ],
    ],

    'tasks' => [
        'types' => [
            'agricultural' => 'Agricole',
            'livestock' => 'Élevage',
            'maintenance' => 'Maintenance',
            'administrative' => 'Administratif',
        ],
        
        'priorities' => [
            'low' => 'Faible',
            'medium' => 'Moyen',
            'high' => 'Élevé',
            'urgent' => 'Urgent',
        ],
        
        'statuses' => [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
        ],
    ],

    'stock' => [
        'categories' => [
            'agricultural_inputs' => 'Intrants agricoles',
            'animal_feed' => 'Aliments pour bétail',
            'equipment' => 'Équipements',
            'veterinary_products' => 'Produits vétérinaires',
        ],
        
        'movement_types' => [
            'in' => 'Entrée',
            'out' => 'Sortie',
            'adjustment' => 'Ajustement',
        ],
    ],

    'units' => [
        'weight' => ['kg', 'g', 'tonne'],
        'volume' => ['litre', 'ml', 'm³'],
        'area' => ['m²', 'hectare', 'acre'],
        'count' => ['pièce', 'unité', 'lot'],
        'length' => ['m', 'cm', 'mm'],
    ],

    'alerts' => [
        'stock_low_threshold' => 10, // percentage
        'expiry_warning_days' => 30,
        'task_overdue_days' => 1,
    ],

    'reports' => [
        'default_date_range' => 30, // days
        'max_date_range' => 365, // days
    ],
];
