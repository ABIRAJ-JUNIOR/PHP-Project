<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Menu';
$pageDescription = 'Browse our full menu of artisan breads, pastries, cakes, seasonal specials, and beverages. Prices and allergen info included.';
$currentPage = 'menu';
$extraScripts = ['menu-filter.js'];

$menuItems = [];
$pdo = getDb($config);
if ($pdo) {
    $stmt = $pdo->query('SELECT * FROM menu_items ORDER BY FIELD(category, "Breads", "Pastries", "Cakes", "Seasonal", "Beverages"), sort_order');
    $menuItems = $stmt->fetchAll();
}

$categories = ['Breads', 'Pastries', 'Cakes', 'Seasonal', 'Beverages'];

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Our Menu</h1>
        <p>Handcrafted daily with premium ingredients. Allergen information is provided for your safety.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="menu-toolbar panel panel--flat">
            <div class="menu-search">
                <svg class="menu-search__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <label for="menu-search-input" class="sr-only">Search menu items</label>
                <input type="search" id="menu-search-input" placeholder="Search breads, pastries, cakes..." aria-label="Search menu items">
            </div>

            <div class="menu-filter" role="group" aria-label="Filter by category">
                <button type="button" class="menu-filter__btn is-active" data-category="all">All</button>
                <?php foreach ($categories as $cat): ?>
                <button type="button" class="menu-filter__btn" data-category="<?= e(strtolower($cat)) ?>"><?= e($cat) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!empty($menuItems)): ?>
            <?php foreach ($categories as $category): ?>
                <?php
                $catItems = array_filter($menuItems, fn($item) => $item['category'] === $category);
                if (empty($catItems)) continue;
                $catId = strtolower($category);
                ?>
            <div id="<?= e($catId) ?>" class="menu-category" data-category="<?= e($catId) ?>">
                <div class="menu-category__header">
                    <h2><?= e($category) ?></h2>
                    <span class="menu-category__count"><?= count($catItems) ?> items</span>
                </div>
                <div class="card-grid card-grid--3">
                    <?php foreach ($catItems as $item): ?>
                    <a href="<?= e(menu_item_url((int) $item['id'])) ?>" class="card card--link menu-item" data-category="<?= e($catId) ?>" data-name="<?= e(strtolower($item['name'])) ?>">
                        <div class="card__image">
                            <img src="<?= e(asset($item['image_path'])) ?>"
                                 alt="<?= e($item['name']) ?>"
                                 width="400" height="300" loading="lazy">
                            <span class="card__overlay">View details</span>
                        </div>
                        <div class="card__body">
                            <div class="card__top">
                                <h3 class="card__title"><?= e($item['name']) ?></h3>
                                <p class="card__price"><?= e(format_price((float) $item['price'])) ?></p>
                            </div>
                            <p class="card__desc"><?= e($item['description']) ?></p>
                            <?php $allergens = parse_allergens($item['allergens']); ?>
                            <?php if ($allergens): ?>
                            <div class="tags" aria-label="Allergens: <?= e(implode(', ', $allergens)) ?>">
                                <?php foreach ($allergens as $allergen): ?>
                                <span class="tag"><?= e($allergen) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Menu items will appear here once the database is connected. Run <code>sql/schema.sql</code> and <code>sql/seed.sql</code> to populate sample data.</p>
        <?php endif; ?>

        <div class="menu-footer-note">
            <p>Tap any item to view details, choose quantity, and add to your cart.</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
