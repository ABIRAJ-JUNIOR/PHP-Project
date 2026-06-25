<?php

declare(strict_types=1);

function getDb(array $config): ?PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $db = $config['db'];
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $db['host'],
        $db['name'],
        $db['charset']
    );

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        if ($config['debug']) {
            error_log('Database connection failed: ' . $e->getMessage());
        }
        return null;
    }

    return $pdo;
}
