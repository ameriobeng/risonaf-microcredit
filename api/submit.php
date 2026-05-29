<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/mailer.php';

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

    $newId = (int)$pdo->lastInsertId();

    // Email notification (fire-and-forget — failure doesn't affect the response)
    if (NOTIFY_EMAIL !== '') {
        $emailBody = "New loan application received on Risonaf Microcredit Ghana.\n\n"
            . "Name:      {$fullName}\n"
            . "Phone:     {$phone}\n"
            . "Email:     {$email}\n"
            . "Location:  {$location}\n"
            . "Loan Type: {$loanType}\n"
            . "Amount:    GHS {$amount}\n"
            . "Purpose:   {$purpose}\n\n"
            . "View all applications in the admin dashboard.";
        sendMail(NOTIFY_EMAIL, "New Loan Application #{$newId} — {$fullName}", $emailBody);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully',
        'id'      => $newId,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while saving application']);
}
