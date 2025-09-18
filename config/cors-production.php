<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration - Production
    |--------------------------------------------------------------------------
    |
    | Configuration CORS spécifique pour l'environnement de production
    | Remplacez les domaines par vos vrais domaines de production
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        // Remplacez par vos domaines de production
        'https://votre-domaine.com',
        'https://www.votre-domaine.com',
        'https://app.votre-domaine.com',
        'https://admin.votre-domaine.com',
    ],

    'allowed_origins_patterns' => [
        // Pattern pour les sous-domaines
        '/^https:\/\/.*\.votre-domaine\.com$/',
        // Pattern pour les environnements de staging
        '/^https:\/\/staging\.votre-domaine\.com$/',
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Origin',
        'Access-Control-Request-Method',
        'Access-Control-Request-Headers',
    ],

    'exposed_headers' => [
        'Cache-Control',
        'Content-Language',
        'Content-Type',
        'Expires',
        'Last-Modified',
        'Pragma',
    ],

    'max_age' => 86400, // 24 heures

    'supports_credentials' => true,

];
