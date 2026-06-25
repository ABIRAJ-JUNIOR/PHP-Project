<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'The Baker Best';
$pageDescription = 'Artisan bakery offering handcrafted breads, pastries, and cakes. Fresh daily, locally sourced ingredients. Order online for pickup.';
$currentPage = 'home';

$featuredItems = [];
$pdo = getDb($config);
if ($pdo) {
    $stmt = $pdo->query('SELECT * FROM menu_items WHERE is_featured = 1 ORDER BY sort_order LIMIT 6');
    $featuredItems = $stmt->fetchAll();
}

$structuredData = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Bakery',
    'name' => 'The Baker Best',
    'description' => 'Artisan bakery offering handcrafted breads, pastries, and cakes.',
    'url' => page_url('index.php'),
    'telephone' => '+1-555-123-4567',
    'email' => 'hello@bakerbest.com',
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => '123 Main Street',
        'addressLocality' => 'Downtown',
        'addressRegion' => 'ST',
        'postalCode' => '12345',
        'addressCountry' => 'US',
    ],
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => 40.7128,
        'longitude' => -74.0060,
    ],
    'openingHoursSpecification' => [
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'], 'opens' => '07:00', 'closes' => '18:00'],
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Saturday', 'opens' => '08:00', 'closes' => '17:00'],
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Sunday', 'opens' => '08:00', 'closes' => '14:00'],
    ],
    'servesCuisine' => 'Bakery',
    'priceRange' => '$$',
    'image' => asset('images/hero/hero-1.svg'),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG);

require __DIR__ . '/includes/header.php';
?>

<section class="hero" aria-label="Featured bakery images">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="<?= e(asset('images/hero/hero-1.svg')) ?>" alt="Fresh artisan bread cooling on wooden racks" width="1200" height="600" loading="eager">
                <div class="hero__overlay">
                    <div class="hero__content">
                        <h1>The Baker Best</h1>
                        <p>Handcrafted breads &amp; pastries baked fresh every morning.</p>
                        <div class="btn-group">
                            <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--primary">View Menu</a>
                            <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--secondary">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <img src="<?= e(asset('images/hero/hero-2.svg')) ?>" alt="Display of golden croissants and pastries" width="1200" height="600" loading="lazy">
                <div class="hero__overlay">
                    <div class="hero__content">
                        <h1>Artisan Pastries</h1>
                        <p>Buttery, flaky, and made from scratch with premium ingredients.</p>
                        <div class="btn-group">
                            <a href="<?= e(page_url('menu.php')) ?>#pastries" class="btn btn--primary">Explore Pastries</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <img src="<?= e(asset('images/hero/hero-3.svg')) ?>" alt="Baker decorating a celebration cake" width="1200" height="600" loading="lazy">
                <div class="hero__overlay">
                    <div class="hero__content">
                        <h1>Celebration Cakes</h1>
                        <p>Custom cakes for every occasion, made with love.</p>
                        <div class="btn-group">
                            <a href="<?= e(page_url('menu.php')) ?>#cakes" class="btn btn--primary">Order a Cake</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination" aria-label="Slide navigation"></div>
        <div class="swiper-button-prev" aria-label="Previous slide"></div>
        <div class="swiper-button-next" aria-label="Next slide"></div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-intro">
            <div>
                <h2>Our Story</h2>
                <p>Founded in 2010, The Baker Best began as a small family kitchen with a big dream: to bring the warmth and comfort of homemade bread back to our community. Today, we still mix every dough by hand and bake in small batches to ensure every loaf meets our standards.</p>
                <p>We believe great baking starts with great ingredients — locally milled flour, farm-fresh eggs, and butter from nearby dairies.</p>
                <a href="<?= e(page_url('about.php')) ?>" class="btn btn--secondary">Learn More About Us</a>
            </div>
            <div>
                <img src="<?= e(asset('images/team/founder.svg')) ?>" alt="Founder of The Baker Best in the bakery" width="600" height="450" loading="lazy" style="border-radius: var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__header">
            <h2>Featured &amp; Seasonal</h2>
            <p class="section__subtitle">Customer favorites and limited-time seasonal offerings</p>
        </div>
        <div class="card-grid card-grid--3">
            <?php if (!empty($featuredItems)): ?>
                <?php foreach ($featuredItems as $item): ?>
                <a href="<?= e(menu_item_url((int) $item['id'])) ?>" class="card card--link">
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
                        <div class="tags">
                            <?php foreach ($allergens as $allergen): ?>
                            <span class="tag"><?= e($allergen) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Our featured items will appear here once the menu database is connected.</p>
            <?php endif; ?>
        </div>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="<?= e(page_url('menu.php')) ?>" class="btn btn--primary">View Full Menu</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header">
            <h2>Follow Our Journey</h2>
            <p class="section__subtitle">See what's fresh from our ovens on Instagram</p>
        </div>
        <div class="instagram-embed">
            <!-- Replace data-instagram-widget with your SnapWidget or Elfsight embed code -->
            <div class="instagram-placeholder" role="region" aria-label="Instagram feed placeholder">
                <p><strong>Instagram Feed</strong></p>
                <p>Connect your Instagram account by replacing this placeholder with a SnapWidget or Elfsight embed in <code>index.php</code>.</p>
                <p><a href="https://instagram.com/" target="_blank" rel="noopener noreferrer">Follow us @thebakerbest</a></p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
