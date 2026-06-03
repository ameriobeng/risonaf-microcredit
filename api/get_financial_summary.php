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

try {
    $pdo = getPDO();

    // Portfolio totals
    $portfolioStmt = $pdo->query(
        "SELECT
            COALESCE(SUM(CASE WHEN status IN ('Disbursed','Repaying','Completed','Defaulted') THEN amount ELSE 0 END), 0) AS total_disbursed,
            COALESCE(SUM(CASE WHEN status IN ('Disbursed','Repaying','Defaulted') THEN amount * 1.20 ELSE 0 END), 0)         AS active_repayable,
            COUNT(CASE WHEN status = 'Completed'  THEN 1 END) AS completed_count,
            COUNT(CASE WHEN status = 'Defaulted'  THEN 1 END) AS defaulted_count,
            COUNT(CASE WHEN status IN ('Disbursed','Repaying') THEN 1 END) AS active_count
         FROM loan_applications"
    );
    $portfolio = $portfolioStmt->fetch();

    // Total collected from repayments
    $collectedStmt = $pdo->query('SELECT COALESCE(SUM(amount), 0) AS total_collected FROM repayments');
    $collected     = (float)$collectedStmt->fetchColumn();

    // Overdue loans
    $overdueStmt = $pdo->query(
        "SELECT COUNT(*) AS cnt,
                COALESCE(SUM(la.amount * 1.20 - COALESCE(paid.total, 0)), 0) AS amount
         FROM loan_applications la
         LEFT JOIN (SELECT loan_id, SUM(amount) AS total FROM repayments GROUP BY loan_id) paid
                ON paid.loan_id = la.id
         WHERE la.due_date IS NOT NULL
           AND la.due_date < CURDATE()
           AND la.status NOT IN ('Completed','Rejected','Defaulted')"
    );
    $overdue = $overdueStmt->fetch();

    $activeRepayable = (float)$portfolio['active_repayable'];
    $outstanding     = max(0.0, $activeRepayable - $collected);

    echo json_encode([
        'success' => true,
        'data' => [
            'totalDisbursed'  => (float)$portfolio['total_disbursed'],
            'totalCollected'  => $collected,
            'outstanding'     => $outstanding,
            'overdueCount'    => (int)$overdue['cnt'],
            'overdueAmount'   => (float)$overdue['amount'],
            'completedCount'  => (int)$portfolio['completed_count'],
            'defaultedCount'  => (int)$portfolio['defaulted_count'],
            'activeCount'     => (int)$portfolio['active_count'],
        ],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
