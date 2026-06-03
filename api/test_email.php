<?php
declare(strict_types=1);

session_start();
if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401); echo 'Unauthorised'; exit;
}

require_once __DIR__ . '/../config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== SMTP Diagnostic ===\n\n";
echo "SMTP_HOST : " . SMTP_HOST . "\n";
echo "SMTP_PORT : " . SMTP_PORT . "\n";
echo "SMTP_USER : " . SMTP_USER . "\n";
echo "SMTP_FROM : " . SMTP_FROM . "\n";
echo "SMTP_PASS : " . (SMTP_PASS ? str_repeat('*', strlen(SMTP_PASS)) : '(empty)') . "\n\n";

if (SMTP_HOST === '') {
    echo "ERROR: SMTP_HOST is empty — would fall back to PHP mail().\n"; exit;
}

// ── Step 1: TCP connection ──────────────────────────────────────────────────
echo "Step 1: Connecting to " . SMTP_HOST . ":" . SMTP_PORT . " ...\n";
$useSSL = (SMTP_PORT === 465);
$socket = @fsockopen(($useSSL ? 'ssl://' : '') . SMTP_HOST, SMTP_PORT, $errno, $errstr, 15);
if (!$socket) {
    echo "FAILED: $errstr ($errno)\n";
    echo "\nLikely cause: your hosting provider blocks outbound port " . SMTP_PORT . ".\n";
    echo "Try port 465 instead, or use your host's own SMTP server.\n";
    exit;
}
echo "OK — connected.\n\n";
stream_set_timeout($socket, 10);

$log = [];
$read = function () use ($socket, &$log): int {
    $code = 0; $full = '';
    while ($line = fgets($socket, 515)) {
        $full .= '  S: ' . rtrim($line) . "\n";
        $code = (int)substr($line, 0, 3);
        if (substr($line, 3, 1) === ' ') break;
    }
    echo $full;
    return $code;
};
$send = function (string $cmd) use ($socket, &$log): void {
    $display = (str_starts_with($cmd, 'AUTH') || strlen($cmd) > 60)
        ? substr($cmd, 0, 12) . '...'
        : $cmd;
    echo "  C: $display\n";
    fputs($socket, $cmd . "\r\n");
};

// ── Step 2: Greeting ────────────────────────────────────────────────────────
echo "Step 2: Greeting\n";
$code = $read();
if ($code !== 220) { echo "FAILED (expected 220, got $code)\n"; fclose($socket); exit; }
echo "OK\n\n";

// ── Step 3: EHLO ────────────────────────────────────────────────────────────
echo "Step 3: EHLO\n";
$ehlo = gethostname() ?: 'localhost';
$send('EHLO ' . $ehlo);
$code = $read();
if ($code !== 250) { echo "FAILED (expected 250, got $code)\n"; fclose($socket); exit; }
echo "OK\n\n";

// ── Step 4: STARTTLS ────────────────────────────────────────────────────────
if (!$useSSL) {
    echo "Step 4: STARTTLS\n";
    $send('STARTTLS');
    $code = $read();
    if ($code !== 220) { echo "FAILED (expected 220, got $code)\n"; fclose($socket); exit; }
    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        echo "FAILED: TLS upgrade failed.\n"; fclose($socket); exit;
    }
    echo "OK — TLS active.\n\n";

    echo "Step 4b: EHLO after TLS\n";
    $send('EHLO ' . $ehlo);
    $code = $read();
    if ($code !== 250) { echo "FAILED (expected 250, got $code)\n"; fclose($socket); exit; }
    echo "OK\n\n";
}

// ── Step 5: AUTH ─────────────────────────────────────────────────────────────
echo "Step 5: AUTH LOGIN\n";
$send('AUTH LOGIN');
$code = $read();
if ($code !== 334) { echo "FAILED (expected 334, got $code)\n"; fclose($socket); exit; }

$send(base64_encode(SMTP_USER));
$code = $read();
if ($code !== 334) { echo "FAILED (expected 334, got $code)\n"; fclose($socket); exit; }

$send(base64_encode(str_replace(' ', '', SMTP_PASS)));
$code = $read();
if ($code !== 235) {
    echo "FAILED (expected 235, got $code)\n";
    echo "\nLikely cause: wrong app password, or 2FA not enabled on Gmail.\n";
    echo "Make sure you generated an App Password at myaccount.google.com → Security → App passwords.\n";
    fclose($socket); exit;
}
echo "OK — authenticated.\n\n";

// ── Step 6: Send test email ──────────────────────────────────────────────────
echo "Step 6: Sending test email to " . ADMIN_EMAIL . "\n";
$send('MAIL FROM: <' . SMTP_FROM . '>');
$code = $read();
if ($code !== 250) { echo "FAILED MAIL FROM (got $code)\n"; fclose($socket); exit; }

$send('RCPT TO: <' . ADMIN_EMAIL . '>');
$code = $read();
if ($code !== 250) { echo "FAILED RCPT TO (got $code)\n"; fclose($socket); exit; }

$send('DATA');
$code = $read();
if ($code !== 354) { echo "FAILED DATA (got $code)\n"; fclose($socket); exit; }

$msg = "From: Risonaf Loans Ghana <" . SMTP_FROM . ">\r\n"
     . "To: " . ADMIN_EMAIL . "\r\n"
     . "Subject: SMTP Test — Risonaf Loans\r\n"
     . "MIME-Version: 1.0\r\n"
     . "Content-Type: text/plain; charset=utf-8\r\n"
     . "\r\n"
     . "This is a test email from your Risonaf Loans diagnostic tool.\r\n"
     . "If you received this, SMTP is working correctly.\r\n"
     . "\r\n.\r\n";

fputs($socket, $msg);
$code = $read();
if ($code !== 250) { echo "FAILED queuing message (got $code)\n"; fclose($socket); exit; }

$send('QUIT');
fclose($socket);

echo "\n=== SUCCESS ===\n";
echo "Test email sent to " . ADMIN_EMAIL . ".\n";
echo "Check your inbox (and spam folder).\n";
