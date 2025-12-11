<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';
require_once HS_ROOT . '/core/ColorEngine.php';
require_once HS_ROOT . '/core/LayoutEngine.php';

$themeService = new Theme(HS_ROOT . '/themes');
$colorEngine = new ColorEngine();
$layoutEngine = new LayoutEngine();

$folder = $_GET['theme'] ?? ($themeService->getActiveTheme()['folder'] ?? 'liquid_glass_2025');
$theme = $themeService->getThemeInfo($folder);
if (!$theme) {
    http_response_code(404);
    exit('Theme not found');
}
$settings = $themeService->loadThemeSettings($folder);
$themeInfo = $theme;
$themeSettings = $settings;

$template = HS_ROOT . '/themes/' . $folder . '/home.php';
if (!is_readable($template)) {
    exit('Template missing');
}
$colorEngine = new ColorEngine();
$layoutEngine = new LayoutEngine();

include $template;
