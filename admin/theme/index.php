<?php
require_once __DIR__ . '/../../bootstrap.php';
$themesDir = __DIR__ . '/../../themes';
$themes = array_filter(scandir($themesDir), fn($d) => $d[0] !== '.' && is_dir($themesDir . '/' . $d));
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Theme Manager</title></head>
<body>
<h1>Themes</h1>
<ul>
<?php foreach ($themes as $theme): ?>
    <li><?php echo HS_Helpers::esc($theme); ?> - <a href="activate.php?theme=<?php echo urlencode($theme); ?>">Activate</a> | <a href="preview.php?theme=<?php echo urlencode($theme); ?>" target="_blank">Preview</a></li>
<?php endforeach; ?>
</ul>
</body>
</html>
