<?php
$seo = HS_SEO::meta($meta ?? []);
$settings = hs_settings();
$palette = hs_theme_palette(hs_current_theme());
$navItems = hs_primary_nav_items();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo HS_Helpers::esc($seo['title']); ?></title>
    <meta name="description" content="<?php echo HS_Helpers::esc($seo['description']); ?>">
    <meta name="keywords" content="<?php echo HS_Helpers::esc($seo['keywords']); ?>">
    <?php if (!empty($seo['og_image'])): ?>
        <meta property="og:image" content="<?php echo HS_Helpers::esc($seo['og_image']); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo HS_THEME_URL; ?>assets/css/style.css">
</head>
<body class="glass">
<div class="page-shell">
    <header class="site-header">
        <div class="top-meta">
            <div class="brand">
                <div class="logo">HD</div>
                <div class="brand-copy">
                    <strong>HDSPTV</strong>
                    <span><?php echo HS_Helpers::esc($settings['tagline'] ?? 'Global • India • Kerala'); ?></span>
                </div>
            </div>
            <div class="meta-actions">
                <span class="meta-chip">Live Updates</span>
                <span class="meta-chip"><?php echo date('l, M j, Y'); ?></span>
                <div class="search-box">
                    <form action="<?php echo hs_search_url(); ?>" method="get">
                        <input type="text" name="q" placeholder="Search news, teams, topics..." aria-label="Search">
                        <button type="submit">Go</button>
                    </form>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <?php foreach ($navItems as $item): ?>
                <a href="<?php echo HS_Helpers::esc($item['url']); ?>" class="nav-pill"><?php echo HS_Helpers::esc($item['label']); ?></a>
            <?php endforeach; ?>
        </nav>
    </header>

    <main class="page-main">
        <?php include $view; ?>
    </main>

    <footer class="site-footer">
        <div class="footer-meta">
            <div>
                <strong>HDSPTV</strong> — Worldwide, India, Kerala & Malayalam stories with depth.
            </div>
            <div class="footer-links">
                <a href="<?php echo hs_base_url('privacy.php'); ?>">Privacy</a>
                <a href="<?php echo hs_base_url('terms.php'); ?>">Terms</a>
                <a href="<?php echo hs_base_url('contact.php'); ?>">Contact</a>
            </div>
        </div>
        <p>© <?php echo date('Y'); ?> HDSPTV News Network.</p>
    </footer>
</div>
<script>
(function(){
    const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
    const thumbs = Array.from(document.querySelectorAll('[data-hero-thumb]'));
    function activate(idx){
        slides.forEach(s => s.classList.toggle('active', s.dataset.heroSlide === String(idx)));
        thumbs.forEach(t => t.classList.toggle('active', t.dataset.heroThumb === String(idx)));
    }
    thumbs.forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            activate(btn.dataset.heroThumb);
        });
    });
})();
</script>
</body>
</html>
