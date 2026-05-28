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
