<?php declare(strict_types=1);

return [
    'paths' => [
        'api/*',
        'oauth/*',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        //
    ],

    'allowed_origins_patterns' => [
        //
    ],

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
    ],

    'exposed_headers' => [],
    'max_age' => 3600,
    'supports_credentials' => false,
];

