<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';
require_once HS_ROOT . '/core/ColorEngine.php';
require_once HS_ROOT . '/core/ThemeCustomizer.php';

$themeService = new Theme(HS_ROOT . '/themes');
$colorEngine = new ColorEngine();
$customizer = new ThemeCustomizer($themeService, $colorEngine);

$folder = $_GET['theme'] ?? ($themeService->getActiveTheme()['folder'] ?? 'liquid_glass_2025');
$theme = $themeService->getThemeInfo($folder);
if (!$theme) {
    http_response_code(404);
    exit('Theme not found');
}
$settings = $customizer->load($folder);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $palette = $_POST['palette'] ?? [];
    $typography = $_POST['typography'] ?? [];
    $layout = $_POST['layout'] ?? [];
    $settings = [
        'palette' => $palette,
        'typography' => $typography,
        'layout' => $layout,
        'modes' => $settings['modes'] ?? [],
        'widgets' => $settings['widgets'] ?? []
    ];
    $customizer->save($folder, $settings);
    header('Location: editor.php?theme=' . urlencode($folder) . '&saved=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Customizer — <?= htmlspecialchars($theme['name'] ?? $folder); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>body{background:#0b1220;color:#e2e8f0;font-family:'Inter',system-ui,sans-serif;} .sidebar{width:340px;}</style>
</head>
<body>
<div class="d-flex" style="min-height:100vh;">
    <div class="sidebar bg-dark text-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Customizer</h2>
            <a class="btn btn-sm btn-outline-light" href="index.php">Back</a>
        </div>
        <?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Saved</div><?php endif; ?>
        <form method="post">
            <h6 class="text-uppercase text-muted">Palette</h6>
            <?php foreach (['primary','secondary','background','glass','text','muted','neon','dark'] as $key): ?>
                <div class="mb-2">
                    <label class="form-label small text-white"><?= ucfirst($key); ?></label>
                    <input type="text" class="form-control" name="palette[<?= $key; ?>]" value="<?= htmlspecialchars($settings['palette'][$key] ?? ''); ?>">
                </div>
            <?php endforeach; ?>

            <h6 class="text-uppercase text-muted mt-3">Typography</h6>
            <div class="mb-2"><label class="form-label small">Font Family</label><input class="form-control" name="typography[font_family]" value="<?= htmlspecialchars($settings['typography']['font_family'] ?? ''); ?>"></div>
            <div class="mb-2"><label class="form-label small">Base Size</label><input class="form-control" name="typography[base_size]" value="<?= htmlspecialchars($settings['typography']['base_size'] ?? '16px'); ?>"></div>
            <div class="mb-2"><label class="form-label small">Weight</label><input class="form-control" name="typography[weight]" value="<?= htmlspecialchars($settings['typography']['weight'] ?? '500'); ?>"></div>
            <div class="mb-2"><label class="form-label small">Line Height</label><input class="form-control" name="typography[line_height]" value="<?= htmlspecialchars($settings['typography']['line_height'] ?? '1.6'); ?>"></div>

            <h6 class="text-uppercase text-muted mt-3">Layout</h6>
            <div class="mb-2"><label class="form-label small">Container</label><input class="form-control" name="layout[container]" value="<?= htmlspecialchars($settings['layout']['container'] ?? '1200px'); ?>"></div>
            <div class="mb-2"><label class="form-label small">Grid Gap</label><input class="form-control" name="layout[grid_gap]" value="<?= htmlspecialchars($settings['layout']['grid_gap'] ?? '24px'); ?>"></div>
            <div class="mb-2"><label class="form-label small">Card Radius</label><input class="form-control" name="layout[card_radius]" value="<?= htmlspecialchars($settings['layout']['card_radius'] ?? '20px'); ?>"></div>
            <div class="mb-2"><label class="form-label small">Glass Blur</label><input class="form-control" name="layout[glass_blur]" value="<?= htmlspecialchars($settings['layout']['glass_blur'] ?? '16px'); ?>"></div>

            <button class="btn btn-primary w-100 mt-3" type="submit">Save & Generate</button>
        </form>
    </div>
    <div class="flex-fill bg-light">
        <iframe src="preview.php?theme=<?= urlencode($folder); ?>" style="border:0;width:100%;height:100vh;"></iframe>
    </div>
</div>
</body>
</html>
