<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Kerala • മലയാളം</h2>
        <span>Local pulse in two languages</span>
    </div>
    <?php $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="420" height="260" viewBox="0 0 420 260" fill="none"><rect width="420" height="260" fill="#0f172a"/><text x="50%" y="50%" fill="#334155" font-size="28" font-family="Inter" dominant-baseline="middle" text-anchor="middle">Kerala</text></svg>'); ?>
    <div class="dual-grid">
        <?php foreach ($kerala as $story): ?>
            <a class="story-card" href="<?php echo hs_news_url($story['slug']); ?>">
                <?php $img = !empty($story['image_main']) ? $story['image_main'] : $placeholder; ?>
                <img src="<?php echo HS_Helpers::esc($img); ?>" alt="<?php echo HS_Helpers::esc($story['title']); ?>">
                <div class="body">
                    <div class="meta">Kerala Desk • മലയാളം</div>
                    <strong><?php echo HS_Helpers::esc($story['title']); ?></strong>
                    <div class="meta">Updated <?php echo date('M j, g:i a', strtotime($story['created_at'] ?? 'now')); ?></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
