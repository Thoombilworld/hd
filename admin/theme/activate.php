<?php
require dirname(__DIR__, 1) . '/../bootstrap.php';
require_once HS_ROOT . '/core/Theme.php';

$themeService = new Theme(HS_ROOT . '/themes');
$folder = $_GET['theme'] ?? '';
if ($folder && $themeService->activateTheme($folder)) {
    header('Location: index.php?status=activated');
    exit;
}
http_response_code(400);
echo 'Unable to activate theme';
