<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'About Us';
$pageDescription = 'Learn the story behind The Baker Best — our family heritage, artisan values, and commitment to locally sourced ingredients.';
$currentPage = 'about';

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>About Us</h1>
        <p>A family tradition of baking with heart, heritage, and the finest local ingredients.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="about-intro">
            <div>
                <h2>Our Family Story</h2>
                <p>The Baker Best was born from a simple recipe passed down three generations. What started in Grandma Rose's kitchen — the smell of rising dough and cinnamon on Sunday mornings — became a neighborhood gathering place where everyone feels like family.</p>
                <p>In 2010, siblings Maria and James opened our first storefront on Main Street. Their mission was clear: bake everything from scratch, never cut corners, and treat every customer like a guest in their home.</p>
                <p>Today, our team of passionate bakers continues that tradition, waking before dawn to ensure every loaf, croissant, and cake that leaves our doors is something we'd proudly serve at our own table.</p>
            </div>
            <div>
                <img src="<?= e(asset('images/team/founder.jpg')) ?>" alt="Maria and James, founders of The Baker Best" width="600" height="450" loading="lazy" style="border-radius: var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__header">
            <h2>Our Mission &amp; Values</h2>
            <p class="section__subtitle">What guides us every day in the kitchen</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-card__icon" aria-hidden="true">🌾</div>
                <h3>Artisan Craft</h3>
                <p>Every product is shaped by hand using time-honored techniques. We ferment our sourdough for 24 hours and laminate our pastry dough with real butter.</p>
            </div>
            <div class="value-card">
                <div class="value-card__icon" aria-hidden="true">🏡</div>
                <h3>Local Sourcing</h3>
                <p>We partner with nearby farms and mills for flour, eggs, dairy, and seasonal produce. Supporting local growers is at the heart of what we do.</p>
            </div>
            <div class="value-card">
                <div class="value-card__icon" aria-hidden="true">❤️</div>
                <h3>Community First</h3>
                <p>From farmers market pop-ups to school fundraisers, we believe a great bakery is a cornerstone of community life.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header">
            <h2>Meet Our Team</h2>
            <p class="section__subtitle">The talented hands behind every bake</p>
        </div>
        <div class="card-grid card-grid--3">
            <article class="card">
                <div class="card__image">
                    <img src="<?= e(asset('images/team/founder.jpg')) ?>" alt="Maria Lopez, Head Baker and Co-founder" width="400" height="300" loading="lazy">
                </div>
                <div class="card__body">
                    <h3 class="card__title">Maria Lopez</h3>
                    <p class="card__desc">Head Baker &amp; Co-founder. Maria leads recipe development and our sourdough program.</p>
                </div>
            </article>
            <article class="card">
                <div class="card__image">
                    <img src="<?= e(asset('images/team/baker-1.jpg')) ?>" alt="James Lopez, Pastry Chef and Co-founder" width="400" height="300" loading="lazy">
                </div>
                <div class="card__body">
                    <h3 class="card__title">James Lopez</h3>
                    <p class="card__desc">Pastry Chef &amp; Co-founder. James crafts our viennoiserie and celebration cakes.</p>
                </div>
            </article>
            <article class="card">
                <div class="card__image">
                    <img src="<?= e(asset('images/team/baker-2.jpg')) ?>" alt="Sophie Chen, Lead Pastry Baker" width="400" height="300" loading="lazy">
                </div>
                <div class="card__body">
                    <h3 class="card__title">Sophie Chen</h3>
                    <p class="card__desc">Lead Pastry Baker. Sophie brings creativity to our seasonal specials and custom orders.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__header">
            <h2>Ingredient Philosophy</h2>
        </div>
        <div class="about-intro">
            <div>
                <p>We believe you can taste the difference when ingredients are chosen with care. Our flour comes from a family-owned mill just 40 miles away. Our butter is from a local dairy cooperative. Our seasonal fruit is sourced from orchards we visit personally.</p>
                <p>We never use artificial preservatives, flavors, or colors. If it wouldn't be in Grandma Rose's pantry, it won't be in our kitchen. Every ingredient list is short, honest, and pronounceable.</p>
            </div>
            <div>
                <img src="<?= e(asset('images/gallery/baker-kneading.jpg')) ?>" alt="Baker hand-kneading dough with locally sourced flour" width="600" height="450" loading="lazy" style="border-radius: var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
