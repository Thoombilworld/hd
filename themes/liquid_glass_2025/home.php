<?php
/**
 * Liquid Glass 2025 — World News Captain Edition homepage.
 */
require_once HS_ROOT . '/core/HomepageBuilder.php';

$builder = new HomepageBuilder(hs_db());
$config = $builder->getConfig();
$themeFolder = basename(__DIR__);
$themeUrl = hs_base_url('themes/' . $themeFolder . '/');
$componentsPath = __DIR__ . '/components/home/';

$colorVars = $colorEngine->generateCssVariables($themeSettings['palette'] ?? []);
$breakingNews = !empty($config['show_breaking']) ? $builder->getBreakingNews() : [];
$heroPosts = !empty($config['show_hero_world']) ? $builder->getWorldHeroPosts() : [];
$indiaStories = !empty($config['show_india_focus']) ? $builder->getIndiaTopStories() : [];
$keralaStories = !empty($config['show_kerala_malayalam']) ? $builder->getKeralaMalayalamStories() : [];
$opinionPosts = !empty($config['show_opinion']) ? $builder->getOpinionEditorials() : [];
$videos = !empty($config['show_videos_live']) ? $builder->getVideoHighlights() : [];
$photos = !empty($config['show_photo_gallery']) ? $builder->getPhotoStories() : [];
$tags = !empty($config['show_trending_tags']) ? $builder->getTrendingTags() : [];

$regions = [
    'asia' => 'Asia',
    'gcc' => 'Middle East / GCC',
    'europe' => 'Europe',
    'americas' => 'Americas',
    'africa' => 'Africa',
];
$worldSections = [];
if (!empty($config['show_world_sections'])) {
    foreach ($regions as $key => $label) {
        $worldSections[$key] = [
            'label' => $label,
            'stories' => $builder->getWorldByRegion($key),
        ];
    }
}

$logoText = $themeInfo['name'] ?? 'NEWS HDSPTV';
function hg_story_url(array $story): string {
    if (!empty($story['slug'])) {
        return hs_news_url($story['slug']);
    }
    return hs_base_url('news/' . ($story['id'] ?? ''));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($logoText); ?> — World News Captain</title>
    <style>:root{<?= $colorVars; ?>}</style>
    <link rel="stylesheet" href="<?= hs_base_url('themes/liquid_glass_2025/assets/css/theme.css'); ?>">
</head>
<body class="hg-body">
    <header class="wnc-topbar" id="top">
        <div class="wnc-brand">
            <div class="wnc-logo">WN</div>
            <div>
                <div class="wnc-name">World News Captain</div>
                <div class="wnc-sub">NEWS HDSPTV</div>
            </div>
        </div>
        <div class="wnc-center">
            <div class="wnc-lang">
                <a href="#" class="active">English</a>
                <span>|</span>
                <a href="#">മലയാളം</a>
            </div>
            <div class="wnc-meta">Global + Kerala • Reliable • 24x7</div>
        </div>
        <div class="wnc-right">
            <span class="wnc-datetime"><?= date('D, M j'); ?> • <?= date('h:i A'); ?></span>
            <a class="wnc-link" href="<?= hs_login_url(); ?>">Login</a>
            <a class="wnc-pill" href="<?= hs_register_url(); ?>">Subscribe</a>
        </div>
    </header>

    <nav class="wnc-nav" data-sticky>
        <?php foreach (['World','India','Kerala','Gulf','Business','Tech','Sports','Entertainment','Opinion'] as $item): ?>
            <a href="#" class="wnc-nav__link"><?= htmlspecialchars($item); ?></a>
        <?php endforeach; ?>
        <div class="wnc-nav__actions">
            <input type="search" placeholder="Search news" aria-label="Search" />
        </div>
    </nav>

    <main class="wnc-main">
        <?php if (!empty($config['show_breaking'])) { include $componentsPath . 'breaking_ticker.php'; } ?>
        <?php if (!empty($config['show_hero_world'])) { include $componentsPath . 'hero_world.php'; } ?>
        <?php if (!empty($config['show_india_focus'])) { include $componentsPath . 'india_focus.php'; } ?>
        <?php if (!empty($config['show_kerala_malayalam'])) { include $componentsPath . 'kerala_malayalam.php'; } ?>
        <?php if (!empty($config['show_world_sections'])) { include $componentsPath . 'world_sections.php'; } ?>
        <?php if (!empty($config['show_videos_live'])) { include $componentsPath . 'videos_live.php'; } ?>
        <?php if (!empty($config['show_opinion'])) { include $componentsPath . 'opinion_editorial.php'; } ?>
        <?php if (!empty($config['show_photo_gallery'])) { include $componentsPath . 'photo_gallery.php'; } ?>
        <?php if (!empty($config['show_trending_tags'])) { include $componentsPath . 'trending_tags.php'; } ?>
        <?php if (!empty($config['show_newsletter'])) { include $componentsPath . 'newsletter_block.php'; } ?>
    </main>
</body>
</html>
