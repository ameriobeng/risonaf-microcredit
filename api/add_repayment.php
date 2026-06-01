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

$loanId    = (int)($_POST['loan_id'] ?? 0);
$amountRaw = trim((string)($_POST['amount'] ?? ''));
$note      = trim((string)($_POST['note']   ?? ''));

if ($loanId <= 0 || $amountRaw === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Loan ID and amount are required']);
    exit;
}

$amount = (float)$amountRaw;
if ($amount <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Amount must be greater than zero']);
    exit;
}

try {
    $pdo = getPDO();

    $check = $pdo->prepare('SELECT id FROM loan_applications WHERE id = ?');
    $check->execute([$loanId]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Loan application not found']);
        exit;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO repayments (loan_id, amount, note) VALUES (:loan_id, :amount, :note)'
    );
    $stmt->execute([
        ':loan_id' => $loanId,
        ':amount'  => $amount,
        ':note'    => $note !== '' ? $note : null,
    ]);

    echo json_encode(['success' => true, 'message' => 'Repayment recorded']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while recording repayment']);
}
