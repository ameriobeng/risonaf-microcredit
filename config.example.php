<?php
declare(strict_types=1);

/**
 * Copy this file to config.php and fill in your real values.
 * NEVER commit config.php to version control.
 */

/**
 * Admin login credentials.
 * Generate a bcrypt hash with generate_hash.php, then paste it below.
 */
const ADMIN_USERNAME      = 'admin';
const ADMIN_PASSWORD_HASH = ''; // e.g. $2y$12$...

/**
 * Database configuration for MySQL connection.
 */
const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'microcredit_db';
const DB_USER = 'root';
const DB_PASS = '';

/**
 * Email notification settings.
 * Set NOTIFY_EMAIL to receive an alert on new loan submissions.
 * Leave SMTP_HOST empty to use PHP's built-in mail() function.
 */
const ADMIN_EMAIL    = '';        // Admin email for password reset e.g. admin@risonaf.com
const NOTIFY_EMAIL   = '';        // e.g. admin@risonaf.com
const SMTP_HOST      = '';        // e.g. smtp.gmail.com
const SMTP_PORT      = 587;
const SMTP_USER      = '';
const SMTP_PASS      = '';
const SMTP_FROM      = '';
const SMTP_FROM_NAME = 'Risonaf Loans Ghana';

/**
 * Returns a PDO instance for MySQL with safe defaults.
 */
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    return $pdo;
}
