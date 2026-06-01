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

    // Check which optional columns exist (added by run_migration.php)
    $cols    = $pdo->query("SHOW COLUMNS FROM loan_applications")->fetchAll(PDO::FETCH_COLUMN);
    $hasNotes = in_array('notes', $cols, true);
    $notesExpr = $hasNotes ? "COALESCE(notes, '') AS notes," : "'' AS notes,";

    $stmt = $pdo->query(
        "SELECT
            id,
            full_name AS fullName,
            phone,
            email,
            location,
            id_type   AS idType,
            id_number AS idNumber,
            loan_type AS loanType,
            amount,
            purpose,
            status,
            {$notesExpr}
            DATE_FORMAT(submitted_at, \"%Y-%m-%d %H:%i:%s\") AS submittedAt
         FROM loan_applications
         ORDER BY submitted_at DESC, id DESC"
    );

    $rows = $stmt->fetchAll();

    $statsStmt = $pdo->query(
        'SELECT
            COUNT(*) AS totalApplications,
            COALESCE(SUM(amount), 0) AS totalAmount,
            SUM(CASE WHEN loan_type = "Personal Loan" THEN 1 ELSE 0 END) AS personalCount,
            SUM(CASE WHEN loan_type = "Business Loan" THEN 1 ELSE 0 END) AS businessCount,
            SUM(CASE WHEN loan_type = "Group Loan" THEN 1 ELSE 0 END) AS groupCount
         FROM loan_applications'
    );
    $stats = $statsStmt->fetch();

    // Monthly breakdown for chart — last 6 months
    $chartStmt = $pdo->query(
        'SELECT
            DATE_FORMAT(submitted_at, "%Y-%m") AS month,
            COUNT(*) AS total,
            SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) AS approved,
            SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) AS rejected
         FROM loan_applications
         WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
         GROUP BY month
         ORDER BY month ASC'
    );
    $chartRows = $chartStmt->fetchAll();

    echo json_encode([
        'success'      => true,
        'applications' => $rows,
        'stats' => [
            'totalApplications' => (int)($stats['totalApplications'] ?? 0),
            'totalAmount'       => (float)($stats['totalAmount'] ?? 0),
            'personalCount'     => (int)($stats['personalCount'] ?? 0),
            'businessCount'     => (int)($stats['businessCount'] ?? 0),
            'groupCount'        => (int)($stats['groupCount'] ?? 0),
        ],
        'chart' => $chartRows,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while fetching applications']);
}
