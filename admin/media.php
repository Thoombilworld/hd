<?php
require __DIR__ . '/../bootstrap.php';
hs_require_staff(['admin', 'editor']);

$uploadDir = HS_ROOT . '/writable/media';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}
$db = hs_db();
if ($db && !hs_table_exists('hs_media', $db)) {
    mysqli_query($db, "CREATE TABLE IF NOT EXISTS hs_media (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        path VARCHAR(255) NOT NULL,
        mime VARCHAR(120) NULL,
        size BIGINT UNSIGNED NULL,
        uploaded_by INT UNSIGNED NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(uploaded_by)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $name = basename($file['name']);
        $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $name);
        $dest = $uploadDir . '/' . $safeName;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            if ($db) {
                $stmt = mysqli_prepare($db, "INSERT INTO hs_media(filename, path, mime, size, uploaded_by) VALUES (?,?,?,?,?)");
                $userId = $_SESSION['hs_admin_id'] ?? null;
                mysqli_stmt_bind_param($stmt, 'sssii', $safeName, $dest, $file['type'], $file['size'], $userId);
                mysqli_stmt_execute($stmt);
            }
            $msg = 'File uploaded.';
        } else {
            $error = 'Upload failed.';
        }
    } else {
        $error = 'Upload error code: ' . $file['error'];
    }
}

$media = [];
if ($db) {
    $res = mysqli_query($db, "SELECT * FROM hs_media ORDER BY created_at DESC LIMIT 100");
    $media = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Media Library – NEWS HDSPTV</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1 class="h4 mb-3">Media Library</h1>
  <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="card p-3 mb-4 shadow-sm">
    <div class="mb-2"><label class="form-label">Upload file</label><input class="form-control" type="file" name="file" required></div>
    <button class="btn btn-primary" type="submit">Upload</button>
  </form>
  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="h6">Recent files</h2>
      <div class="table-responsive">
        <table class="table table-sm">
          <thead><tr><th>File</th><th>Mime</th><th>Size</th><th>Path</th><th>Uploaded</th></tr></thead>
          <tbody>
            <?php foreach ($media as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['filename']) ?></td>
                <td><?= htmlspecialchars($row['mime']) ?></td>
                <td><?= number_format((int)$row['size']/1024, 1) ?> KB</td>
                <td><small><?= htmlspecialchars(str_replace(HS_ROOT . '/', '', $row['path'])) ?></small></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
