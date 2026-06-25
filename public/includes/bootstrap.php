<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    $configPath = __DIR__ . '/config.example.php';
}
$config = require $configPath;
$GLOBALS['config'] = $config;

if (!$config['debug']) {
    ini_set('display_errors', '0');
    error_reporting(0);
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/rate-limit.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/cart.php';
require_once __DIR__ . '/orders.php';
