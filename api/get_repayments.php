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

    // Ensure the repayments table exists (safe to call on every request — IF NOT EXISTS is a no-op)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS repayments (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            loan_id     INT NOT NULL,
            amount      DECIMAL(12,2) NOT NULL,
            note        VARCHAR(500) DEFAULT NULL,
            recorded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_loan (loan_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Fetch loan + optional lifecycle columns
    $cols       = $pdo->query("SHOW COLUMNS FROM loan_applications")->fetchAll(PDO::FETCH_COLUMN);
    $hasDueDate = in_array('due_date', $cols, true);
    $dueDateCol = $hasDueDate ? ", DATE_FORMAT(due_date, '%Y-%m-%d') AS due_date" : ", NULL AS due_date";

    $loanStmt = $pdo->prepare(
        "SELECT id, full_name, loan_type, amount, status {$dueDateCol}
         FROM loan_applications WHERE id = ?"
    );
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

    $totalPaid      = (float)array_sum(array_column($repayments, 'amount'));
    $totalRepayable = (float)$loan['amount'] * 1.20;  // principal + 20% flat interest
    $outstanding    = max(0.0, $totalRepayable - $totalPaid);

    // Late fee: 5% per month on outstanding balance, calculated from due_date
    $lateFee      = 0.0;
    $monthsOverdue = 0;
    if (!empty($loan['due_date']) && $outstanding > 0) {
        $due   = new DateTime($loan['due_date']);
        $today = new DateTime('today');
        if ($today > $due) {
            $diff          = $today->diff($due);
            $monthsOverdue = (int)ceil(($diff->days) / 30);
            $lateFee       = round($outstanding * 0.05 * $monthsOverdue, 2);
        }
    }

    echo json_encode([
        'success'        => true,
        'loan'           => $loan,
        'repayments'     => $repayments,
        'totalPaid'      => $totalPaid,
        'totalRepayable' => $totalRepayable,
        'outstanding'    => $outstanding,
        'lateFee'        => $lateFee,
        'monthsOverdue'  => $monthsOverdue,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
