<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Videos • Live</h2>
        <span>Clips & live coverage</span>
    </div>
    <?php $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="340" height="220" viewBox="0 0 340 220" fill="none"><rect width="340" height="220" fill="#0f172a"/><text x="50%" y="50%" fill="#334155" font-size="22" font-family="Inter" dominant-baseline="middle" text-anchor="middle">Video</text></svg>'); ?>
    <div class="video-grid">
        <?php foreach ($videos as $video): ?>
            <a class="video-card" href="<?php echo hs_news_url($video['slug']); ?>">
                <?php $img = !empty($video['image_main']) ? $video['image_main'] : $placeholder; ?>
                <img src="<?php echo HS_Helpers::esc($img); ?>" alt="<?php echo HS_Helpers::esc($video['title']); ?>">
                <div class="overlay">
                    <div class="play">▶</div>
                    <strong><?php echo HS_Helpers::esc($video['title']); ?></strong>
                    <span class="hero-meta">Stream | <?php echo HS_Helpers::esc($video['video_url'] ?? 'HD'); ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
