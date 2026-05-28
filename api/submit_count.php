<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

try {
    $pdo  = getPDO();
    $stmt = $pdo->query('SELECT COUNT(*) AS cnt FROM loan_applications');
    $row  = $stmt->fetch();
    echo json_encode(['success' => true, 'count' => (int)($row['cnt'] ?? 0)]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'count' => 0]);
}
