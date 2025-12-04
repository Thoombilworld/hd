<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';
require_once HS_ROOT . '/core/ColorEngine.php';
require_once HS_ROOT . '/core/ThemeCustomizer.php';

$themeService = new Theme(HS_ROOT . '/themes');
$colorEngine = new ColorEngine();
$themes = $themeService->getThemes();
$active = $themeService->getActiveTheme();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activate'])) {
    $folder = trim($_POST['activate']);
    if ($themeService->activateTheme($folder)) {
        header('Location: index.php?status=activated');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Manager — NEWS HDSPTV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body{background:#f6f8fb;font-family:'Inter',system-ui,sans-serif;}
        .theme-card{border:1px solid #e5e7eb;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(15,23,42,0.06);}
        .theme-thumb{height:180px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:700;}
        .badge-pro{background:linear-gradient(120deg,#2563eb,#06b6d4);}
    </style>
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Theme Manager</h1>
            <p class="text-muted mb-0">Activate, preview, and customize next-gen themes.</p>
        </div>
        <a class="btn btn-primary" href="editor.php?theme=<?= urlencode($active['folder'] ?? ''); ?>">Open Customizer</a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status']==='activated'): ?>
        <div class="alert alert-success">Theme activated successfully.</div>
    <?php endif; ?>

    <div class="row g-3">
        <?php foreach ($themes as $theme): ?>
            <div class="col-md-4">
                <div class="theme-card">
                    <div class="theme-thumb">
                        <?= htmlspecialchars($theme['name'] ?? $theme['folder']); ?>
                    </div>
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($theme['name'] ?? $theme['folder']); ?></h5>
                                <p class="text-muted small mb-2">v<?= htmlspecialchars($theme['version'] ?? '1.0'); ?> • <?= htmlspecialchars($theme['author'] ?? ''); ?></p>
                            </div>
                            <?php if (!empty($theme['is_pro'])): ?><span class="badge badge-pro text-white">PRO</span><?php endif; ?>
                        </div>
                        <div class="d-flex gap-2">
                            <form method="post" class="d-inline">
                                <input type="hidden" name="activate" value="<?= htmlspecialchars($theme['folder']); ?>">
                                <button class="btn btn-success btn-sm" type="submit" <?= ($active['folder'] ?? '') === $theme['folder'] ? 'disabled' : ''; ?>>Activate</button>
                            </form>
                            <a class="btn btn-outline-primary btn-sm" href="preview.php?theme=<?= urlencode($theme['folder']); ?>" target="_blank">Preview</a>
                            <?php if (!empty($theme['has_customizer'])): ?>
                                <a class="btn btn-outline-dark btn-sm" href="editor.php?theme=<?= urlencode($theme['folder']); ?>">Customize</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
