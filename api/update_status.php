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

$id      = (int)($_POST['id']     ?? 0);
$status  = trim((string)($_POST['status'] ?? ''));
$allowed = ['Pending', 'Approved', 'Rejected', 'Disbursed', 'Repaying', 'Completed', 'Defaulted'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid id or status']);
    exit;
}

try {
    $pdo = getPDO();

    // Fetch applicant details before updating so we can email them
    $appStmt = $pdo->prepare(
        'SELECT full_name, email, loan_type, amount, status FROM loan_applications WHERE id = ?'
    );
    $appStmt->execute([$id]);
    $app = $appStmt->fetch();

    if (!$app) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Application not found']);
        exit;
    }

    // Only update (and email) if the status actually changed
    $statusChanged = $app['status'] !== $status;

    $stmt = $pdo->prepare('UPDATE loan_applications SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $id]);

    // Email the applicant when their status changes
    if ($statusChanged && !empty($app['email'])) {
        $name     = $app['full_name'];
        $loanType = $app['loan_type'];
        $amount   = number_format((float)$app['amount'], 2);

        $messages = [
            'Approved'  => "Congratulations, {$name}!\n\n"
                         . "Your loan application has been approved. Our team will contact you shortly with next steps and disbursement details.",
            'Rejected'  => "Dear {$name},\n\n"
                         . "After careful review, we regret to inform you that your loan application has not been approved at this time.\n\n"
                         . "Please contact us for more information or to discuss your options.",
            'Pending'   => "Dear {$name},\n\n"
                         . "Your loan application is currently under review by our team. We will notify you once a decision has been made.",
            'Disbursed' => "Dear {$name},\n\n"
                         . "Your loan has been disbursed! The funds have been sent to you via your chosen method.\n\n"
                         . "Your repayment schedule has been set. Please ensure full repayment by the due date to avoid late fees.",
            'Repaying'  => "Dear {$name},\n\n"
                         . "Thank you for your payment. Your loan is now in repayment. Keep up the good work!",
            'Completed' => "Dear {$name},\n\n"
                         . "Congratulations! Your loan has been fully repaid. Thank you for choosing Risonaf Loans Ghana.\n\n"
                         . "We look forward to serving you again in the future.",
            'Defaulted' => "Dear {$name},\n\n"
                         . "Your loan account has been marked as defaulted due to non-payment past the due date.\n\n"
                         . "Please contact us immediately to discuss a repayment arrangement and avoid further action.",
        ];

        $statusLine = $messages[$status] ?? "Dear {$name},\n\nYour application status has been updated to: {$status}.";

        $body = $statusLine . "\n\n"
              . "Loan Details\n"
              . "------------\n"
              . "Reference:  #{$id}\n"
              . "Loan Type:  {$loanType}\n"
              . "Amount:     GHS {$amount}\n"
              . "Status:     {$status}\n\n"
              . "To check your application at any time, visit our website and use the Track Application page.\n\n"
              . "If you have any questions, please contact Risonaf Loans Ghana directly.\n\n"
              . "— The Risonaf Loans Team";

        sendMail($app['email'], "Loan Update — {$status} | Reference #{$id}", $body);
    }

    // Audit log
    try {
        $admin = $_SESSION['admin_user'] ?? 'admin';
        $pdo->prepare('INSERT INTO audit_log (loan_id, action, details, admin_user) VALUES (?, ?, ?, ?)')
            ->execute([$id, 'Status Updated', "Changed to: {$status}", $admin]);
    } catch (Throwable) {}

    echo json_encode(['success' => true, 'message' => "Application marked as {$status}"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while updating status']);
}
