<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(page_url('menu.php'));
}

$fromCart = !empty($_POST['from_cart']);
$returnUrl = $fromCart ? checkout_url() : page_url('menu.php');

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('form_error', 'Invalid form submission. Please try again.');
    redirect($returnUrl);
}

if (!check_rate_limit('order', $config)) {
    flash('form_error', 'Too many submissions. Please try again later.');
    redirect($returnUrl);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$pickupDate = trim($_POST['pickup_date'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

if ($name === '' || strlen($name) > 100) {
    $errors[] = 'Please enter a valid name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($phone === '' || !preg_match('/^[\d\s\-+().]{7,30}$/', $phone)) {
    $errors[] = 'Please enter a valid phone number.';
}
if ($pickupDate === '' || strtotime($pickupDate) < strtotime('today')) {
    $errors[] = 'Please select a valid pickup date.';
}

$pdo = getDb($config);
$cartLines = [];
$orderTotal = 0.0;

if (!$fromCart) {
    $errors[] = 'Please add items to your cart before placing an order.';
} elseif (!$pdo) {
    $errors[] = 'Unable to process your order at this time. Please call us directly.';
} else {
    $cartLines = cart_resolve($pdo);
    if ($cartLines === []) {
        $errors[] = 'Your cart is empty. Please add items from the menu.';
    } else {
        $orderTotal = cart_total($cartLines);
    }
}

if ($errors) {
    $_SESSION['old_input'] = $_POST;
    flash('form_error', implode(' ', $errors));
    redirect($returnUrl);
}

$items = format_order_lines($cartLines);

try {
    $stmt = $pdo->prepare(
        'INSERT INTO orders (name, email, phone, items, pickup_date, message) VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $email, $phone, $items, $pickupDate, $message ?: null]);
    $orderId = (int) $pdo->lastInsertId();
} catch (PDOException $e) {
    if ($config['debug']) {
        error_log('Order insert failed: ' . $e->getMessage());
    }
    $_SESSION['old_input'] = $_POST;
    flash('form_error', 'Unable to save your order. Please try again or call us.');
    redirect($returnUrl);
}

cart_clear();

$html = '<h2>New Order Received</h2>';
$html .= '<p><strong>Name:</strong> ' . e($name) . '</p>';
$html .= '<p><strong>Email:</strong> ' . e($email) . '</p>';
$html .= '<p><strong>Phone:</strong> ' . e($phone) . '</p>';
$html .= '<p><strong>Order Total:</strong> ' . e(format_price($orderTotal)) . '</p>';
$html .= '<p><strong>Items:</strong><br>' . nl2br(e($items)) . '</p>';
$html .= '<p><strong>Pickup Date:</strong> ' . e($pickupDate) . '</p>';
if ($message) {
    $html .= '<p><strong>Message:</strong><br>' . nl2br(e($message)) . '</p>';
}

send_admin_email($config, 'New Order from ' . $name, $html, $email, $name);

flash('order_id', (string) $orderId);
flash('order_email', $email);

redirect(page_url('order-success.php'));
