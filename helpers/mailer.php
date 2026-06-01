<?php
declare(strict_types=1);

/**
 * Sends a plain-text email via SMTP (fsockopen) or PHP mail().
 * Configure SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS in config.php.
 */
function sendMail(string $to, string $subject, string $body): bool
{
    if (NOTIFY_EMAIL === '' || $to === '') {
        return false; // Notifications disabled
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

    // SMTP via fsockopen (works with Gmail, Mailtrap, etc.)
    try {
        $socket = fsockopen('ssl://' . SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
        if (!$socket) return false;

        $read = function () use ($socket): string {
            $res = '';
            while ($line = fgets($socket, 515)) {
                $res .= $line;
                if (substr($line, 3, 1) === ' ') break;
            }
            return $res;
        };

        $send = function (string $cmd) use ($socket): void {
            fputs($socket, $cmd . "\r\n");
        };

        $read(); // 220 greeting
        $send('EHLO ' . (SMTP_FROM ?: 'localhost'));
        $read();
        $send('AUTH LOGIN');
        $read();
        $send(base64_encode(SMTP_USER));
        $read();
        $send(base64_encode(SMTP_PASS));
        $read();
        $send('MAIL FROM: <' . SMTP_FROM . '>');
        $read();
        $send('RCPT TO: <' . $to . '>');
        $read();
        $send('DATA');
        $read();

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
