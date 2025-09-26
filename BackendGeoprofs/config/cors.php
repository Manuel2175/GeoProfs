<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'api/documentation', 'docs', 'api-docs.json'],

    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://127.0.0.1:8000', 'http://localhost:5173/'], // your Swagger UI origin
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];

