<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Top of the world</h2>
        <span>Global | India | Kerala</span>
    </div>
    <?php $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="720" viewBox="0 0 1200 720" fill="none"><rect width="1200" height="720" fill="#0f172a"/><text x="50%" y="50%" fill="#334155" font-size="42" font-family="Inter" dominant-baseline="middle" text-anchor="middle">HDSPTV</text></svg>'); ?>
    <div class="hero-shell">
        <div class="hero-slides">
            <?php foreach ($hero as $index => $post): ?>
                <a class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-hero-slide="<?php echo $index; ?>" href="<?php echo hs_news_url($post['slug']); ?>">
                    <?php $img = !empty($post['image_main']) ? $post['image_main'] : $placeholder; ?>
                    <div class="hero-image" style="background-image:url('<?php echo HS_Helpers::esc($img); ?>');"></div>
                    <div class="hero-overlay">
                        <span class="hero-kicker">Lead Story</span>
                        <h3 class="hero-title"><?php echo HS_Helpers::esc($post['title']); ?></h3>
                        <div class="hero-meta">
                            <span>Updated <?php echo date('M j', strtotime($post['created_at'] ?? 'now')); ?></span>
                            <span>Views <?php echo number_format($post['view_count'] ?? 0); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="hero-thumbs">
            <?php foreach ($hero as $index => $post): ?>
                <button class="hero-thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-hero-thumb="<?php echo $index; ?>">
                    <?php $img = !empty($post['image_main']) ? $post['image_main'] : $placeholder; ?>
                    <img src="<?php echo HS_Helpers::esc($img); ?>" alt="<?php echo HS_Helpers::esc($post['title']); ?>">
                    <div>
                        <p class="thumb-title"><?php echo HS_Helpers::esc($post['title']); ?></p>
                        <span><?php echo HS_Helpers::esc($post['excerpt'] ?? 'Full coverage'); ?></span>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>
