<?php
/**
 * Liquid Glass 2025 homepage template.
 */
$colorVars = $colorEngine->generateCssVariables($themeSettings['palette'] ?? []);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($themeInfo['name'] ?? 'NEWS HDSPTV'); ?></title>
    <style>:root{<?= $colorVars; ?>}</style>
    <link rel="stylesheet" href="<?= hs_base_url('themes/liquid_glass_2025/assets/css/theme.css'); ?>">
</head>
<body class="hg-body">
    <header class="hg-header">
        <div class="hg-brand">NEWS HDSPTV</div>
        <nav class="hg-nav">
            <?php foreach (hs_primary_nav_items() as $item): ?>
                <a href="<?= htmlspecialchars($item['url']); ?>" class="hg-nav__link"><?= htmlspecialchars($item['label']); ?></a>
            <?php endforeach; ?>
        </nav>
        <button class="hg-mode" data-mode-toggle>Mode</button>
    </header>

    <main class="hg-main">
        <section class="hg-hero glass">
            <div class="hg-hero__content">
                <p class="eyebrow">Top Story</p>
                <h1>Future-ready newsroom powered by the HDSPTV 2025 engine</h1>
                <p class="lede">Experience lightning-fast storytelling, adaptive colors, and glassy layouts tuned for modern audiences.</p>
                <a class="hg-button" href="<?= hs_base_url(); ?>">Explore now</a>
            </div>
        </section>

        <section class="hg-grid">
            <article class="hg-card glass">
                <p class="eyebrow">AI Adaptive</p>
                <h3>Automatic palette generation from your brand assets.</h3>
                <p>Upload your logo to instantly seed neon, glass, and neutral tones.</p>
            </article>
            <article class="hg-card glass">
                <p class="eyebrow">Live Builder</p>
                <h3>Drag, drop, and preview layouts in real-time.</h3>
                <p>Every change streams to the iframe preview for zero-guess design.</p>
            </article>
            <article class="hg-card glass">
                <p class="eyebrow">Widgets</p>
                <h3>Modular blocks optimized for headline-first reading.</h3>
                <p>Slot hero sliders, trending rails, and newsletter captures with ease.</p>
            </article>
        </section>
    </main>

    <footer class="hg-footer glass">
        <p>© <?= date('Y'); ?> NEWS HDSPTV — Liquid Glass 2025</p>
    </footer>
</body>
</html>
