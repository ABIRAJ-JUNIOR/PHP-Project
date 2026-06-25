<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_admin_email(array $config, string $subject, string $htmlBody, ?string $replyToEmail = null, ?string $replyToName = null): bool
{
    $autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
    if (!file_exists($autoload)) {
        return false;
    }

    require_once $autoload;

    $smtp = $config['smtp'];
    if (empty($smtp['user']) && empty($smtp['pass'])) {
        return false;
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtp['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtp['user'];
        $mail->Password = $smtp['pass'];
        $mail->Port = (int) $smtp['port'];
        $mail->SMTPSecure = (int) $smtp['port'] === 465
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom($smtp['from_email'], $smtp['from_name']);
        $mail->addAddress($config['admin_email']);

        if ($replyToEmail) {
            $mail->addReplyTo($replyToEmail, $replyToName ?? '');
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        if ($config['debug']) {
            error_log('Email send failed: ' . $e->getMessage());
        }
        return false;
    }
}
