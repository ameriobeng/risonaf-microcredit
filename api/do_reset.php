<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$token   = trim((string)($_POST['token']           ?? ''));
$new     = (string)($_POST['new_password']         ?? '');
$confirm = (string)($_POST['confirm_password']     ?? '');

if ($token === '' || $new === '' || $confirm === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if ($new !== $confirm) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

if (strlen($new) < 8) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare(
        'SELECT id FROM password_resets
         WHERE token = ? AND used = 0 AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)'
    );
    $stmt->execute([$token]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'This reset link has expired or has already been used.']);
        exit;
    }

    $newHash    = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
    $configPath = __DIR__ . '/../config.php';
    $configText = file_get_contents($configPath);

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
        echo json_encode(['success' => false, 'message' => 'Failed to update password — config pattern not found']);
        exit;
    }

    if (file_put_contents($configPath, $updated) === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write config file — check server permissions']);
        exit;
    }

    // Mark token used only after the file write succeeds — if we burned it first
    // and the write failed, the admin would be locked out with no way to retry.
    $pdo->prepare('UPDATE password_resets SET used = 1 WHERE id = ?')->execute([$row['id']]);

    echo json_encode(['success' => true, 'message' => 'Password updated. Redirecting to sign in…']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error — please try again']);
}
