<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Your Cart';
$pageDescription = 'Review the items in your cart before checkout.';
$currentPage = 'cart';
$extraScripts = ['item-order.js'];
$orderStep = 'cart';

$pdo = getDb($config);
$cartLines = cart_resolve($pdo);
$cartTotal = cart_total($cartLines);
$itemCount = cart_count();

$cartSuccess = flash('cart_success');
$formError = flash('form_error');

require __DIR__ . '/includes/header.php';
?>

<div class="page-header page-header--compact">
    <div class="container">
        <h1>Your Cart</h1>
        <p><?= $itemCount > 0 ? e((string) $itemCount) . ' item' . ($itemCount === 1 ? '' : 's') . ' ready for pickup order.' : 'Review your items before checkout.' ?></p>
    </div>
</div>

<section class="section section--compact">
    <div class="container">
        <?php require __DIR__ . '/includes/order-steps.php'; ?>

        <?php if ($cartSuccess): ?>
        <div class="form-alert form-alert--success" role="status"><?= e($cartSuccess) ?></div>
        <?php endif; ?>

        <?php if ($formError): ?>
        <div class="form-alert form-alert--error" role="alert"><?= e($formError) ?></div>
        <?php endif; ?>

        <?php if (empty($cartLines)): ?>
        <div class="cart-empty panel">
            <div class="cart-empty__icon" aria-hidden="true">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <h2>Your cart is empty</h2>
            <p>Browse our fresh breads, pastries, and cakes to start your order.</p>
            <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--primary btn--lg">Browse Menu</a>
        </div>
        <?php else: ?>
        <form class="cart-form cart-layout" id="cart-update-form" method="POST" action="<?= e(page_url('api/cart-action.php')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update">

            <div class="cart-layout__items">
                <div class="cart-items">
                    <?php foreach ($cartLines as $line): ?>
                    <article class="cart-item panel panel--flat">
                        <a href="<?= e(menu_item_url($line['id'])) ?>" class="cart-item__image">
                            <img src="<?= e(asset($line['image_path'])) ?>"
                                 alt="<?= e($line['name']) ?>"
                                 width="120" height="90" loading="lazy">
                        </a>

                        <div class="cart-item__details">
                            <h2 class="cart-item__name">
                                <a href="<?= e(menu_item_url($line['id'])) ?>"><?= e($line['name']) ?></a>
                            </h2>
                            <p class="cart-item__meta">
                                <span class="cart-item__category"><?= e($line['category']) ?></span>
                                <?= e(format_price($line['price'])) ?> each
                            </p>

                            <div class="quantity-picker quantity-picker--sm" data-quantity-picker>
                                <button type="button" class="quantity-picker__btn" data-quantity-decrease aria-label="Decrease quantity">−</button>
                                <input type="number"
                                       name="quantities[<?= e((string) $line['id']) ?>]"
                                       value="<?= e((string) $line['quantity']) ?>"
                                       min="0" max="99"
                                       aria-label="Quantity for <?= e($line['name']) ?>">
                                <button type="button" class="quantity-picker__btn" data-quantity-increase aria-label="Increase quantity">+</button>
                            </div>
                        </div>

                        <div class="cart-item__total">
                            <span class="cart-item__line-total"><?= e(format_price($line['line_total'])) ?></span>
                            <button type="submit"
                                    class="cart-item__remove-btn"
                                    name="remove_item_id"
                                    value="<?= e((string) $line['id']) ?>"
                                    aria-label="Remove <?= e($line['name']) ?> from cart">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn--ghost cart-update-btn">Update quantities</button>
            </div>

            <aside class="cart-layout__summary">
                <div class="cart-summary panel">
                    <h2 class="cart-summary__title">Order Summary</h2>

                    <ul class="cart-summary__list">
                        <?php foreach ($cartLines as $line): ?>
                        <li class="cart-summary__row">
                            <span><?= e((string) $line['quantity']) ?> × <?= e($line['name']) ?></span>
                            <span><?= e(format_price($line['line_total'])) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="cart-summary__divider"></div>

                    <div class="cart-summary__total-row">
                        <span>Total</span>
                        <strong><?= e(format_price($cartTotal)) ?></strong>
                    </div>

                    <p class="cart-summary__note">Pickup orders — we'll confirm by email or phone.</p>

                    <a href="<?= e(checkout_url()) ?>" class="btn btn--primary btn--lg cart-summary__checkout">Proceed to Checkout</a>
                    <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--ghost cart-summary__continue">Continue Shopping</a>
                </div>
            </aside>
        </form>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
