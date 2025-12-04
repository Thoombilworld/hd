<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Photo Gallery</h2>
        <span>Visual highlights</span>
    </div>
    <?php $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="240" height="160" viewBox="0 0 240 160" fill="none"><rect width="240" height="160" fill="#0f172a"/><text x="50%" y="50%" fill="#334155" font-size="18" font-family="Inter" dominant-baseline="middle" text-anchor="middle">Photo</text></svg>'); ?>
    <div class="gallery-grid">
        <?php foreach ($gallery as $photo): ?>
            <a href="<?php echo hs_news_url($photo['slug']); ?>">
                <?php $img = !empty($photo['image_main']) ? $photo['image_main'] : $placeholder; ?>
                <img src="<?php echo HS_Helpers::esc($img); ?>" alt="<?php echo HS_Helpers::esc($photo['title']); ?>">
            </a>
        <?php endforeach; ?>
    </div>
</div>
