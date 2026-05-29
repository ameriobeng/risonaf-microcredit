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

    $stmt = $pdo->query(
        'SELECT 
            id,
            full_name AS fullName,
            phone,
            email,
            location,
            loan_type AS loanType,
            amount,
            purpose,
            status,
            DATE_FORMAT(submitted_at, "%Y-%m-%d %H:%i:%s") AS submittedAt
         FROM loan_applications
         ORDER BY submitted_at DESC, id DESC'
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

    echo json_encode([
        'success' => true,
        'applications' => $rows,
        'stats' => [
            'totalApplications' => (int)($stats['totalApplications'] ?? 0),
            'totalAmount' => (float)($stats['totalAmount'] ?? 0),
            'personalCount' => (int)($stats['personalCount'] ?? 0),
            'businessCount' => (int)($stats['businessCount'] ?? 0),
            'groupCount' => (int)($stats['groupCount'] ?? 0),
        ],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while fetching applications']);
}
