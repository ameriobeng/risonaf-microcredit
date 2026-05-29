<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config.php';

$filename = 'loan-applications-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');

try {
    $pdo  = getPDO();
    $stmt = $pdo->query(
        'SELECT
            id,
            full_name,
            phone,
            email,
            location,
            loan_type,
            amount,
            purpose,
            status,
            DATE_FORMAT(submitted_at, "%Y-%m-%d %H:%i:%s") AS submitted_at
         FROM loan_applications
         ORDER BY submitted_at DESC'
    );

    $out = fopen('php://output', 'w');

    // BOM for Excel UTF-8 compatibility
    fputs($out, "\xEF\xBB\xBF");

    // Header row
    fputcsv($out, ['ID', 'Full Name', 'Phone', 'Email', 'Location', 'Loan Type', 'Amount (GHS)', 'Purpose', 'Status', 'Submitted At']);

    while ($row = $stmt->fetch()) {
        fputcsv($out, [
            $row['id'],
            $row['full_name'],
            $row['phone'],
            $row['email'],
            $row['location'],
            $row['loan_type'],
            $row['amount'],
            $row['purpose'],
            $row['status'],
            $row['submitted_at'],
        ]);
    }

    fclose($out);
} catch (Throwable $e) {
    // Can't send JSON here since headers are set — output plain error
    echo "Error exporting data.";
}
