<?php

declare(strict_types=1);

/** @var string $orderStep One of: menu, cart, checkout, done */
$orderStep = $orderStep ?? 'menu';

$steps = [
    'menu' => ['label' => 'Browse Menu', 'url' => page_url('menu.php')],
    'cart' => ['label' => 'Your Cart', 'url' => cart_url()],
    'checkout' => ['label' => 'Checkout', 'url' => checkout_url()],
    'done' => ['label' => 'Confirmed', 'url' => null],
];

$stepOrder = array_keys($steps);
$currentIndex = array_search($orderStep, $stepOrder, true);
if ($currentIndex === false) {
    $currentIndex = 0;
}
?>
<nav class="order-steps" aria-label="Order progress">
    <ol class="order-steps__list">
        <?php foreach ($stepOrder as $index => $key): ?>
            <?php
            $step = $steps[$key];
            $state = $index < $currentIndex ? 'is-complete' : ($index === $currentIndex ? 'is-active' : '');
            ?>
        <li class="order-steps__item <?= e($state) ?>">
            <?php if ($step['url'] && $index <= $currentIndex): ?>
            <a href="<?= e($step['url']) ?>" class="order-steps__link"><?= e($step['label']) ?></a>
            <?php else: ?>
            <span class="order-steps__label"><?= e($step['label']) ?></span>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ol>
</nav>
