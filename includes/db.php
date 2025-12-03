<?php

function cs_parse_database_url($url) {
    $parts = parse_url($url);
    if (!$parts) return [];
    return [
        'host' => $parts['host'] ?? null,
        'port' => isset($parts['port']) ? (string)$parts['port'] : null,
        'name' => isset($parts['path']) ? ltrim($parts['path'], '/') : null,
        'user' => $parts['user'] ?? null,
        'pass' => $parts['pass'] ?? null,
    ];
}

$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$cfg = [];
$databaseUrl = getenv('DATABASE_URL') ?: getenv('JAWSDB_URL') ?: getenv('CLEARDB_DATABASE_URL');
if ($databaseUrl) {
    $cfg = cs_parse_database_url($databaseUrl);
}

if (empty($cfg['host'])) $cfg['host'] = getenv('MYSQLHOST') ?: getenv('DB_HOST');
if (empty($cfg['port'])) $cfg['port'] = getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: '3306';
if (empty($cfg['name'])) $cfg['name'] = getenv('MYSQLDATABASE') ?: getenv('DB_NAME');
if (empty($cfg['user'])) $cfg['user'] = getenv('MYSQLUSER') ?: getenv('DB_USER');
if (empty($cfg['pass'])) $cfg['pass'] = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS');

if (empty($cfg['host'])) $cfg['host'] = '127.0.0.1';
if (empty($cfg['name'])) $cfg['name'] = 'computer_store';
if (empty($cfg['user'])) $cfg['user'] = 'root';
if (!isset($cfg['pass'])) $cfg['pass'] = '';
if (empty($cfg['port'])) $cfg['port'] = '3306';

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['name'], $charset);

try {
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
?>
