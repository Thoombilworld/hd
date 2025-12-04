<h2>World Highlights</h2>
<div class="hero-grid">
<?php foreach ($hero as $post): ?>
    <article class="card">
        <?php if (!empty($post['image_main'])): ?>
            <img src="<?php echo HS_Helpers::esc($post['image_main']); ?>" alt="<?php echo HS_Helpers::esc($post['title']); ?>">
        <?php endif; ?>
        <h3><a href="<?php echo hs_news_url($post['slug']); ?>"><?php echo HS_Helpers::esc($post['title']); ?></a></h3>
        <p><?php echo HS_Helpers::esc($post['excerpt'] ?? ''); ?></p>
    </article>
<?php endforeach; ?>
</div>
