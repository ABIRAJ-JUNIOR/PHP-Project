<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(page_url('contact.php'));
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('form_error', 'Invalid form submission. Please try again.');
    redirect(page_url('contact.php'));
}

if (!check_rate_limit('contact', $config)) {
    flash('form_error', 'Too many submissions. Please try again later.');
    redirect(page_url('contact.php'));
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

if ($name === '' || strlen($name) > 100) {
    $errors[] = 'Please enter a valid name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($message === '') {
    $errors[] = 'Please enter your message.';
}

if ($errors) {
    $_SESSION['old_input'] = $_POST;
    flash('form_error', implode(' ', $errors));
    redirect(page_url('contact.php'));
}

$pdo = getDb($config);
if (!$pdo) {
    flash('form_error', 'Unable to send your message at this time. Please call us directly.');
    redirect(page_url('contact.php'));
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)'
    );
    $stmt->execute([$name, $email, $message]);
} catch (PDOException $e) {
    if ($config['debug']) {
        error_log('Contact insert failed: ' . $e->getMessage());
    }
    flash('form_error', 'Unable to save your message. Please try again or call us.');
    redirect(page_url('contact.php'));
}

$html = '<h2>New Contact Message</h2>';
$html .= '<p><strong>Name:</strong> ' . e($name) . '</p>';
$html .= '<p><strong>Email:</strong> ' . e($email) . '</p>';
$html .= '<p><strong>Message:</strong><br>' . nl2br(e($message)) . '</p>';

send_admin_email($config, 'Contact Message from ' . $name, $html, $email, $name);

redirect(page_url('contact-success.php'));
