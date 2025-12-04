<h2>Breaking</h2>
<div class="ticker">
    <?php foreach ($breaking as $item): ?>
        <a href="<?php echo hs_news_url($item['slug']); ?>">⚡ <?php echo HS_Helpers::esc($item['title']); ?></a>
    <?php endforeach; ?>
</div>
