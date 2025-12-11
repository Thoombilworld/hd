<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';

$themeService = new Theme(HS_ROOT . '/themes');
$folder = $_GET['theme'] ?? ($themeService->getActiveTheme()['folder'] ?? 'liquid_glass_2025');
$settings = $themeService->loadThemeSettings($folder);
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . basename($folder) . '-settings.json"');
echo json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
