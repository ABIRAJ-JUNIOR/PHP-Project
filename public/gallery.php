<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Gallery';
$pageDescription = 'Browse photos of our bakery, handcrafted products, team, and community events at The Baker Best.';
$currentPage = 'gallery';

$galleryImages = [];
$pdo = getDb($config);
if ($pdo) {
    $stmt = $pdo->query('SELECT * FROM gallery_images ORDER BY sort_order');
    $galleryImages = $stmt->fetchAll();
}

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Gallery</h1>
        <p>A glimpse into our kitchen, our creations, and the community we love.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (!empty($galleryImages)): ?>
        <div class="gallery-grid" id="gallery-grid">
            <?php foreach ($galleryImages as $image): ?>
            <a href="<?= e(asset($image['file_path'])) ?>"
               class="gallery-item glightbox"
               data-gallery="bakery-gallery"
               data-glightbox="title: <?= e($image['caption']) ?>"
               aria-label="View larger image: <?= e($image['caption']) ?>">
                <img src="<?= e(asset($image['file_path'])) ?>"
                     alt="<?= e($image['caption']) ?>"
                     width="400" height="400" loading="lazy">
                <span class="gallery-item__caption"><?= e($image['caption']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-center">Gallery images will appear here once the database is connected.</p>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
