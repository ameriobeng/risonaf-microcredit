<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$fullName = trim((string)($_POST['fullName'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$location = trim((string)($_POST['location'] ?? ''));
$loanType = trim((string)($_POST['loanType'] ?? ''));
$amountRaw = trim((string)($_POST['amount'] ?? ''));
$purpose = trim((string)($_POST['purpose'] ?? ''));

$allowedLoanTypes = ['Personal Loan', 'Business Loan', 'Group Loan'];

if (
    $fullName === '' ||
    $phone === '' ||
    $email === '' ||
    $location === '' ||
    $loanType === '' ||
    $amountRaw === '' ||
    $purpose === ''
) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if (!in_array($loanType, $allowedLoanTypes, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid loan type']);
    exit;
}

$amount = (float)$amountRaw;
if ($amount <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Amount must be greater than zero']);
    exit;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare(
        'INSERT INTO loan_applications 
         (full_name, phone, email, location, loan_type, amount, purpose) 
         VALUES (:full_name, :phone, :email, :location, :loan_type, :amount, :purpose)'
    );

    $stmt->execute([
        ':full_name' => $fullName,
        ':phone' => $phone,
        ':email' => $email,
        ':location' => $location,
        ':loan_type' => $loanType,
        ':amount' => $amount,
        ':purpose' => $purpose,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully',
        'id' => (int)$pdo->lastInsertId(),
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while saving application']);
}
