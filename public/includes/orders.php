<?php

declare(strict_types=1);

function format_order_number(int $id): string
{
    return '#' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);
}

function track_order_url(): string
{
    return page_url('track-order.php');
}

function order_detail_url(int $id): string
{
    return page_url('order-details.php?id=' . $id);
}

function order_status_label(string $status): string
{
    return match ($status) {
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        default => 'Pending',
    };
}

function order_status_description(string $status): string
{
    return match ($status) {
        'confirmed' => 'Your order has been confirmed. We are preparing it for your pickup date.',
        'completed' => 'This order has been picked up. Thank you for visiting The Baker Best!',
        default => 'We received your order and will confirm pickup details by email or phone.',
    };
}

function order_status_badge_class(string $status): string
{
    return match ($status) {
        'confirmed' => 'order-status--confirmed',
        'completed' => 'order-status--completed',
        default => 'order-status--pending',
    };
}

function parse_order_items(?string $items): array
{
    if ($items === null || trim($items) === '') {
        return [];
    }

    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $items) ?: [])));
}

function grant_order_access(int $orderId): void
{
    if (!isset($_SESSION['verified_orders']) || !is_array($_SESSION['verified_orders'])) {
        $_SESSION['verified_orders'] = [];
    }

    $_SESSION['verified_orders'][$orderId] = time();
}

function can_view_order(int $orderId): bool
{
    $verified = $_SESSION['verified_orders'][$orderId] ?? 0;

    return is_int($verified) && $verified > 0 && (time() - $verified) < 1800;
}

function find_order_by_id_and_email(PDO $pdo, int $id, string $email): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND LOWER(email) = LOWER(?) LIMIT 1');
    $stmt->execute([$id, $email]);

    $order = $stmt->fetch();

    return $order ?: null;
}

function find_orders_by_email(PDO $pdo, string $email): array
{
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE LOWER(email) = LOWER(?) ORDER BY created_at DESC');
    $stmt->execute([$email]);

    return $stmt->fetchAll();
}

function format_order_datetime(?string $datetime): string
{
    if ($datetime === null || $datetime === '') {
        return '';
    }

    $timestamp = strtotime($datetime);

    return $timestamp ? date('M j, Y \a\t g:i A', $timestamp) : $datetime;
}

function format_pickup_date(?string $date): string
{
    if ($date === null || $date === '') {
        return '';
    }

    $timestamp = strtotime($date);

    return $timestamp ? date('l, M j, Y', $timestamp) : $date;
}
