<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Message Sent';
$pageDescription = 'Your message has been sent to The Baker Best. We will get back to you soon.';
$currentPage = 'contact';

require __DIR__ . '/includes/header.php';
?>

<div class="success-box">
    <div class="success-box__icon" aria-hidden="true">✉️</div>
    <h1>Message Sent!</h1>
    <p>Thank you for reaching out. We've received your message and will respond as soon as possible.</p>
    <div class="btn-group" style="margin-top: 2rem;">
        <a href="<?= e(page_url('index.php')) ?>" class="btn btn--primary">Back to Home</a>
        <a href="<?= e(page_url('contact.php')) ?>" class="btn btn--secondary">Send Another Message</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
