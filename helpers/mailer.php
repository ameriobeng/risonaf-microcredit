<?php
declare(strict_types=1);

/**
 * Sends a plain-text email via SMTP (fsockopen) or PHP mail().
 * Configure SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS in config.php.
 *
 * Port 465 = direct SSL (SMTPS).
 * Port 587 = plain connect then STARTTLS — used by Gmail.
 */
function sendMail(string $to, string $subject, string $body): bool
{
    if ($to === '') return false;

    // Strip newlines to prevent SMTP header injection
    $subject = str_replace(["\r", "\n"], ' ', $subject);
    $to      = str_replace(["\r", "\n"], '',  $to);

    // Fall back to PHP mail() if no SMTP host is configured
    if (SMTP_HOST === '') {
        $headers  = "From: " . SMTP_FROM_NAME . " <" . (SMTP_FROM ?: 'noreply@localhost') . ">\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        return mail($to, $subject, $body, $headers);
    }

    try {
        $useSSL = (SMTP_PORT === 465);
        $socket = fsockopen(
            ($useSSL ? 'ssl://' : '') . SMTP_HOST,
            SMTP_PORT,
            $errno, $errstr, 15
        );
        if (!$socket) return false;

        stream_set_timeout($socket, 10); // cap each individual read at 10 s

        /**
         * Read one complete SMTP response (handles multi-line like EHLO 250-...).
         * Returns the 3-digit numeric code from the final line.
         */
        $read = function () use ($socket): int {
            $code = 0;
            while ($line = fgets($socket, 515)) {
                $code = (int)substr($line, 0, 3);
                if (substr($line, 3, 1) === ' ') break; // space = last line of response
            }
            return $code;
        };

        $send = function (string $cmd) use ($socket): void {
            fputs($socket, $cmd . "\r\n");
        };

        $ehlo = gethostname() ?: 'localhost'; // must be a hostname, not an email address

        // --- Greeting ---
        if ($read() !== 220) { fclose($socket); return false; }

        // --- EHLO ---
        $send('EHLO ' . $ehlo);
        if ($read() !== 250) { fclose($socket); return false; }

        // --- STARTTLS (port 587 only) ---
        if (!$useSSL) {
            $send('STARTTLS');
            if ($read() !== 220) { fclose($socket); return false; }

            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket); return false;
            }

            // Re-introduce after TLS upgrade
            $send('EHLO ' . $ehlo);
            if ($read() !== 250) { fclose($socket); return false; }
        }

        // --- AUTH LOGIN ---
        $send('AUTH LOGIN');
        if ($read() !== 334) { fclose($socket); return false; } // "Username:"

        $send(base64_encode(SMTP_USER));
        if ($read() !== 334) { fclose($socket); return false; } // "Password:"

        // Remove spaces from app password (Google displays them in groups of 4)
        $send(base64_encode(str_replace(' ', '', SMTP_PASS)));
        if ($read() !== 235) { fclose($socket); return false; } // 235 = authenticated

        // --- Envelope ---
        $send('MAIL FROM: <' . SMTP_FROM . '>');
        if ($read() !== 250) { fclose($socket); return false; }

        $send('RCPT TO: <' . $to . '>');
        if ($read() !== 250) { fclose($socket); return false; }

        // --- Body ---
        $send('DATA');
        if ($read() !== 354) { fclose($socket); return false; }

        $from    = SMTP_FROM_NAME . ' <' . SMTP_FROM . '>';
        $message = "From: {$from}\r\n"
                 . "To: {$to}\r\n"
                 . "Subject: {$subject}\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-Type: text/plain; charset=utf-8\r\n"
                 . "\r\n"
                 . $body . "\r\n.\r\n";

        fputs($socket, $message);
        $read(); // 250 queued
        $send('QUIT');
        fclose($socket);
        return true;

    } catch (Throwable $e) {
        return false;
    }
}
