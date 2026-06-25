<?php

declare(strict_types=1);

function check_rate_limit(string $action, array $config): bool
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $maxAttempts = $config['rate_limit']['max_attempts'] ?? 5;
    $window = $config['rate_limit']['window_seconds'] ?? 3600;

    $storageDir = sys_get_temp_dir() . '/baker_best_rate_limit';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0700, true);
    }

    $key = hash('sha256', $action . '|' . $ip);
    $file = $storageDir . '/' . $key . '.json';
    $now = time();
    $data = ['attempts' => [], 'blocked_until' => 0];

    if (file_exists($file)) {
        $decoded = json_decode((string) file_get_contents($file), true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }

    if ($data['blocked_until'] > $now) {
        return false;
    }

    $data['attempts'] = array_values(array_filter(
        $data['attempts'],
        static fn(int $ts): bool => ($now - $ts) < $window
    ));

    if (count($data['attempts']) >= $maxAttempts) {
        $data['blocked_until'] = $now + $window;
        file_put_contents($file, json_encode($data), LOCK_EX);
        return false;
    }

    $data['attempts'][] = $now;
    file_put_contents($file, json_encode($data), LOCK_EX);
    return true;
}
