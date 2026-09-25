<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'La Capsule CDR'),
    'env' => env('APP_ENV', 'local'),
    'url' => env('APP_URL', 'http://localhost:8080'),
    'search_index' => STORAGE_PATH . '/index/ressources.index',
];
