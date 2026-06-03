<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(400); echo 'Invalid ID'; exit; }

try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare('SELECT id_document, full_name FROM loan_applications WHERE id = ?');
    $stmt->execute([$id]);
    $row  = $stmt->fetch();

    if (!$row || empty($row['id_document'])) {
        http_response_code(404); echo 'No document on file for this application.'; exit;
    }

    $path = __DIR__ . '/../uploads/' . basename($row['id_document']);
    if (!file_exists($path)) {
        http_response_code(404); echo 'File not found on server.'; exit;
    }

    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mime = match ($ext) {
        'pdf'       => 'application/pdf',
        'jpg', 'jpeg' => 'image/jpeg',
        'png'       => 'image/png',
        default     => 'application/octet-stream',
    };

    header('Content-Type: ' . $mime);
    header('Content-Disposition: inline; filename="application_' . $id . '_id.' . $ext . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
} catch (Throwable $e) {
    http_response_code(500); echo 'Server error';
}
