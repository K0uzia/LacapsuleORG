<?php

declare(strict_types=1);

if (defined('ROOT_PATH')) {
    return;
}

define('ROOT_PATH', __DIR__);
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEW_PATH', ROOT_PATH . '/app/Views');
define('CONFIG_PATH', ROOT_PATH . '/config');

/**
 * Charge un fichier .env simple (KEY=VALUE).
 */
function load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\"'");
        if ($name === '') {
            continue;
        }
        $_ENV[$name] = $value;
        putenv($name . '=' . $value);
    }
}

function env(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string) $value;
}

load_env(ROOT_PATH . '/.env');

$appConfig = require CONFIG_PATH . '/app.php';
$dbConfig = require CONFIG_PATH . '/database.php';

// Constantes legacy (TNTSearch / anciens scripts)
define('DB_DRIVER', $dbConfig['driver']);
define('DBHOST', $dbConfig['host'] ?? '127.0.0.1');
define('DBUSER', $dbConfig['username'] ?? '');
define('DBPASS', $dbConfig['password'] ?? '');
define('DBNAME', $dbConfig['database'] ?? '');
define('DB_DSN', $dbConfig['dsn']);
define('APP_URL', $appConfig['url']);
define('SEARCH_INDEX', $appConfig['search_index']);

try {
    $db = new PDO(
        $dbConfig['dsn'],
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['options']
    );
    if ($dbConfig['driver'] === 'mysql') {
        $db->exec('SET NAMES "UTF8"');
    }
    if ($dbConfig['driver'] === 'sqlite') {
        $db->exec('PRAGMA foreign_keys = ON');
        $db->exec('PRAGMA busy_timeout = 5000');
    }
} catch (PDOException $e) {
    http_response_code(500);
    die('Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage()));
}

require_once ROOT_PATH . '/app/Helpers/functions.php';
require_once ROOT_PATH . '/app/Helpers/ressources.php';
require_once ROOT_PATH . '/app/Helpers/media.php';

if (is_file(ROOT_PATH . '/vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
}

/**
 * Inclut une vue depuis app/Views.
 */
function view(string $name): void
{
    $path = VIEW_PATH . '/' . ltrim($name, '/');
    if (!is_file($path)) {
        throw new RuntimeException('Vue introuvable : ' . $path);
    }
    require $path;
}
