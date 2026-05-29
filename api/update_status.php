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

$id     = (int)($_POST['id'] ?? 0);
$status = trim((string)($_POST['status'] ?? ''));
$allowed = ['Pending', 'Approved', 'Rejected'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid id or status']);
    exit;
}

try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare('UPDATE loan_applications SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $id]);

    echo json_encode(['success' => true, 'message' => "Application marked as {$status}"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while updating status']);
}
