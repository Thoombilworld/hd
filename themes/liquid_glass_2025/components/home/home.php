<div class="home-grid">
    <div class="home-main">
        <?php include __DIR__ . '/breaking_ticker.php'; ?>
        <?php include __DIR__ . '/hero_world.php'; ?>

        <div class="dual-grid">
            <?php include __DIR__ . '/india_focus.php'; ?>
            <?php include __DIR__ . '/kerala_malayalam.php'; ?>
        </div>

        <?php include __DIR__ . '/world_regions.php'; ?>
        <?php include __DIR__ . '/videos_live.php'; ?>
        <?php include __DIR__ . '/photo_gallery.php'; ?>
    </div>

    <aside class="home-rail">
        <div class="list-block">
            <h3>Latest pulse</h3>
            <ul>
                <?php foreach ($breaking as $item): ?>
                    <li><a href="<?php echo hs_news_url($item['slug']); ?>">➜ <?php echo HS_Helpers::esc($item['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php include __DIR__ . '/opinion.php'; ?>
        <?php include __DIR__ . '/trending_tags.php'; ?>
        <?php include __DIR__ . '/newsletter.php'; ?>
    </aside>
</div>
