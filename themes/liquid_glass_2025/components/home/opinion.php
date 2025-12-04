<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Opinion & Editorial</h2>
        <span>Analysis & perspectives</span>
    </div>
    <div class="card-list">
        <?php foreach ($opinions as $op): ?>
            <a class="card-row" href="<?php echo hs_news_url($op['slug']); ?>">
                <div style="width:60px;height:60px;border-radius:50%;background:var(--chip);display:grid;place-items:center;font-weight:700;color:var(--accent);">Op</div>
                <div>
                    <h4><?php echo HS_Helpers::esc($op['title']); ?></h4>
                    <small><?php echo HS_Helpers::esc($op['excerpt'] ?? 'Editorial insight'); ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
