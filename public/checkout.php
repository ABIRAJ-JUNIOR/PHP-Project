<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pdo = getDb($config);
$cartLines = cart_resolve($pdo);

if (empty($cartLines)) {
    flash('form_error', 'Your cart is empty. Add items from the menu first.');
    redirect(cart_url());
}

$cartTotal = cart_total($cartLines);
$orderStep = 'checkout';

$pageTitle = 'Checkout';
$pageDescription = 'Complete your bakery order for pickup.';
$currentPage = 'cart';
$extraScripts = ['forms.js'];

$formError = flash('form_error');
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

require __DIR__ . '/includes/header.php';
?>

<div class="page-header page-header--compact">
    <div class="container">
        <h1>Checkout</h1>
        <p>Enter your details and we'll prepare your order for pickup.</p>
    </div>
</div>

<section class="section section--compact">
    <div class="container">
        <?php require __DIR__ . '/includes/order-steps.php'; ?>

        <div class="checkout-layout">
            <aside class="checkout-summary panel">
                <h2>Order Summary</h2>

                <ul class="checkout-summary__list">
                    <?php foreach ($cartLines as $line): ?>
                    <li class="checkout-summary__item">
                        <div class="checkout-summary__product">
                            <img src="<?= e(asset($line['image_path'])) ?>"
                                 alt=""
                                 width="48" height="48" loading="lazy"
                                 class="checkout-summary__thumb">
                            <span><?= e((string) $line['quantity']) ?> × <?= e($line['name']) ?></span>
                        </div>
                        <span class="checkout-summary__price"><?= e(format_price($line['line_total'])) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="checkout-summary__divider"></div>

                <div class="checkout-summary__total-row">
                    <span>Total</span>
                    <strong><?= e(format_price($cartTotal)) ?></strong>
                </div>

                <p class="checkout-summary__note">
                    <a href="<?= e(cart_url()) ?>">← Edit cart</a>
                </p>
            </aside>

            <div class="checkout-form-wrap panel">
                <h2 class="checkout-form__title">Your Details</h2>

                <?php if ($formError): ?>
                <div class="form-alert form-alert--error" role="alert"><?= e($formError) ?></div>
                <?php endif; ?>

                <form class="form" id="checkout-form" method="POST"
                      action="<?= e(page_url('api/submit-order.php')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="from_cart" value="1">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="order-name">Full Name <span class="required" aria-hidden="true">*</span></label>
                            <input type="text" id="order-name" name="name" required maxlength="100"
                                   value="<?= e($old['name'] ?? '') ?>" autocomplete="name"
                                   aria-required="true">
                            <div class="form-error" id="order-name-error" role="alert"></div>
                        </div>
                    </div>

                    <div class="form-row form-row--2">
                        <div class="form-group">
                            <label for="order-email">Email <span class="required" aria-hidden="true">*</span></label>
                            <input type="email" id="order-email" name="email" required maxlength="255"
                                   value="<?= e($old['email'] ?? '') ?>" autocomplete="email"
                                   aria-required="true">
                            <div class="form-error" id="order-email-error" role="alert"></div>
                        </div>

                        <div class="form-group">
                            <label for="order-phone">Phone <span class="required" aria-hidden="true">*</span></label>
                            <input type="tel" id="order-phone" name="phone" required maxlength="30"
                                   value="<?= e($old['phone'] ?? '') ?>" autocomplete="tel"
                                   aria-required="true" placeholder="(555) 123-4567">
                            <div class="form-error" id="order-phone-error" role="alert"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="order-pickup">Preferred Pickup Date <span class="required" aria-hidden="true">*</span></label>
                        <input type="date" id="order-pickup" name="pickup_date" required
                               value="<?= e($old['pickup_date'] ?? '') ?>"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>" aria-required="true">
                        <div class="form-error" id="order-pickup-error" role="alert"></div>
                    </div>

                    <div class="form-group">
                        <label for="order-message">Additional Message</label>
                        <textarea id="order-message" name="message" maxlength="2000"
                                  placeholder="Allergies, special requests, or questions"><?= e($old['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn--primary btn--lg" style="width: 100%;">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
