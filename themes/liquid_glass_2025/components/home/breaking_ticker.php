<div class="section-block breaking-bar">
    <div class="breaking-label">Breaking</div>
    <div class="breaking-items">
        <?php if (!empty($breaking)): ?>
            <?php foreach ($breaking as $item): ?>
                <a href="<?php echo hs_news_url($item['slug']); ?>"><?php echo HS_Helpers::esc($item['title']); ?></a>
            <?php endforeach; ?>
        <?php else: ?>
            <span class="muted">Stay tuned for live updates.</span>
        <?php endif; ?>
    </div>
</div>
