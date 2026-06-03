<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$current = (string)($_POST['current_password'] ?? '');
$new     = (string)($_POST['new_password']     ?? '');
$confirm = (string)($_POST['confirm_password'] ?? '');

if ($current === '' || $new === '' || $confirm === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!password_verify($current, ADMIN_PASSWORD_HASH)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

if ($new !== $confirm) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
    exit;
}

if (strlen($new) < 8) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters']);
    exit;
}

$newHash     = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
$configPath  = __DIR__ . '/../config.php';
$configText  = file_get_contents($configPath);

// Use preg_replace_callback so the bcrypt hash (which contains $2y$12$...)
// is treated as a literal string — preg_replace replacement strings interpret
// $1, $2 etc. as backreferences, which would silently corrupt the hash.
$updated = preg_replace_callback(
    "/const ADMIN_PASSWORD_HASH\s*=\s*'[^']*';/",
    fn($m) => "const ADMIN_PASSWORD_HASH = '" . $newHash . "';",
    $configText
);

if ($updated === null || $updated === $configText) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update config — pattern not found']);
    exit;
}

if (file_put_contents($configPath, $updated) === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to write config file']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
