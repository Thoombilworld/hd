<?php
require_once __DIR__ . '/../../bootstrap.php';
$theme = $_GET['theme'] ?? '';
if ($theme !== '') {
    HS_DB::query("INSERT INTO hs_settings(`key`,`value`) VALUES('active_theme', ?) ON DUPLICATE KEY UPDATE value=VALUES(value)", [$theme]);
}
header('Location: index.php');
exit;
