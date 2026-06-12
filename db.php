<?php

function env_value(string $key, $default = null)
{
    $value = getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return $value;
}

function parse_database_url(string $url): array
{
    $parts = parse_url($url);
    if ($parts === false) {
        return [];
    }

    return [
        'host' => $parts['host'] ?? null,
        'port' => isset($parts['port']) ? (int) $parts['port'] : null,
        'user' => $parts['user'] ?? null,
        'pass' => $parts['pass'] ?? null,
        'dbname' => isset($parts['path']) ? ltrim($parts['path'], '/') : null,
    ];
}

$databaseUrl = env_value('DATABASE_URL');
$databaseConfig = $databaseUrl ? parse_database_url($databaseUrl) : [];

$host = $databaseConfig['host'] ?? env_value('MYSQLHOST', env_value('DB_HOST'));
$user = $databaseConfig['user'] ?? env_value('MYSQLUSER', env_value('DB_USER'));
$pass = $databaseConfig['pass'] ?? env_value('MYSQLPASSWORD', env_value('DB_PASS', ''));
$dbname = $databaseConfig['dbname'] ?? env_value('MYSQLDATABASE', env_value('DB_NAME'));
$port = (int) ($databaseConfig['port'] ?? env_value('MYSQLPORT', env_value('DB_PORT', 3306)));

if (!$host || !$user || !$dbname) {
    http_response_code(500);
    die('Database configuration is missing. Please set the database environment variables.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = @new mysqli($host, $user, $pass, $dbname, $port ?: 3306);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    die('Database connection failed. Please check your Render environment variables and database host.');
}
