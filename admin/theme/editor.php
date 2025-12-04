<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../core/ThemeCustomizer.php';
$theme = new HS_Theme();
$settings = $theme->settings();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Theme Customizer</title></head>
<body>
<h1>Theme Customizer</h1>
<form method="post" action="save.php">
    <label>Primary Color <input type="color" name="palette[primary]" value="<?php echo HS_Helpers::esc($settings['palette']['primary'] ?? '#ff3b30'); ?>"></label><br>
    <label>Secondary Color <input type="color" name="palette[secondary]" value="<?php echo HS_Helpers::esc($settings['palette']['secondary'] ?? '#0d6efd'); ?>"></label><br>
    <label>Custom CSS<br><textarea name="custom_css" rows="4" cols="60"><?php echo HS_Helpers::esc($settings['custom_css'] ?? ''); ?></textarea></label><br>
    <label>Custom JS<br><textarea name="custom_js" rows="4" cols="60"><?php echo HS_Helpers::esc($settings['custom_js'] ?? ''); ?></textarea></label><br>
    <button type="submit">Save</button>
</form>
</body>
</html>
