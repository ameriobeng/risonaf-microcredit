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

try {
    $pdo = getPDO();

    // Loans due within the next 7 days (or already overdue) with outstanding balance
    $stmt = $pdo->query(
        "SELECT la.id, la.full_name, la.email, la.loan_type, la.amount,
                la.due_date, la.status,
                COALESCE(SUM(r.amount), 0) AS total_paid,
                DATEDIFF(la.due_date, CURDATE()) AS days_left
         FROM loan_applications la
         LEFT JOIN repayments r ON r.loan_id = la.id
         WHERE la.due_date IS NOT NULL
           AND la.due_date >= CURDATE() - INTERVAL 30 DAY
           AND la.due_date <= CURDATE() + INTERVAL 7 DAY
           AND la.status NOT IN ('Completed','Rejected','Defaulted')
         GROUP BY la.id
         HAVING (la.amount * 1.20 - COALESCE(SUM(r.amount), 0)) > 0"
    );
    $loans = $stmt->fetchAll();

    $sent   = 0;
    $failed = 0;

    foreach ($loans as $loan) {
        $totalRepayable = (float)$loan['amount'] * 1.20;
        $outstanding    = $totalRepayable - (float)$loan['total_paid'];
        $daysLeft       = (int)$loan['days_left'];

        if ($daysLeft < 0) {
            $timing = "OVERDUE by " . abs($daysLeft) . " day(s)";
        } elseif ($daysLeft === 0) {
            $timing = "DUE TODAY";
        } else {
            $timing = "due in {$daysLeft} day(s) on {$loan['due_date']}";
        }

        $body = "Dear {$loan['full_name']},\n\n"
              . "This is a reminder that your Risonaf Loans repayment is {$timing}.\n\n"
              . "Loan Summary\n"
              . "------------\n"
              . "Reference:   #{$loan['id']}\n"
              . "Loan Type:   {$loan['loan_type']}\n"
              . "Due Date:    {$loan['due_date']}\n"
              . "Outstanding: GHS " . number_format($outstanding, 2) . "\n\n"
              . ($daysLeft < 0
                    ? "Your loan is overdue. A late repayment fee of 5% per month is now accruing on the outstanding balance. Please make payment immediately to avoid further charges.\n\n"
                    : "Please ensure payment is made on or before your due date to avoid a 5% per month late repayment fee.\n\n")
              . "Track your application status at any time on our website.\n\n"
              . "— Risonaf Loans Ghana";

        $subject = $daysLeft < 0
            ? "OVERDUE: Loan Repayment — Reference #{$loan['id']}"
            : "Repayment Reminder — Due {$loan['due_date']} — Reference #{$loan['id']}";

        if (sendMail($loan['email'], $subject, $body)) {
            $sent++;
        } else {
            $failed++;
        }
    }

    // Audit
    try {
        $admin = $_SESSION['admin_user'] ?? 'admin';
        $pdo->prepare('INSERT INTO audit_log (loan_id, action, details, admin_user) VALUES (NULL, ?, ?, ?)')
            ->execute(['Reminders Sent', "Sent {$sent}, failed {$failed}, out of " . count($loans) . " qualifying loans", $admin]);
    } catch (Throwable) {}

    echo json_encode([
        'success' => true,
        'message' => "{$sent} reminder(s) sent" . ($failed ? ", {$failed} failed (check SMTP config)" : '') . " — " . count($loans) . " loan(s) qualifying",
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
