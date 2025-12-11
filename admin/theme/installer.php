<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/ThemeInstaller.php';

$installer = new ThemeInstaller(HS_ROOT . '/themes');
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['package'])) {
    $tmp = $_FILES['package']['tmp_name'];
    $result = $installer->installFromZip($tmp);
    $message = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Theme</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Install Theme Package</h1>
            <p class="text-muted mb-0">Upload a .zip to register a new theme.</p>
        </div>
        <a class="btn btn-outline-secondary" href="index.php">Back</a>
    </div>
    <?php if ($message): ?><div class="alert alert-info"><?= htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Theme package (.zip)</label>
            <input type="file" name="package" class="form-control" accept=".zip" required>
        </div>
        <button class="btn btn-primary" type="submit">Install</button>
    </form>
</body>
</html>
