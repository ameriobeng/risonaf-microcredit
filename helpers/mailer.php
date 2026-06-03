<?php
declare(strict_types=1);

/**
 * Sends a plain-text email via SMTP (fsockopen) or PHP mail().
 * Configure SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS in config.php.
 */
function sendMail(string $to, string $subject, string $body): bool
{
    if ($to === '') {
        return false;
    }

    // Strip newlines from header fields to prevent SMTP header injection
    $subject = str_replace(["\r", "\n"], ' ', $subject);
    $to      = str_replace(["\r", "\n"], '',  $to);

    // Use PHP mail() if no SMTP host configured
    if (SMTP_HOST === '') {
        $headers  = "From: " . SMTP_FROM_NAME . " <" . (SMTP_FROM ?: 'noreply@localhost') . ">\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        return mail($to, $subject, $body, $headers);
    }

    // SMTP via fsockopen.
    // Port 465 = direct SSL (SMTPS).  Port 587 = plain connect then STARTTLS.
    try {
        $useSSL = (SMTP_PORT === 465);
        $socket = fsockopen(
            ($useSSL ? 'ssl://' : '') . SMTP_HOST,
            SMTP_PORT,
            $errno, $errstr, 15
        );
        if (!$socket) return false;

        // Returns the numeric SMTP response code (e.g. 250, 535).
        $read = function () use ($socket): int {
            $code = 0;
            while ($line = fgets($socket, 515)) {
                $code = (int)substr($line, 0, 3);
                if (substr($line, 3, 1) === ' ') break;
            }
            return $code;
        };

        $send = function (string $cmd) use ($socket): void {
            fputs($socket, $cmd . "\r\n");
        };

        // Helper: abort if the server returned an unexpected code.
        $expect = function (int $code, int $expected) use ($socket): bool {
            if ($code !== $expected) {
                fclose($socket);
                return false;
            }
            return true;
        };

        $ehlo = SMTP_FROM ?: gethostname() ?: 'localhost';

        if (!$expect($read(), 220)) return false; // greeting
        $send('EHLO ' . $ehlo);
        if ($read() < 200 || $read() > 299) {} // EHLO may return multi-line 250; just consume

        // Upgrade to TLS on port 587 via STARTTLS
        if (!$useSSL) {
            $send('STARTTLS');
            if (!$expect($read(), 220)) return false;
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket);
                return false;
            }
            $send('EHLO ' . $ehlo);
            $read(); // consume post-TLS EHLO response
        }

        $send('AUTH LOGIN');
        $read(); // 334 (send username)
        $send(base64_encode(SMTP_USER));
        $read(); // 334 (send password)
        $send(base64_encode(SMTP_PASS));
        if (!$expect($read(), 235)) return false; // 235 = auth success; 535 = auth failed

        $send('MAIL FROM: <' . SMTP_FROM . '>');
        if (!$expect($read(), 250)) return false;
        $send('RCPT TO: <' . $to . '>');
        if (!$expect($read(), 250)) return false;
        $send('DATA');
        if (!$expect($read(), 354)) return false;

        $from    = SMTP_FROM_NAME . ' <' . SMTP_FROM . '>';
        $message = "From: {$from}\r\n"
                 . "To: {$to}\r\n"
                 . "Subject: {$subject}\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-Type: text/plain; charset=utf-8\r\n"
                 . "\r\n"
                 . $body . "\r\n.\r\n";

        fputs($socket, $message);
        $read();
        $send('QUIT');
        fclose($socket);
        return true;
    } catch (Throwable $e) {
        return false;
    }
}
