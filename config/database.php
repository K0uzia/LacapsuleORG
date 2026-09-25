<?php

declare(strict_types=1);

$driver = env('DB_DRIVER', 'sqlite');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

if ($driver === 'sqlite') {
    if (getenv('HOME')) {
        $defaultSqlite = getenv('HOME') . '/.cache/capsule/database.sqlite';
    } else {
        $defaultSqlite = STORAGE_PATH . '/database.sqlite';
    }
    $database = env('DB_DATABASE', $defaultSqlite);
    $dir = dirname($database);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $sqliteOptions = $options + [
        PDO::ATTR_TIMEOUT => 5,
    ];

    return [
        'driver' => 'sqlite',
        'dsn' => 'sqlite:' . $database,
        'host' => null,
        'database' => $database,
        'username' => null,
        'password' => null,
        'options' => $sqliteOptions,
    ];
}

$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$database = env('DB_DATABASE', 'capsule');
$username = env('DB_USERNAME', 'capsule');
$password = env('DB_PASSWORD', 'capsule');

return [
    'driver' => 'mysql',
    'dsn' => sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database),
    'host' => $host,
    'database' => $database,
    'username' => $username,
    'password' => $password,
    'options' => $options,
];
