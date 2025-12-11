<?php
require __DIR__ . '/bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';
require_once HS_ROOT . '/core/ColorEngine.php';
require_once HS_ROOT . '/core/LayoutEngine.php';

$themeService = new Theme(HS_ROOT . '/themes');
$theme = $themeService->getActiveTheme();
$settings = $themeService->loadThemeSettings($theme['folder'] ?? '');
$themeInfo = $theme;
$themeSettings = $settings;
$colorEngine = new ColorEngine();
$layoutEngine = new LayoutEngine();
$template = HS_ROOT . '/themes/' . ($theme['folder'] ?? '') . '/home.php';

if (!is_readable($template)) {
    http_response_code(500);
    exit('Active theme template missing');
}

include $template;
