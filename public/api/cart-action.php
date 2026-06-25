<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(page_url('menu.php'));
}

$action = trim($_POST['action'] ?? 'add');
$returnUrl = trim($_POST['return_url'] ?? '');

if ($returnUrl === '' || !str_starts_with($returnUrl, base_url())) {
    $returnUrl = cart_url();
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('form_error', 'Invalid form submission. Please try again.');
    redirect($returnUrl);
}

if (isset($_POST['remove_item_id'])) {
    cart_remove((int) $_POST['remove_item_id']);
    flash('cart_success', 'Item removed from your cart.');
    redirect(cart_url());
}

$pdo = getDb($config);

switch ($action) {
    case 'add':
        $menuItemId = (int) ($_POST['menu_item_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if ($menuItemId <= 0 || $quantity < 1 || $quantity > 99) {
            flash('form_error', 'Please select a valid quantity.');
            redirect($returnUrl);
        }

        if (!$pdo) {
            flash('form_error', 'Unable to add item to cart right now. Please try again.');
            redirect($returnUrl);
        }

        $stmt = $pdo->prepare('SELECT id FROM menu_items WHERE id = ? LIMIT 1');
        $stmt->execute([$menuItemId]);
        if (!$stmt->fetch()) {
            flash('form_error', 'That menu item is no longer available.');
            redirect($returnUrl);
        }

        cart_add($menuItemId, $quantity);
        flash('cart_success', 'Item added to your cart.');
        redirect($returnUrl);
        break;

    case 'update':
        $quantities = $_POST['quantities'] ?? [];

        if (!is_array($quantities)) {
            flash('form_error', 'Unable to update your cart.');
            redirect(cart_url());
        }

        foreach ($quantities as $itemId => $quantity) {
            cart_set_quantity((int) $itemId, (int) $quantity);
        }

        flash('cart_success', 'Cart updated.');
        redirect(cart_url());
        break;

    case 'remove':
        $menuItemId = (int) ($_POST['menu_item_id'] ?? 0);
        cart_remove($menuItemId);
        flash('cart_success', 'Item removed from your cart.');
        redirect(cart_url());
        break;

    default:
        redirect(cart_url());
}
