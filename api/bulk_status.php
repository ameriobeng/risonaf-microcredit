<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$rawIds = $_POST['ids'] ?? [];
$status  = trim((string)($_POST['status'] ?? ''));
$allowed = ['Pending', 'Approved', 'Rejected'];

if (!is_array($rawIds) || count($rawIds) === 0 || !in_array($status, $allowed, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Valid IDs array and status are required']);
    exit;
}

$ids = array_values(array_filter(array_map('intval', $rawIds), fn($id) => $id > 0));
if (empty($ids)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'No valid IDs provided']);
    exit;
}

try {
    $pdo          = getPDO();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $params       = array_merge([$status], $ids);

    $pdo->prepare("UPDATE loan_applications SET status = ? WHERE id IN ({$placeholders})")->execute($params);

    $admin = $_SESSION['admin_user'] ?? 'admin';
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS audit_log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                loan_id INT DEFAULT NULL,
                action VARCHAR(100) NOT NULL,
                details TEXT DEFAULT NULL,
                admin_user VARCHAR(100) DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_loan (loan_id),
                INDEX idx_time (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        foreach ($ids as $id) {
            $pdo->prepare('INSERT INTO audit_log (loan_id, action, details, admin_user) VALUES (?, ?, ?, ?)')
                ->execute([$id, 'Bulk Status Update', "Set to: {$status}", $admin]);
        }
    } catch (Throwable) {}

    $count = count($ids);
    echo json_encode(['success' => true, 'message' => "{$count} application(s) updated to {$status}"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
