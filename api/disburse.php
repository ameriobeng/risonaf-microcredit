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

    // Fetch full applicant details for the email
    $fullStmt = $pdo->prepare('SELECT full_name, email, loan_type, amount FROM loan_applications WHERE id = ?');
    $fullStmt->execute([$id]);
    $full = $fullStmt->fetch();

    $pdo->prepare(
        "UPDATE loan_applications
         SET status = 'Disbursed', disbursed_at = NOW(), due_date = ?, disbursement_method = ?
         WHERE id = ?"
    )->execute([$dueDate, $method, $id]);

    // Email applicant
    if (!empty($full['email'])) {
        $amount   = number_format((float)$full['amount'], 2);
        $monthly  = number_format((float)$full['amount'] * 1.20 / 3, 2);
        $body = "Dear {$full['full_name']},\n\n"
              . "Great news! Your loan has been disbursed.\n\n"
              . "Loan Details\n"
              . "------------\n"
              . "Reference:    #{$id}\n"
              . "Loan Type:    {$full['loan_type']}\n"
              . "Amount:       GHS {$amount}\n"
              . "Method:       {$method}\n"
              . "Due Date:     {$dueDate}\n\n"
              . "Repayment Schedule\n"
              . "------------------\n"
              . "Monthly Payment: GHS {$monthly} (3 equal instalments)\n"
              . "Final Due Date:  {$dueDate}\n\n"
              . "Please ensure timely repayment to avoid a 5% per month late repayment fee.\n\n"
              . "Track your application status on our website at any time.\n\n"
              . "— The Risonaf Loans Team";
        sendMail($full['email'], "Loan Disbursed — Reference #{$id}", $body);
    }

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
