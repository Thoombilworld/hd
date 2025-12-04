<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Trending Now</h2>
        <span>Most read across the network</span>
    </div>
    <?php if (!empty($trending)): ?>
        <div class="trending-strip">
            <?php foreach ($trending as $post): ?>
                <?php $img = !empty($post['image_main']) ? $post['image_main'] : HS_Helpers::placeholder(360, 220, 'Trending'); ?>
                <a class="trending-card" href="<?php echo hs_news_url($post['slug']); ?>">
                    <div class="thumb" style="background-image:url('<?php echo HS_Helpers::esc($img); ?>');"></div>
                    <div class="body">
                        <p class="eyebrow">Views <?php echo number_format($post['view_count'] ?? 0); ?></p>
                        <h4><?php echo HS_Helpers::esc($post['title']); ?></h4>
                        <span class="muted">Updated <?php echo date('M j', strtotime($post['created_at'] ?? 'now')); ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="muted">No trending posts yet.</p>
    <?php endif; ?>
</div>
