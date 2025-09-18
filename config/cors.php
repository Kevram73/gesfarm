<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:3001',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:3001',
        'http://62.171.181.213:3000',
        'https://62.171.181.213:3000',
        'http://62.171.181.213',
        'https://62.171.181.213',
        // Ajoutez votre domaine de production ici
        // 'https://votre-domaine.com',
        // 'https://www.votre-domaine.com',
    ],

    'allowed_origins_patterns' => [
        '/^https?:\/\/localhost:\d+$/',
        '/^https?:\/\/127\.0\.0\.1:\d+$/',
        '/^https?:\/\/62\.171\.181\.213(:\d+)?$/',
        // Pattern pour les sous-domaines de production
        // '/^https?:\/\/.*\.votre-domaine\.com$/',
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
