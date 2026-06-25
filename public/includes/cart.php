<?php

declare(strict_types=1);

function cart_get(): array
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    return $_SESSION['cart'];
}

function cart_count(): int
{
    $total = 0;
    foreach (cart_get() as $quantity) {
        $total += max(0, (int) $quantity);
    }

    return $total;
}

function cart_add(int $itemId, int $quantity): void
{
    if ($itemId <= 0) {
        return;
    }

    $cart = cart_get();
    $quantity = max(1, min(99, $quantity));
    $existing = (int) ($cart[$itemId] ?? 0);
    $cart[$itemId] = min(99, $existing + $quantity);
    $_SESSION['cart'] = $cart;
}

function cart_set_quantity(int $itemId, int $quantity): void
{
    $cart = cart_get();

    if ($quantity <= 0) {
        unset($cart[$itemId]);
    } else {
        $cart[$itemId] = max(1, min(99, $quantity));
    }

    $_SESSION['cart'] = $cart;
}

function cart_remove(int $itemId): void
{
    $cart = cart_get();
    unset($cart[$itemId]);
    $_SESSION['cart'] = $cart;
}

function cart_clear(): void
{
    $_SESSION['cart'] = [];
}

function cart_url(): string
{
    return page_url('cart.php');
}

function checkout_url(): string
{
    return page_url('checkout.php');
}

/**
 * @return list<array{id:int,name:string,category:string,price:float,quantity:int,line_total:float,image_path:string}>
 */
function cart_resolve(?PDO $pdo): array
{
    $cart = cart_get();

    if ($cart === [] || !$pdo) {
        return [];
    }

    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();

    $byId = [];
    foreach ($rows as $row) {
        $byId[(int) $row['id']] = $row;
    }

    $lines = [];
    $validCart = [];

    foreach ($cart as $id => $quantity) {
        $id = (int) $id;
        $quantity = max(1, min(99, (int) $quantity));

        if (!isset($byId[$id])) {
            continue;
        }

        $item = $byId[$id];
        $price = (float) $item['price'];

        $lines[] = [
            'id' => $id,
            'name' => $item['name'],
            'category' => $item['category'],
            'price' => $price,
            'quantity' => $quantity,
            'line_total' => $price * $quantity,
            'image_path' => $item['image_path'],
        ];
        $validCart[$id] = $quantity;
    }

    $_SESSION['cart'] = $validCart;

    return $lines;
}

function cart_total(array $lines): float
{
    $total = 0.0;

    foreach ($lines as $line) {
        $total += $line['line_total'];
    }

    return $total;
}

function format_order_lines(array $lines): string
{
    $parts = [];

    foreach ($lines as $line) {
        $parts[] = sprintf(
            '%d × %s @ %s each — %s',
            $line['quantity'],
            $line['name'],
            format_price($line['price']),
            format_price($line['line_total'])
        );
    }

    return implode("\n", $parts);
}
