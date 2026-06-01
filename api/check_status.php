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
    $stmt = $pdo->prepare(
        'SELECT id,
                full_name AS fullName,
                loan_type AS loanType,
                amount,
                status,
                DATE_FORMAT(submitted_at, "%Y-%m-%d %H:%i") AS submittedAt
         FROM loan_applications
         WHERE phone = ? AND id_number = ?
         ORDER BY submitted_at DESC
         LIMIT 1'
    );
    $stmt->execute([$phone, $idNumber]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'No application found matching those details.']);
        exit;
    }

    echo json_encode(['success' => true, 'application' => $row]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
