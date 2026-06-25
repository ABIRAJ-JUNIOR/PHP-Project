<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function app_config(): array
{
    return $GLOBALS['config'] ?? [];
}

function base_url(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    $config = app_config();
    $configured = rtrim($config['site_url'] ?? '', '/');

    if (!($config['debug'] ?? false) && $configured !== '') {
        $base = $configured;
        return $base;
    }

    if (!empty($_SERVER['HTTP_HOST'])) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if (str_ends_with($dir, '/api')) {
            $dir = dirname($dir);
        }

        $base = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim($dir, '/');
        return $base;
    }

    $base = $configured !== '' ? $configured : 'http://localhost';
    return $base;
}

function asset(string $path): string
{
    return base_url() . '/assets/' . ltrim($path, '/');
}

function page_url(string $path = ''): string
{
    $base = base_url();
    if ($path === '') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function format_price(float $price): string
{
    return '$' . number_format($price, 2);
}

function parse_allergens(?string $allergens): array
{
    if (empty($allergens)) {
        return [];
    }
    return array_map('trim', explode(',', $allergens));
}

function menu_item_url(int $id): string
{
    return page_url('menu-item.php?id=' . $id);
}
