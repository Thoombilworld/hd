<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Video Shorts</h2>
        <span>Sidebar quick watch</span>
    </div>
    <?php if (!empty($videos)): ?>
        <div class="video-widget">
            <?php foreach ($videos as $clip): ?>
                <?php $img = !empty($clip['image_main']) ? $clip['image_main'] : HS_Helpers::placeholder(320, 180, 'Video'); ?>
                <a class="video-widget-row" href="<?php echo hs_news_url($clip['slug']); ?>">
                    <div class="thumb" style="background-image:url('<?php echo HS_Helpers::esc($img); ?>');"></div>
                    <div class="body">
                        <p class="eyebrow">Live / Video</p>
                        <h4><?php echo HS_Helpers::esc($clip['title']); ?></h4>
                        <span class="muted"><?php echo date('M j', strtotime($clip['created_at'] ?? 'now')); ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="muted">No videos yet.</p>
    <?php endif; ?>
</div>
