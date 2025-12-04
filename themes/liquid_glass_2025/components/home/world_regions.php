<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">World Regions</h2>
        <span>Asia • GCC • Europe • Americas • Africa</span>
    </div>
    <div class="region-grid">
        <?php foreach ($regions as $region => $posts): ?>
            <div class="region-card">
                <h4><?php echo strtoupper($region); ?></h4>
                <ul>
                    <?php foreach ($posts as $p): ?>
                        <li><a href="<?php echo hs_news_url($p['slug']); ?>"><?php echo HS_Helpers::esc($p['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>
