<div class="section-block ribbon-block">
    <div class="section-title">
        <h2 style="margin:0;">Top Featured</h2>
        <span>Automatic slider • Editors' picks</span>
    </div>
    <?php if (!empty($featured)): ?>
        <div class="carousel" data-carousel>
            <div class="carousel-track">
                <?php $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="960" height="540" viewBox="0 0 960 540" fill="none"><rect width="960" height="540" fill="#0f172a"/><text x="50%" y="50%" fill="#334155" font-size="38" font-family="Inter" dominant-baseline="middle" text-anchor="middle">HDSPTV Featured</text></svg>'); ?>
                <?php foreach ($featured as $idx => $post): ?>
                    <?php $img = !empty($post['image_main']) ? $post['image_main'] : $placeholder; ?>
                    <a class="carousel-slide<?php echo $idx === 0 ? ' active' : ''; ?>" data-carousel-slide="<?php echo $idx; ?>" href="<?php echo hs_news_url($post['slug']); ?>">
                        <div class="carousel-media" style="background-image:url('<?php echo HS_Helpers::esc($img); ?>');"></div>
                        <div class="carousel-copy">
                            <p class="eyebrow">Featured · <?php echo date('M j', strtotime($post['created_at'] ?? 'now')); ?></p>
                            <h3><?php echo HS_Helpers::esc($post['title']); ?></h3>
                            <p class="muted"><?php echo HS_Helpers::esc($post['excerpt'] ?? 'Full story'); ?></p>
                            <div class="micro">Views <?php echo number_format($post['view_count'] ?? 0); ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="carousel-thumbs">
                <?php foreach ($featured as $idx => $post): ?>
                    <button class="carousel-dot<?php echo $idx === 0 ? ' active' : ''; ?>" data-carousel-thumb="<?php echo $idx; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p class="muted">No featured stories yet.</p>
    <?php endif; ?>
</div>
