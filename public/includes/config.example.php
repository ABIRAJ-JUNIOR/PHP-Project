<?php
/**
 * Copy this file to config.php and fill in your credentials.
 * config.php is gitignored — never commit real secrets.
 */

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'baker_best',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'smtp' => [
        'host' => 'smtp.mailtrap.io',
        'port' => 2525,
        'user' => '',
        'pass' => '',
        'from_email' => 'orders@bakerbest.com',
        'from_name' => 'The Baker Best',
    ],
    'admin_email' => 'admin@bakerbest.com',
    'site_url' => 'http://localhost',
    'site_name' => 'The Baker Best',
    'rate_limit' => [
        'max_attempts' => 5,
        'window_seconds' => 3600,
    ],
    'debug' => true,
];
