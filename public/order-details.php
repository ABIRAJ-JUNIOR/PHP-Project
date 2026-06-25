<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orderId = (int) ($_GET['id'] ?? 0);
$order = null;
$pdo = getDb($config);

if ($orderId > 0 && $pdo) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch() ?: null;
}

if (!$order || !can_view_order($orderId)) {
    if ($order && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('form_error', 'Invalid form submission. Please try again.');
            redirect(order_detail_url($orderId));
        }

        if (!check_rate_limit('track_order', $config)) {
            flash('form_error', 'Too many lookup attempts. Please try again later.');
            redirect(order_detail_url($orderId));
        }

        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('form_error', 'Please enter a valid email address.');
            redirect(order_detail_url($orderId));
        }

        $verified = find_order_by_id_and_email($pdo, $orderId, $email);
        if (!$verified) {
            flash('form_error', 'That email does not match this order.');
            redirect(order_detail_url($orderId));
        }

        grant_order_access($orderId);
        redirect(order_detail_url($orderId));
    }

    $pageTitle = $order ? 'Verify Order Access' : 'Order Not Found';
    $pageDescription = 'Verify your email to view order details.';
    $currentPage = 'track-order';
    $extraScripts = ['forms.js'];
    $formError = flash('form_error');

    require __DIR__ . '/includes/header.php';
    ?>
    <div class="page-header page-header--compact">
        <div class="container">
            <h1><?= $order ? 'Verify Your Email' : 'Order Not Found' ?></h1>
            <p><?= $order ? 'Enter the email used for order ' . e(format_order_number($orderId)) . ' to view details.' : 'We could not find that order.' ?></p>
        </div>
    </div>
    <section class="section section--compact">
        <div class="container">
            <?php if ($order): ?>
            <?php if ($formError): ?>
            <div class="form-alert form-alert--error" role="alert"><?= e($formError) ?></div>
            <?php endif; ?>
            <div class="panel track-order-form-wrap" style="max-width: 480px; margin: 0 auto;">
                <form class="form" id="track-order-form" method="POST" action="<?= e(order_detail_url($orderId)) ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="track-email">Email Address <span class="required" aria-hidden="true">*</span></label>
                        <input type="email" id="track-email" name="email" required maxlength="255" autocomplete="email" aria-required="true">
                        <div class="form-error" id="track-email-error" role="alert"></div>
                    </div>
                    <button type="submit" class="btn btn--primary" style="width: 100%;">View Order</button>
                </form>
            </div>
            <?php else: ?>
            <div class="text-center">
                <a href="<?= e(track_order_url()) ?>" class="btn btn--primary">Track an Order</a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$status = (string) ($order['status'] ?? 'pending');
$itemLines = parse_order_items($order['items'] ?? '');

$pageTitle = 'Order ' . format_order_number($orderId);
$pageDescription = 'View your order details and current status.';
$currentPage = 'track-order';

require __DIR__ . '/includes/header.php';
?>

<div class="page-header page-header--compact">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= e(track_order_url()) ?>">Track Order</a>
            <span aria-hidden="true">/</span>
            <span><?= e(format_order_number($orderId)) ?></span>
        </nav>
        <h1>Order <?= e(format_order_number($orderId)) ?></h1>
        <p>Placed on <?= e(format_order_datetime($order['created_at'] ?? '')) ?></p>
    </div>
</div>

<section class="section section--compact">
    <div class="container order-detail-layout">
        <div class="order-detail-main">
            <div class="panel order-detail-card">
                <div class="order-detail-card__header">
                    <h2>Order Status</h2>
                    <span class="order-status <?= e(order_status_badge_class($status)) ?> order-status--lg">
                        <?= e(order_status_label($status)) ?>
                    </span>
                </div>

                <p class="order-detail-card__status-desc"><?= e(order_status_description($status)) ?></p>

                <ol class="order-timeline" aria-label="Order progress">
                    <li class="order-timeline__step <?= in_array($status, ['pending', 'confirmed', 'completed'], true) ? 'is-complete' : '' ?> <?= $status === 'pending' ? 'is-current' : '' ?>">
                        <span class="order-timeline__dot"></span>
                        <div>
                            <strong>Order Received</strong>
                            <span>We have your order on file</span>
                        </div>
                    </li>
                    <li class="order-timeline__step <?= in_array($status, ['confirmed', 'completed'], true) ? 'is-complete' : '' ?> <?= $status === 'confirmed' ? 'is-current' : '' ?>">
                        <span class="order-timeline__dot"></span>
                        <div>
                            <strong>Confirmed</strong>
                            <span>We are preparing your items</span>
                        </div>
                    </li>
                    <li class="order-timeline__step <?= $status === 'completed' ? 'is-complete is-current' : '' ?>">
                        <span class="order-timeline__dot"></span>
                        <div>
                            <strong>Completed</strong>
                            <span>Order picked up</span>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="panel order-detail-card">
                <h2>Items Ordered</h2>
                <ul class="order-detail-items">
                    <?php foreach ($itemLines as $line): ?>
                    <li><?= e($line) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php $orderTotal = (float) ($order['total'] ?? 0); ?>
                <?php if ($orderTotal > 0): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid var(--color-pastel);">
                    <strong>Total</strong>
                    <span style="font-size: 1.35rem; font-weight: 700; color: var(--color-accent);"><?= e(format_price($orderTotal)) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="order-detail-sidebar panel">
            <h2>Pickup Details</h2>

            <dl class="order-detail-meta">
                <div>
                    <dt>Name</dt>
                    <dd><?= e($order['name']) ?></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd><?= e($order['email']) ?></dd>
                </div>
                <div>
                    <dt>Phone</dt>
                    <dd><?= e($order['phone']) ?></dd>
                </div>
                <div>
                    <dt>Pickup Date</dt>
                    <dd><?= e(format_pickup_date($order['pickup_date'] ?? '')) ?></dd>
                </div>
            </dl>

            <?php if (!empty($order['message'])): ?>
            <div class="order-detail-message">
                <h3>Your Message</h3>
                <p><?= nl2br(e($order['message'])) ?></p>
            </div>
            <?php endif; ?>

            <a href="<?= e(track_order_url()) ?>" class="btn btn--ghost" style="width: 100%; margin-top: 1rem;">← Back to Track Order</a>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
