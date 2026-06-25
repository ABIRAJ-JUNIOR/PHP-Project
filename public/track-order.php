<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Track Order';
$pageDescription = 'Look up your bakery order status and view order details using your order number and email.';
$currentPage = 'track-order';
$extraScripts = ['forms.js'];

$formError = flash('form_error');
$old = $_SESSION['old_track_input'] ?? [];
unset($_SESSION['old_track_input']);

$orders = [];
$lookupEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('form_error', 'Invalid form submission. Please try again.');
        redirect(track_order_url());
    }

    if (!check_rate_limit('track_order', $config)) {
        flash('form_error', 'Too many lookup attempts. Please try again later.');
        redirect(track_order_url());
    }

    $lookupEmail = trim($_POST['email'] ?? '');
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $_SESSION['old_track_input'] = $_POST;

    if (!filter_var($lookupEmail, FILTER_VALIDATE_EMAIL)) {
        flash('form_error', 'Please enter a valid email address.');
        redirect(track_order_url());
    }

    $pdo = getDb($config);
    if (!$pdo) {
        flash('form_error', 'Unable to look up orders right now. Please try again or call us.');
        redirect(track_order_url());
    }

    unset($_SESSION['old_track_input']);

    if ($orderId > 0) {
        $order = find_order_by_id_and_email($pdo, $orderId, $lookupEmail);
        if (!$order) {
            flash('form_error', 'No order found with that order number and email. Please check your details and try again.');
            $_SESSION['old_track_input'] = $_POST;
            redirect(track_order_url());
        }

        grant_order_access((int) $order['id']);
        redirect(order_detail_url((int) $order['id']));
    }

    $orders = find_orders_by_email($pdo, $lookupEmail);
    foreach ($orders as $order) {
        grant_order_access((int) $order['id']);
    }

    if ($orders === []) {
        flash('form_error', 'No orders were found for that email address.');
        $_SESSION['old_track_input'] = $_POST;
        redirect(track_order_url());
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="page-header page-header--compact">
    <div class="container">
        <h1>Track Your Order</h1>
        <p>Enter your email to see all orders, or use your order number for a specific order.</p>
    </div>
</div>

<section class="section section--compact">
    <div class="container track-order-layout">
        <div class="track-order-form-wrap panel">
            <h2>Find Your Order</h2>

            <?php if ($formError): ?>
            <div class="form-alert form-alert--error" role="alert"><?= e($formError) ?></div>
            <?php endif; ?>

            <form class="form" id="track-order-form" method="POST" action="<?= e(track_order_url()) ?>" novalidate>
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="track-email">Email Address <span class="required" aria-hidden="true">*</span></label>
                    <input type="email" id="track-email" name="email" required maxlength="255"
                           value="<?= e($old['email'] ?? $lookupEmail) ?>"
                           autocomplete="email" aria-required="true"
                           placeholder="The email used when ordering">
                    <div class="form-error" id="track-email-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label for="track-order-id">Order Number <span class="form-optional">(optional)</span></label>
                    <input type="number" id="track-order-id" name="order_id" min="1" step="1"
                           value="<?= e($old['order_id'] ?? '') ?>"
                           placeholder="e.g. 10001">
                    <p class="form-hint">Leave blank to see all orders linked to your email.</p>
                    <div class="form-error" id="track-order-id-error" role="alert"></div>
                </div>

                <button type="submit" class="btn btn--primary btn--lg" style="width: 100%;">Look Up Order</button>
            </form>
        </div>

        <?php if (!empty($orders)): ?>
        <div class="track-order-results">
            <h2>Your Orders</h2>
            <p class="track-order-results__email">Showing orders for <strong><?= e($lookupEmail) ?></strong></p>

            <div class="order-list">
                <?php foreach ($orders as $order): ?>
                <?php $status = (string) ($order['status'] ?? 'pending'); ?>
                <article class="order-list__item panel panel--flat">
                    <div class="order-list__header">
                        <div>
                            <h3><?= e(format_order_number((int) $order['id'])) ?></h3>
                            <p class="order-list__meta"><?= e(format_order_datetime($order['created_at'] ?? '')) ?></p>
                        </div>
                        <span class="order-status <?= e(order_status_badge_class($status)) ?>">
                            <?= e(order_status_label($status)) ?>
                        </span>
                    </div>

                    <p class="order-list__pickup">
                        Pickup: <strong><?= e(format_pickup_date($order['pickup_date'] ?? '')) ?></strong>
                    </p>

                    <ul class="order-list__items">
                        <?php foreach (array_slice(parse_order_items($order['items'] ?? ''), 0, 2) as $line): ?>
                        <li><?= e($line) ?></li>
                        <?php endforeach; ?>
                        <?php if (count(parse_order_items($order['items'] ?? '')) > 2): ?>
                        <li class="order-list__more">+ more items</li>
                        <?php endif; ?>
                    </ul>

                    <a href="<?= e(order_detail_url((int) $order['id'])) ?>" class="btn btn--ghost btn--sm">View Details</a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="track-order-help panel panel--flat">
            <h2>Where is my order number?</h2>
            <p>Your order number appears on the confirmation screen right after checkout (for example, <strong>#00123</strong>). You can also find it in the confirmation email we send you.</p>
            <ul class="track-order-help__list">
                <li><span class="order-status order-status--pending">Pending</span> — order received, awaiting confirmation</li>
                <li><span class="order-status order-status--confirmed">Confirmed</span> — we are preparing your order</li>
                <li><span class="order-status order-status--completed">Completed</span> — order picked up</li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
