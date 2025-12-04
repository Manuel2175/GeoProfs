<?php

return [
    'paths' => ['*','api/*', 'sanctum/csrf-cookie', 'api/documentation', 'docs', 'api-docs.json'],

    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // your Swagger UI origin
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
