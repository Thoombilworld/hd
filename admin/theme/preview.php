<?php
require_once __DIR__ . '/../../bootstrap.php';
$theme = $_GET['theme'] ?? 'liquid_glass_2025';
$_SESSION['preview_theme'] = $theme;
header('Location: ../../home.php?preview=1');
exit;
