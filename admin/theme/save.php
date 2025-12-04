<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../core/ThemeCustomizer.php';
$data = $_POST;
HS_ThemeCustomizer::save($data);
header('Location: editor.php');
exit;
