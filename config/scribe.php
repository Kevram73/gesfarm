<?php

use Knuckles\Scribe\Extracting\Strategies;
return [
    'title' => 'GESFARM API Documentation',
    'description' => 'API de gestion d\'une ferme agropastorale avec un accent sur la gestion avicole',
    'base_url' => null,
    
    'routes' => [
        [
            'match' => [
                'prefixes' => ['api/*'],
                'domains' => ['*'],
            ],
            'include' => [],
            'exclude' => [],
            'apply' => [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'response_calls' => [
                    'only' => [],
                    'except' => [],
                    'config' => [
                        'app.debug' => false,
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                ],
            ],
        ],
    ],

    'type' => 'static',
    'static' => [
        'output_path' => 'public/docs',
    ],

    'auth' => [
        'enabled' => true,
        'default' => true,
        'in' => 'bearer',
        'name' => 'token',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{YOUR_AUTH_KEY}',
        'extra_info' => 'You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.',
    ],

    'intro_text' => <<<INTRO
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
INTRO,

    'example_languages' => [
        'bash',
        'javascript',
        'php',
    ],

    'postman' => [
        'enabled' => true,
    ],

    'openapi' => [
        'enabled' => true,
    ],

    'logo' => false,
    'fractal' => [
        'serializer' => null,
    ],
    'routeMatcher' => \Knuckles\Scribe\Matching\RouteMatcher::class,
    'database_connections_to_transact' => [config('database.default')],
    // See https://scribe.knuckles.wtf/laravel/reference/config#theme for supported options
    'theme' => 'default',
    'laravel' => [
        // Whether to automatically create a docs endpoint for you to view your generated docs.
        // If this is false, you can still set up routing manually.
        'add_routes' => true,
        // URL path to use for the docs endpoint (if `add_routes` is true).
        // By default, `/docs` opens the HTML page, `/docs.postman` opens the Postman collection, and `/docs.openapi` the OpenAPI spec.
        'docs_url' => '/docs',
        // Directory within `public` in which to store CSS and JS assets.
        // By default, assets are stored in `public/vendor/scribe`.
        // If set, assets will be stored in `public/{{assets_directory}}`
        'assets_directory' => null,
        // Middleware to attach to the docs endpoint (if `add_routes` is true).
        'middleware' => [],
    ],
    'external' => ['html_attributes' => []],
    'try_it_out' => [
        // Add a Try It Out button to your endpoints so consumers can test endpoints right from their browser.
        // Don't forget to enable CORS headers for your endpoints.
        'enabled' => true,
        // The base URL for the API tester to use (for example, you can set this to your staging URL).
        // Leave as null to use the current app URL when generating (config("app.url")).
        'base_url' => null,
        // [Laravel Sanctum] Fetch a CSRF token before each request, and add it as an X-XSRF-TOKEN header.
        'use_csrf' => false,
        // The URL to fetch the CSRF token from (if `use_csrf` is true).
        'csrf_url' => '/sanctum/csrf-cookie',
    ],
    // Customize the "Last updated" value displayed in the docs by specifying tokens and formats.
    // Examples:
    // - {date:F j Y} => March 28, 2022
    // - {git:short} => Short hash of the last Git commit
    // Available tokens are `{date:<format>}` and `{git:<format>}`.
    // The format you pass to `date` will be passed to PHP's `date()` function.
    // The format you pass to `git` can be either "short" or "long".
    'last_updated' => 'Last updated: {date:F j, Y}',
    'examples' => [
        // Set this to any number (e.g. 1234) to generate the same example values for parameters on each run,
        'faker_seed' => null,
        // With API resources and transformers, Scribe tries to generate example models to use in your API responses.
        // By default, Scribe will try the model's factory, and if that fails, try fetching the first from the database.
        // You can reorder or remove strategies here.
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],
];