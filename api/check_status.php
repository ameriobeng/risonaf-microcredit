<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$phone    = trim((string)($_GET['phone']     ?? ''));
$idNumber = trim((string)($_GET['id_number'] ?? ''));

if ($phone === '' || $idNumber === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Phone and ID number are required']);
    exit;
}

// Basic phone format check to prevent enumeration via malformed input
if (!preg_match('/^(0\d{9}|\+233\d{9})$/', $phone)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Enter a valid Ghana phone number']);
    exit;
}

try {
    $pdo  = getPDO();
    // Build SELECT safely — lifecycle columns may not exist on older installs
    $cols        = $pdo->query("SHOW COLUMNS FROM loan_applications")->fetchAll(PDO::FETCH_COLUMN);
    $hasDisburse = in_array('disbursed_at', $cols, true);
    $extraCols   = $hasDisburse
        ? "DATE_FORMAT(disbursed_at, '%Y-%m-%d') AS disbursedAt,
           DATE_FORMAT(due_date, '%Y-%m-%d') AS dueDate,
           disbursement_method AS disbursementMethod,"
        : "NULL AS disbursedAt, NULL AS dueDate, NULL AS disbursementMethod,";

    $stmt = $pdo->prepare(
        "SELECT id,
                full_name AS fullName,
                loan_type AS loanType,
                amount,
                status,
                DATE_FORMAT(submitted_at, '%Y-%m-%d %H:%i') AS submittedAt,
                {$extraCols}
                1 AS _placeholder
         FROM loan_applications
         WHERE phone = ? AND id_number = ?
         ORDER BY submitted_at DESC
         LIMIT 1"
    );
    $stmt->execute([$phone, $idNumber]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'No application found matching those details.']);
        exit;
    }

    // Fetch repayments for this application
    $totalPaid   = 0.0;
    $outstanding = (float)$row['amount'];
    $repayments  = [];

    try {
        $repStmt = $pdo->prepare(
            'SELECT amount, DATE_FORMAT(recorded_at, "%d %b %Y") AS recordedAt
             FROM repayments WHERE loan_id = ? ORDER BY recorded_at ASC'
        );
        $repStmt->execute([$row['id']]);
        $rawReps    = $repStmt->fetchAll();
        $repayments = array_map(fn($r) => ['amount' => (float)$r['amount'], 'recordedAt' => $r['recordedAt']], $rawReps);
        $totalPaid  = (float)array_sum(array_column($rawReps, 'amount'));
        $outstanding = max(0.0, (float)$row['amount'] * 1.20 - $totalPaid);
    } catch (Throwable $e) {
        // repayments table may not exist on older installs
    }

    $row['totalPaid']   = $totalPaid;
    $row['outstanding'] = $outstanding;
    $row['repayments']  = $repayments;

    // Build repayment schedule if disbursed
    $schedule = [];
    if (!empty($row['disbursedAt']) && !empty($row['dueDate'])) {
        $totalRepayable  = (float)$row['amount'] * 1.20;
        $monthlyPayment  = $totalRepayable / 3;
        $dueDate         = new DateTime($row['dueDate']);
        $today           = new DateTime('today');

        for ($i = 3; $i >= 1; $i--) {
            $paymentDate = clone $dueDate;
            $paymentDate->modify('-' . ($i - 1) . ' months');

            $cumulativeDue = $monthlyPayment * (4 - $i);
            $paid          = min($totalPaid, $cumulativeDue);
            $prevDue       = $monthlyPayment * (3 - $i);
            $thisPaid      = max(0, $paid - $prevDue);

            $dateStr = $paymentDate->format('Y-m-d');
            if ($thisPaid >= $monthlyPayment * 0.99) {
                $status = 'Paid';
            } elseif ($paymentDate < $today) {
                $status = 'Overdue';
            } else {
                $status = 'Upcoming';
            }

            $schedule[] = [
                'month'       => 'Month ' . (4 - $i),
                'dueDate'     => $dateStr,
                'amount'      => round($monthlyPayment, 2),
                'status'      => $status,
            ];
        }
    }
    $row['schedule'] = $schedule;

    echo json_encode(['success' => true, 'application' => $row]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
