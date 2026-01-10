<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Agentic_AI',
        'base_url' => null,
        'env' => 'prod',
        'timezone' => 'UTC',
        'cookie' => [
            'visitor_id' => 'visitor_id',
            'secure' => false,
            'http_only' => true,
            'same_site' => 'Lax',
            'days' => 365,
        ],
    ],
    'i18n' => [
        'default' => 'en',
        'supported' => ['en', 'zh', 'es', 'fr', 'ar', 'pt'],
    ],
    'theme' => [
        'default' => 'light',
        'supported' => ['light', 'dark'],
    ],
    'db' => [
        'driver' => 'sqlite',
        'database_path' => __DIR__ . '/../storage/app.sqlite',
        'migrations_path' => __DIR__ . '/../database/migrations',
    ],
];
