<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim(strtolower((string)($_POST['email'] ?? '')));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Enter a valid email address']);
    exit;
}

// Always return the same message to avoid admin email enumeration
$successMsg = 'If that email matches our records, a reset link has been sent. Check your inbox.';

$adminEmail = defined('ADMIN_EMAIL') ? strtolower(trim(ADMIN_EMAIL)) : '';

if ($adminEmail === '' || $adminEmail !== $email) {
    echo json_encode(['success' => true, 'message' => $successMsg]);
    exit;
}

try {
    $pdo = getPDO();

    // Remove expired tokens
    $pdo->exec('DELETE FROM password_resets WHERE created_at < DATE_SUB(NOW(), INTERVAL 2 HOUR)');

    $token = bin2hex(random_bytes(32));
    $pdo->prepare('INSERT INTO password_resets (token) VALUES (?)')->execute([$token]);

    $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir      = rtrim(dirname($_SERVER['PHP_SELF'] ?? '/'), '/');
    $resetUrl = "{$scheme}://{$host}{$dir}/reset_password.php?token={$token}";

    $body = "Hello,\n\n"
          . "A password reset was requested for the Risonaf Loans Ghana admin account.\n\n"
          . "Click the link below to set a new password. The link is valid for 1 hour:\n\n"
          . $resetUrl . "\n\n"
          . "If you did not request this reset, you can safely ignore this email.\n\n"
          . "— Risonaf Loans Ghana";

    sendMail(ADMIN_EMAIL, 'Password Reset — Risonaf Loans Ghana', $body);

    echo json_encode(['success' => true, 'message' => $successMsg]);
} catch (Throwable $e) {
    // Return success anyway to avoid leaking info
    echo json_encode(['success' => true, 'message' => $successMsg]);
}
