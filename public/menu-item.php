<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$itemId = (int) ($_GET['id'] ?? 0);
$item = null;

if ($itemId > 0) {
    $pdo = getDb($config);
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM menu_items WHERE id = ? LIMIT 1');
        $stmt->execute([$itemId]);
        $item = $stmt->fetch() ?: null;
    }
}

if (!$item) {
    http_response_code(404);
    $pageTitle = 'Item Not Found';
    $pageDescription = 'The menu item you are looking for could not be found.';
    $currentPage = 'menu';
    require __DIR__ . '/includes/header.php';
    ?>
    <div class="page-header">
        <div class="container">
            <h1>Item Not Found</h1>
            <p>Sorry, we couldn't find that menu item. It may have been removed or the link is incorrect.</p>
        </div>
    </div>
    <section class="section">
        <div class="container text-center">
            <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--primary">Back to Menu</a>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $item['name'];
$pageDescription = mb_substr(strip_tags($item['description']), 0, 160);
$currentPage = 'menu';
$extraScripts = ['forms.js', 'item-order.js'];

$formError = flash('form_error');
$cartSuccess = flash('cart_success');
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

$allergens = parse_allergens($item['allergens']);
$unitPrice = (float) $item['price'];
$quantity = max(1, min(99, (int) ($old['quantity'] ?? 1)));
$cartItemCount = cart_count();
$orderStep = 'menu';

require __DIR__ . '/includes/header.php';
?>

<div class="page-header page-header--compact">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= e(page_url('menu.php')) ?>">Menu</a>
            <span aria-hidden="true">/</span>
            <span><?= e($item['category']) ?></span>
        </nav>
        <h1><?= e($item['name']) ?></h1>
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

        <div class="item-detail panel">
            <div class="item-detail__media">
                <img src="<?= e(asset($item['image_path'])) ?>"
                     alt="<?= e($item['name']) ?>"
                     width="600" height="450" loading="eager">
            </div>

            <div class="item-detail__info">
                <div class="item-detail__header">
                    <span class="item-detail__category"><?= e($item['category']) ?></span>
                    <p class="item-detail__price" data-unit-price="<?= e((string) $unitPrice) ?>">
                        <?= e(format_price($unitPrice)) ?>
                    </p>
                </div>

                <p class="item-detail__desc"><?= e($item['description']) ?></p>

                <?php if ($allergens): ?>
                <div class="tags" aria-label="Allergens: <?= e(implode(', ', $allergens)) ?>">
                    <?php foreach ($allergens as $allergen): ?>
                    <span class="tag"><?= e($allergen) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="purchase-panel">
                    <h2 class="purchase-panel__title">Add to Order</h2>
                    <p class="purchase-panel__subtitle">Select quantity and add this item to your cart.</p>

                    <form class="form item-cart-form" id="add-to-cart-form" method="POST"
                          action="<?= e(page_url('api/cart-action.php')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="menu_item_id" value="<?= e((string) $item['id']) ?>">
                        <input type="hidden" name="return_url" value="<?= e(menu_item_url((int) $item['id'])) ?>">

                        <div class="form-group">
                            <label for="order-quantity">Quantity</label>
                            <div class="quantity-picker" data-quantity-picker>
                                <button type="button" class="quantity-picker__btn" data-quantity-decrease aria-label="Decrease quantity">−</button>
                                <input type="number" id="order-quantity" name="quantity"
                                       value="<?= e((string) $quantity) ?>"
                                       min="1" max="99" required aria-required="true">
                                <button type="button" class="quantity-picker__btn" data-quantity-increase aria-label="Increase quantity">+</button>
                            </div>
                            <div class="item-order-total">
                                <span>Subtotal</span>
                                <strong id="order-subtotal" data-order-subtotal><?= e(format_price($unitPrice * $quantity)) ?></strong>
                            </div>
                            <div class="form-error" id="order-quantity-error" role="alert"></div>
                        </div>

                        <div class="btn-group item-cart-actions">
                            <button type="submit" class="btn btn--primary btn--lg">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Add to Cart
                            </button>
                            <?php if ($cartItemCount > 0): ?>
                            <a href="<?= e(cart_url()) ?>" class="btn btn--secondary btn--lg">
                                View Cart (<?= e((string) $cartItemCount) ?>)
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <p class="item-detail__note">
                    <a href="<?= e(page_url('menu.php')) ?>">← Back to full menu</a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
