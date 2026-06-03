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

// Safe empty response — returned when columns/tables don't exist yet
$empty = [
    'success' => true,
    'data'    => [
        'totalDisbursed' => 0, 'totalCollected' => 0,
        'outstanding'    => 0, 'overdueCount'   => 0,
        'overdueAmount'  => 0, 'completedCount' => 0,
        'defaultedCount' => 0, 'activeCount'    => 0,
    ],
];

try {
    $pdo = getPDO();

    // Check which optional columns exist before querying them
    $cols        = $pdo->query("SHOW COLUMNS FROM loan_applications")->fetchAll(PDO::FETCH_COLUMN);
    $hasDueDate  = in_array('due_date', $cols, true);

    // Portfolio totals — new status values simply won't match on older ENUM, sum returns 0
    $portfolio = $pdo->query(
        "SELECT
            COALESCE(SUM(CASE WHEN status IN ('Disbursed','Repaying','Completed','Defaulted') THEN amount ELSE 0 END), 0) AS total_disbursed,
            COALESCE(SUM(CASE WHEN status IN ('Disbursed','Repaying','Defaulted') THEN amount * 1.20 ELSE 0 END), 0)      AS active_repayable,
            COUNT(CASE WHEN status = 'Completed' THEN 1 END) AS completed_count,
            COUNT(CASE WHEN status = 'Defaulted' THEN 1 END) AS defaulted_count,
            COUNT(CASE WHEN status IN ('Disbursed','Repaying') THEN 1 END) AS active_count
         FROM loan_applications"
    )->fetch();

    // Total collected — repayments table may not exist yet
    $collected = 0.0;
    try {
        $collected = (float)$pdo->query(
            'SELECT COALESCE(SUM(amount), 0) FROM repayments'
        )->fetchColumn();
    } catch (Throwable) {}

    // Overdue — only query if due_date column exists
    $overdueCount  = 0;
    $overdueAmount = 0.0;
    if ($hasDueDate) {
        try {
            $overdueRow = $pdo->query(
                "SELECT COUNT(*) AS cnt,
                        COALESCE(SUM(la.amount * 1.20 - COALESCE(paid.total, 0)), 0) AS amt
                 FROM loan_applications la
                 LEFT JOIN (SELECT loan_id, SUM(amount) AS total FROM repayments GROUP BY loan_id) paid
                        ON paid.loan_id = la.id
                 WHERE la.due_date IS NOT NULL
                   AND la.due_date < CURDATE()
                   AND la.status NOT IN ('Completed','Rejected','Defaulted')"
            )->fetch();
            $overdueCount  = (int)($overdueRow['cnt']  ?? 0);
            $overdueAmount = (float)($overdueRow['amt'] ?? 0);
        } catch (Throwable) {}
    }

    $outstanding = max(0.0, (float)$portfolio['active_repayable'] - $collected);

    echo json_encode([
        'success' => true,
        'data'    => [
            'totalDisbursed' => (float)$portfolio['total_disbursed'],
            'totalCollected' => $collected,
            'outstanding'    => $outstanding,
            'overdueCount'   => $overdueCount,
            'overdueAmount'  => $overdueAmount,
            'completedCount' => (int)$portfolio['completed_count'],
            'defaultedCount' => (int)$portfolio['defaulted_count'],
            'activeCount'    => (int)$portfolio['active_count'],
        ],
    ]);
} catch (Throwable $e) {
    // If even the basic portfolio query fails, return zeroes rather than a 500
    // so the admin dashboard still loads. Log internally but don't expose to client.
    error_log('[Risonaf] get_financial_summary error: ' . $e->getMessage());
    echo json_encode($empty);
}
