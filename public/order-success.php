<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Order Confirmed';
$pageDescription = 'Your order has been submitted successfully. The Baker Best will confirm your pickup details shortly.';
$currentPage = 'cart';
$orderStep = 'done';

$orderId = (int) (flash('order_id') ?? 0);
$orderEmail = flash('order_email') ?? '';

require __DIR__ . '/includes/header.php';
?>

<section class="section section--compact">
    <div class="container">
        <?php require __DIR__ . '/includes/order-steps.php'; ?>

        <div class="success-box panel">
            <div class="success-box__icon" aria-hidden="true">✓</div>
            <h1>Order Received!</h1>
            <p>Thank you for your order. We've received your request and will confirm your pickup details via email or phone shortly.</p>

            <?php if ($orderId > 0): ?>
            <div class="order-confirmation-ref">
                <p>Your order number</p>
                <strong><?= e(format_order_number($orderId)) ?></strong>
            </div>
            <?php endif; ?>

            <div class="btn-group" style="margin-top: 2rem;">
                <?php if ($orderId > 0): ?>
                    <?php grant_order_access($orderId); ?>
                <a href="<?= e(order_detail_url($orderId)) ?>" class="btn btn--primary btn--lg">View Order Status</a>
                <?php endif; ?>
                <a href="<?= e(track_order_url()) ?>" class="btn btn--secondary">Track Order Later</a>
                <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--ghost">Order Again</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
