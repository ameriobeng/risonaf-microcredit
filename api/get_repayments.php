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

$loanId = (int)($_GET['loan_id'] ?? 0);
if ($loanId <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid loan ID']);
    exit;
}

try {
    $pdo = getPDO();

    $loanStmt = $pdo->prepare('SELECT id, full_name, loan_type, amount FROM loan_applications WHERE id = ?');
    $loanStmt->execute([$loanId]);
    $loan = $loanStmt->fetch();

    if (!$loan) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Loan not found']);
        exit;
    }

    $repStmt = $pdo->prepare(
        'SELECT id, amount, note, DATE_FORMAT(recorded_at, "%Y-%m-%d %H:%i") AS recordedAt
         FROM repayments WHERE loan_id = ? ORDER BY recorded_at DESC'
    );
    $repStmt->execute([$loanId]);
    $repayments = $repStmt->fetchAll();

    $totalPaid = array_sum(array_column($repayments, 'amount'));

    echo json_encode([
        'success'    => true,
        'loan'       => $loan,
        'repayments' => $repayments,
        'totalPaid'  => (float)$totalPaid,
        'outstanding'=> (float)$loan['amount'] - (float)$totalPaid,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
