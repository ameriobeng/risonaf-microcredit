<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $pdo = getPDO();

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS audit_log (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            loan_id    INT DEFAULT NULL,
            action     VARCHAR(100) NOT NULL,
            details    TEXT DEFAULT NULL,
            admin_user VARCHAR(100) DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_loan (loan_id),
            INDEX idx_time (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $limit = min((int)($_GET['limit'] ?? 50), 200);
    $stmt  = $pdo->prepare(
        'SELECT id, loan_id, action, details, admin_user,
                DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") AS createdAt
         FROM audit_log ORDER BY created_at DESC LIMIT ?'
    );
    $stmt->execute([$limit]);

    echo json_encode(['success' => true, 'logs' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
