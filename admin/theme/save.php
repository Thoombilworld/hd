<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';
require_once HS_ROOT . '/core/ColorEngine.php';
require_once HS_ROOT . '/core/ThemeCustomizer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$folder = $_POST['theme'] ?? '';
$payload = $_POST['settings'] ?? [];

$themeService = new Theme(HS_ROOT . '/themes');
$theme = $themeService->getThemeInfo($folder);
if (!$theme) {
    http_response_code(404);
    exit('Theme not found');
}

$colorEngine = new ColorEngine();
$customizer = new ThemeCustomizer($themeService, $colorEngine);
$customizer->save($folder, $payload);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
