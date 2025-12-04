<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Category Highlights</h2>
        <span>10 spotlight beats</span>
    </div>
    <?php if (!empty($categoryHighlights)): ?>
        <div class="category-grid">
            <?php foreach ($categoryHighlights as $category): ?>
                <a class="category-card" href="<?php echo hs_category_url($category['slug']); ?>">
                    <div class="dot" style="background: <?php echo HS_Helpers::esc($category['color'] ?: '#3b82f6'); ?>"></div>
                    <div>
                        <h4><?php echo HS_Helpers::esc($category['name']); ?></h4>
                        <p class="muted"><?php echo HS_Helpers::esc($category['description'] ?: 'Latest updates'); ?></p>
                    </div>
                    <span class="chevron">›</span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="muted">No highlighted categories yet.</p>
    <?php endif; ?>
</div>
