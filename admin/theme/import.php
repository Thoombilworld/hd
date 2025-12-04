<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';
require_once HS_ROOT . '/core/ColorEngine.php';
require_once HS_ROOT . '/core/ThemeCustomizer.php';

$themeService = new Theme(HS_ROOT . '/themes');
$colorEngine = new ColorEngine();
$customizer = new ThemeCustomizer($themeService, $colorEngine);
$folder = $_GET['theme'] ?? ($themeService->getActiveTheme()['folder'] ?? 'liquid_glass_2025');
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['settings'])) {
    $content = file_get_contents($_FILES['settings']['tmp_name']);
    $json = json_decode($content, true);
    if (is_array($json)) {
        $customizer->save($folder, $json);
        $message = 'Settings imported.';
    } else {
        $message = 'Invalid JSON file.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Theme Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Import Settings</h1>
            <p class="text-muted mb-0">Upload exported settings.json to apply to <?= htmlspecialchars($folder); ?>.</p>
        </div>
        <a class="btn btn-outline-secondary" href="index.php">Back</a>
    </div>
    <?php if ($message): ?><div class="alert alert-info"><?= htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">settings.json</label>
            <input type="file" name="settings" class="form-control" accept="application/json" required>
        </div>
        <button class="btn btn-primary" type="submit">Import</button>
    </form>
</body>
</html>
