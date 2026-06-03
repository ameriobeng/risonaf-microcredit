<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/mailer.php';

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

    // Ensure repayments table exists
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

    // Auto-update lifecycle status based on total paid
    try {
        $loanStmt = $pdo->prepare('SELECT amount, status FROM loan_applications WHERE id = ?');
        $loanStmt->execute([$loanId]);
        $loanRow = $loanStmt->fetch();

        if ($loanRow) {
            $totalRepayable = (float)$loanRow['amount'] * 1.20;
            $paidStmt       = $pdo->prepare('SELECT COALESCE(SUM(amount),0) FROM repayments WHERE loan_id = ?');
            $paidStmt->execute([$loanId]);
            $totalPaid  = (float)$paidStmt->fetchColumn();
            $newStatus  = $loanRow['status'];

            if ($loanRow['status'] === 'Disbursed') $newStatus = 'Repaying';
            if ($totalPaid >= $totalRepayable)        $newStatus = 'Completed';

            if ($newStatus !== $loanRow['status']) {
                $pdo->prepare('UPDATE loan_applications SET status = ? WHERE id = ?')
                    ->execute([$newStatus, $loanId]);

                // Email applicant on completion
                if ($newStatus === 'Completed') {
                    $emailStmt = $pdo->prepare('SELECT full_name, email, loan_type, amount FROM loan_applications WHERE id = ?');
                    $emailStmt->execute([$loanId]);
                    $emailRow = $emailStmt->fetch();
                    if ($emailRow && !empty($emailRow['email'])) {
                        $amt  = number_format((float)$emailRow['amount'], 2);
                        $body = "Dear {$emailRow['full_name']},\n\n"
                              . "Congratulations! Your loan has been fully repaid.\n\n"
                              . "Loan Details\n"
                              . "------------\n"
                              . "Reference:  #{$loanId}\n"
                              . "Loan Type:  {$emailRow['loan_type']}\n"
                              . "Amount:     GHS {$amt}\n"
                              . "Status:     Completed\n\n"
                              . "Thank you for your prompt repayments. We look forward to serving you again.\n\n"
                              . "— The Risonaf Loans Team";
                        sendMail($emailRow['email'], "Loan Fully Repaid — Reference #{$loanId}", $body);
                    }
                }
            }
        }
    } catch (Throwable) {}

    // Audit log
    try {
        $admin = $_SESSION['admin_user'] ?? 'admin';
        $pdo->prepare('INSERT INTO audit_log (loan_id, action, details, admin_user) VALUES (?, ?, ?, ?)')
            ->execute([$loanId, 'Repayment Recorded', 'GHS ' . number_format($amount, 2), $admin]);
    } catch (Throwable) {}

    echo json_encode(['success' => true, 'message' => 'Repayment recorded']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
