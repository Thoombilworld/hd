<?php
$seo = HS_SEO::meta($meta ?? []);
?><!DOCTYPE html>
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
<header class="site-header">
    <div class="brand">HDSPTV</div>
    <nav class="nav">
        <a href="<?php echo HS_BASE_URL; ?>">Home</a>
        <a href="<?php echo hs_category_url('india'); ?>">India</a>
        <a href="<?php echo hs_category_url('kerala'); ?>">Kerala</a>
        <a href="<?php echo hs_category_url('world'); ?>">World</a>
    </nav>
</header>
<main>
    <?php include $view; ?>
</main>
<footer class="site-footer">
    <p>© <?php echo date('Y'); ?> HDSPTV News</p>
</footer>
</body>
</html>
