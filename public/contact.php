<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with The Baker Best. Visit us at 123 Main Street, call (555) 123-4567, or send us a message.';
$currentPage = 'contact';
$extraScripts = ['forms.js'];

$formError = flash('form_error');
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

$structuredData = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => 'The Baker Best',
    '@id' => page_url('contact.php'),
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
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Stop by, give us a call, or send a message.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <h2>Get in Touch</h2>

                <div class="contact-info__item">
                    <div class="contact-info__icon" aria-hidden="true">📍</div>
                    <div>
                        <strong>Address</strong><br>
                        123 Main Street<br>
                        Downtown, ST 12345
                    </div>
                </div>

                <div class="contact-info__item">
                    <div class="contact-info__icon" aria-hidden="true">📞</div>
                    <div>
                        <strong>Phone</strong><br>
                        <a href="tel:+15551234567">(555) 123-4567</a>
                    </div>
                </div>

                <div class="contact-info__item">
                    <div class="contact-info__icon" aria-hidden="true">✉️</div>
                    <div>
                        <strong>Email</strong><br>
                        <a href="mailto:hello@bakerbest.com">hello@bakerbest.com</a>
                    </div>
                </div>

                <h3 style="margin-top: 2rem;">Hours of Operation</h3>
                <table class="hours-table" aria-label="Hours of operation">
                    <tbody>
                        <tr><td>Monday – Friday</td><td>7:00 AM – 6:00 PM</td></tr>
                        <tr><td>Saturday</td><td>8:00 AM – 5:00 PM</td></tr>
                        <tr><td>Sunday</td><td>8:00 AM – 2:00 PM</td></tr>
                    </tbody>
                </table>

                <div class="footer-social" style="margin-top: 1.5rem;" aria-label="Social media links">
                    <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" style="background: var(--color-pastel); color: var(--color-crust);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook" style="background: var(--color-pastel); color: var(--color-crust);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <?php if ($formError): ?>
                <div class="form-alert form-alert--error" role="alert"><?= e($formError) ?></div>
                <?php endif; ?>

                <h2>Send a Message</h2>
                <form class="form" id="contact-form" method="POST" action="<?= e(page_url('api/submit-contact.php')) ?>" novalidate>
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="contact-name">Full Name <span class="required" aria-hidden="true">*</span></label>
                        <input type="text" id="contact-name" name="name" required maxlength="100"
                               value="<?= e($old['name'] ?? '') ?>" autocomplete="name" aria-required="true">
                        <div class="form-error" id="contact-name-error" role="alert"></div>
                    </div>

                    <div class="form-group">
                        <label for="contact-email">Email <span class="required" aria-hidden="true">*</span></label>
                        <input type="email" id="contact-email" name="email" required maxlength="255"
                               value="<?= e($old['email'] ?? '') ?>" autocomplete="email" aria-required="true">
                        <div class="form-error" id="contact-email-error" role="alert"></div>
                    </div>

                    <div class="form-group">
                        <label for="contact-message">Message <span class="required" aria-hidden="true">*</span></label>
                        <textarea id="contact-message" name="message" required maxlength="2000"
                                  aria-required="true"><?= e($old['message'] ?? '') ?></textarea>
                        <div class="form-error" id="contact-message-error" role="alert"></div>
                    </div>

                    <button type="submit" class="btn btn--primary" style="width: 100%;">Send Message</button>
                </form>
            </div>
        </div>

        <div class="map-embed">
            <iframe
                src="https://maps.google.com/maps?q=123+Main+Street+Downtown&output=embed"
                title="Google Maps showing The Baker Best location at 123 Main Street"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
