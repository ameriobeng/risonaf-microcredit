<?php
declare(strict_types=1);

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// ── Rate limiting: max 5 submissions per IP per hour ──────────────────────────
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ip = trim(explode(',', $ip)[0]);

try {
    $ratePdo = getPDO();
    $ratePdo->exec("DELETE FROM rate_limits WHERE submitted_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $rateStmt = $ratePdo->prepare("SELECT COUNT(*) FROM rate_limits WHERE ip = ?");
    $rateStmt->execute([$ip]);
    $count = (int)$rateStmt->fetchColumn();
    if ($count >= 5) {
        http_response_code(429);
        echo json_encode(['success' => false, 'message' => 'Too many submissions. Please try again in an hour.']);
        exit;
    }
} catch (Throwable) {
    // rate limit table may not exist yet — allow through
}

// ── Input ─────────────────────────────────────────────────────────────────────
$fullName  = trim((string)($_POST['fullName']  ?? ''));
$phone     = trim((string)($_POST['phone']     ?? ''));
$email     = trim((string)($_POST['email']     ?? ''));
$location  = trim((string)($_POST['location']  ?? ''));
$idType    = trim((string)($_POST['idType']    ?? ''));
$idNumber  = trim((string)($_POST['idNumber']  ?? ''));
$loanType  = trim((string)($_POST['loanType']  ?? ''));
$amountRaw = trim((string)($_POST['amount']    ?? ''));
$purpose   = trim((string)($_POST['purpose']   ?? ''));

$allowedLoanTypes = ['Personal Loan', 'Business Loan', 'Group Loan'];
$allowedIdTypes   = ['Ghana Card', 'Passport', "Driver's License", "Voter's ID"];

// ── Required fields ───────────────────────────────────────────────────────────
if (
    $fullName === '' || $phone    === '' || $email   === '' ||
    $location === '' || $idType   === '' || $idNumber === '' ||
    $loanType === '' || $amountRaw === '' || $purpose === ''
) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// ── Ghana phone validation: 0XXXXXXXXX or +233XXXXXXXXX ──────────────────────
if (!preg_match('/^(0\d{9}|\+233\d{9})$/', $phone)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Enter a valid Ghana phone number (e.g. 0244000000 or +233244000000)']);
    exit;
}

// ── Email ─────────────────────────────────────────────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// ── ID type ───────────────────────────────────────────────────────────────────
if (!in_array($idType, $allowedIdTypes, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid ID card type']);
    exit;
}

// ── Loan type ─────────────────────────────────────────────────────────────────
if (!in_array($loanType, $allowedLoanTypes, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid loan type']);
    exit;
}

// ── Amount: GHS 100 – 100,000 ─────────────────────────────────────────────────
$amount = (float)$amountRaw;
if ($amount < 100) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Minimum loan amount is GHS 100']);
    exit;
}
if ($amount > 100000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Maximum loan amount is GHS 100,000']);
    exit;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare(
        'INSERT INTO loan_applications
         (full_name, phone, email, location, id_type, id_number, loan_type, amount, purpose)
         VALUES (:full_name, :phone, :email, :location, :id_type, :id_number, :loan_type, :amount, :purpose)'
    );

    $stmt->execute([
        ':full_name' => $fullName,
        ':phone'     => $phone,
        ':email'     => $email,
        ':location'  => $location,
        ':id_type'   => $idType,
        ':id_number' => $idNumber,
        ':loan_type' => $loanType,
        ':amount'    => $amount,
        ':purpose'   => $purpose,
    ]);

    $newId = (int)$pdo->lastInsertId();

    // Record this submission for rate limiting
    try {
        $pdo->prepare("INSERT INTO rate_limits (ip) VALUES (?)")->execute([$ip]);
    } catch (Throwable) {}

    // ── Admin notification ────────────────────────────────────────────────────
    if (NOTIFY_EMAIL !== '') {
        $adminBody = "New loan application received on Risonaf Loans Ghana.\n\n"
            . "Name:      {$fullName}\n"
            . "Phone:     {$phone}\n"
            . "Email:     {$email}\n"
            . "Location:  {$location}\n"
            . "ID Type:   {$idType}\n"
            . "ID Number: {$idNumber}\n"
            . "Loan Type: {$loanType}\n"
            . "Amount:    GHS {$amount}\n"
            . "Purpose:   {$purpose}\n\n"
            . "View all applications in the admin dashboard.";
        sendMail(NOTIFY_EMAIL, "New Loan Application #{$newId} — {$fullName}", $adminBody);
    }

    // ── Confirmation email to applicant ───────────────────────────────────────
    $confirmBody = "Dear {$fullName},\n\n"
        . "Thank you for applying with Risonaf Loans Ghana. We have received your application and our team will review it shortly.\n\n"
        . "Application Summary\n"
        . "-------------------\n"
        . "Reference:  #{$newId}\n"
        . "Loan Type:  {$loanType}\n"
        . "Amount:     GHS " . number_format($amount, 2) . "\n"
        . "Purpose:    {$purpose}\n"
        . "Submitted:  " . date('Y-m-d H:i') . "\n\n"
        . "You will receive another email once a decision has been made.\n"
        . "To check your status at any time, visit our website and use the \"Check Status\" page.\n\n"
        . "If you did not submit this application, please contact us immediately.\n\n"
        . "— The Risonaf Loans Team";
    sendMail($email, "Application Received — Reference #{$newId}", $confirmBody);

    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully',
        'id'      => $newId,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error while saving application']);
}
