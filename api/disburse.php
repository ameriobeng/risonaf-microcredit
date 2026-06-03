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

$id     = (int)($_POST['id']      ?? 0);
$method = trim((string)($_POST['method']   ?? ''));
$dueDate = trim((string)($_POST['due_date'] ?? ''));

if ($id <= 0 || $method === '' || $dueDate === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Loan ID, disbursement method, and due date are required']);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid due date format (YYYY-MM-DD expected)']);
    exit;
}

$allowedMethods = ['Mobile Money (MTN)', 'Mobile Money (Vodafone)', 'Mobile Money (AirtelTigo)', 'Cash', 'Bank Transfer'];
if (!in_array($method, $allowedMethods, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid disbursement method']);
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare('SELECT id, full_name, status FROM loan_applications WHERE id = ?');
    $stmt->execute([$id]);
    $loan = $stmt->fetch();

    if (!$loan) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Loan not found']);
        exit;
    }

    if ($loan['status'] !== 'Approved') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Only approved loans can be disbursed']);
        exit;
    }

    $pdo->prepare(
        "UPDATE loan_applications
         SET status = 'Disbursed', disbursed_at = NOW(), due_date = ?, disbursement_method = ?
         WHERE id = ?"
    )->execute([$dueDate, $method, $id]);

    // Audit log
    try {
        $admin = $_SESSION['admin_user'] ?? 'admin';
        $pdo->prepare(
            'INSERT INTO audit_log (loan_id, action, details, admin_user) VALUES (?, ?, ?, ?)'
        )->execute([$id, 'Disbursed', "Method: {$method} | Due: {$dueDate}", $admin]);
    } catch (Throwable) {}

    echo json_encode(['success' => true, 'message' => "Loan #{$id} marked as disbursed. Due date: {$dueDate}"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
