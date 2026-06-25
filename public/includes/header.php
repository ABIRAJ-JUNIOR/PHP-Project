<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'The Baker Best';
$pageDescription = $pageDescription ?? 'Artisan bakery offering handcrafted breads, pastries, and cakes. Order online for pickup.';
$currentPage = $currentPage ?? '';
$extraHead = $extraHead ?? '';
$bodyClass = $bodyClass ?? '';
$structuredData = $structuredData ?? null;

$siteName = $config['site_name'] ?? 'The Baker Best';
$fullTitle = ($pageTitle === $siteName) ? $pageTitle : $pageTitle . ' | ' . $siteName;
$canonicalUrl = page_url($currentPage === 'home' ? '' : $currentPage . '.php');
$cartItemCount = cart_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($fullTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#6B4226">
    <link rel="icon" href="<?= e(asset('images/favicon.svg')) ?>" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&family=Sacramento&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= e(asset('css/main.css')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

    <?php if ($structuredData): ?>
    <script type="application/ld+json"><?= $structuredData ?></script>
    <?php endif; ?>

    <?= $extraHead ?>
</head>
<body class="<?= e($bodyClass) ?>">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="site-header" role="banner">
        <div class="container site-header__inner">
            <a href="<?= e(page_url('index.php')) ?>" class="site-logo" aria-label="The Baker Best — Home">
                The Baker <span>Best</span>
            </a>

            <a href="<?= e(cart_url()) ?>" class="cart-link<?= $currentPage === 'cart' ? ' is-active' : '' ?>"
               aria-label="View cart<?= $cartItemCount > 0 ? ', ' . $cartItemCount . ' items' : '' ?>">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <?php if ($cartItemCount > 0): ?>
                <span class="cart-link__badge"><?= e((string) $cartItemCount) ?></span>
                <?php endif; ?>
            </a>

            <button class="nav-toggle" type="button"
                    aria-expanded="false"
                    aria-controls="main-navigation"
                    aria-label="Toggle navigation menu">
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
            </button>

            <nav id="main-navigation" class="main-nav" role="navigation" aria-label="Main navigation">
                <ul class="main-nav__list">
                    <li class="main-nav__item">
                        <a href="<?= e(page_url('index.php')) ?>"
                           class="main-nav__link<?= $currentPage === 'home' ? ' is-active' : '' ?>">Home</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= e(page_url('about.php')) ?>"
                           class="main-nav__link<?= $currentPage === 'about' ? ' is-active' : '' ?>">About Us</a>
                    </li>
                    <li class="main-nav__item nav-dropdown">
                        <button type="button" class="nav-dropdown__toggle" aria-expanded="false" aria-haspopup="true">
                            Menu <span aria-hidden="true">▾</span>
                        </button>
                        <ul class="nav-dropdown__menu" role="menu">
                            <li role="none"><a href="<?= e(page_url('menu.php')) ?>" role="menuitem">All Items</a></li>
                            <li role="none"><a href="<?= e(page_url('menu.php')) ?>#breads" role="menuitem">Breads</a></li>
                            <li role="none"><a href="<?= e(page_url('menu.php')) ?>#pastries" role="menuitem">Pastries</a></li>
                            <li role="none"><a href="<?= e(page_url('menu.php')) ?>#cakes" role="menuitem">Cakes</a></li>
                            <li role="none"><a href="<?= e(page_url('menu.php')) ?>#seasonal" role="menuitem">Seasonal</a></li>
                            <li role="none"><a href="<?= e(page_url('menu.php')) ?>#beverages" role="menuitem">Beverages</a></li>
                        </ul>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= e(page_url('menu.php')) ?>"
                           class="main-nav__link main-nav__link--cta<?= $currentPage === 'menu' ? ' is-active' : '' ?>">Order Online</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= e(track_order_url()) ?>"
                           class="main-nav__link<?= $currentPage === 'track-order' ? ' is-active' : '' ?>">Track Order</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= e(page_url('gallery.php')) ?>"
                           class="main-nav__link<?= $currentPage === 'gallery' ? ' is-active' : '' ?>">Gallery</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= e(page_url('contact.php')) ?>"
                           class="main-nav__link<?= $currentPage === 'contact' ? ' is-active' : '' ?>">Contact</a>
                    </li>
                </ul>

                <div class="header-social" aria-label="Social media links">
                    <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content">
