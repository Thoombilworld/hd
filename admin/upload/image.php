<?php
require __DIR__ . '/../../bootstrap.php';
hs_require_staff(['admin','editor','reporter']);

header('Content-Type: application/json; charset=utf-8');

if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$allowed = ['image/jpeg','image/png','image/gif','image/webp'];
$file = $_FILES['file'];

if (!in_array($file['type'], $allowed, true)) {
    echo json_encode(['error' => 'Invalid file type']);
    exit;
}

if ($file['size'] > 2 * 1024 * 1024) {
    echo json_encode(['error' => 'File too large (max 2MB)']);
    exit;
}

$uploadDir = __DIR__ . '/../../writable/uploads/editor/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$basename = 'ed_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$targetPath = $uploadDir . $basename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(['error' => 'Unable to save file']);
    exit;
}

$url = HS_BASE_URL . 'writable/uploads/editor/' . $basename;
echo json_encode(['url' => $url]);
