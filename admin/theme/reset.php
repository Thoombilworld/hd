<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';

$themeService = new Theme(HS_ROOT . '/themes');
$folder = $_GET['theme'] ?? ($themeService->getActiveTheme()['folder'] ?? '');
$settingsFile = HS_ROOT . '/themes/' . $folder . '/settings.json';
if (is_readable($settingsFile)) {
    $original = json_decode((string)file_get_contents($settingsFile), true);
    if (is_array($original)) {
        file_put_contents($settingsFile, json_encode($original, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
header('Location: editor.php?theme=' . urlencode($folder));
