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
$allowed = ['Pending', 'Approved', 'Rejected'];

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
    if ($statusChanged && $app['email'] !== '') {
        $name      = $app['full_name'];
        $loanType  = $app['loan_type'];
        $amount    = number_format((float)$app['amount'], 2);

        $messages = [
            'Approved' => "Congratulations! Your loan application has been approved.\n\n"
                        . "Our team will be in touch shortly with next steps and repayment details.",
            'Rejected' => "We regret to inform you that your loan application has not been approved at this time.\n\n"
                        . "Please contact us for more information or to discuss your options.",
            'Pending'  => "Your loan application is currently under review by our team.\n\n"
                        . "We will notify you once a decision has been made.",
        ];

        $statusLine = $messages[$status] ?? "Your application status has been updated to: {$status}.";

        $body = "Dear {$name},\n\n"
              . $statusLine . "\n\n"
              . "Application Details\n"
              . "-------------------\n"
              . "Reference:  #{$id}\n"
              . "Loan Type:  {$loanType}\n"
              . "Amount:     GHS {$amount}\n"
              . "Status:     {$status}\n\n"
              . "If you have any questions, please contact Risonaf Loans Ghana directly.\n\n"
              . "Thank you for choosing Risonaf Loans Ghana.\n\n"
              . "— The Risonaf Loans Team";

        sendMail($app['email'], "Loan Application #{$id} — Status: {$status}", $body);
    }

    echo json_encode(['success' => true, 'message' => "Application marked as {$status}"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while updating status']);
}
