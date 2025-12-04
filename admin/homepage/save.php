<?php
require dirname(__DIR__) . '/../bootstrap.php';
require_once HS_ROOT . '/core/HomepageBuilder.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$boolFields = [
    'show_breaking',
    'show_hero_world',
    'show_india_focus',
    'show_kerala_malayalam',
    'show_world_sections',
    'show_videos_live',
    'show_opinion',
    'show_photo_gallery',
    'show_trending_tags',
    'show_newsletter',
];

$config = [];
foreach ($boolFields as $field) {
    $config[$field] = isset($_POST[$field]) ? 1 : 0;
}

$intFields = [
    'hero_limit' => 5,
    'breaking_limit' => 8,
    'india_limit' => 6,
    'kerala_limit' => 8,
    'region_limit' => 5,
    'video_limit' => 4,
    'opinion_limit' => 4,
    'photo_limit' => 6,
    'tags_limit' => 12,
];

foreach ($intFields as $field => $default) {
    $value = isset($_POST[$field]) ? (int)$_POST[$field] : $default;
    $config[$field] = max(1, $value);
}

$mode = $_POST['kerala_language_mode'] ?? 'mixed';
$config['kerala_language_mode'] = in_array($mode, ['mixed', 'ml', 'en'], true) ? $mode : 'mixed';

HomepageBuilder::persistConfig($config);

header('Location: index.php?status=saved');
exit;
