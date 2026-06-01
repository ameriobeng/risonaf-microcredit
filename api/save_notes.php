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

// CSRF
session_start();
$token = trim((string)($_POST['csrf_token'] ?? ''));
if ($token === '' || !hash_equals((string)($_SESSION['csrf_token'] ?? ''), $token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$id    = (int)($_POST['id']    ?? 0);
$notes = trim((string)($_POST['notes'] ?? ''));

if ($id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid application ID']);
    exit;
}

try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare('UPDATE loan_applications SET notes = :notes WHERE id = :id');
    $stmt->execute([':notes' => $notes !== '' ? $notes : null, ':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'Notes saved']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while saving notes']);
}
