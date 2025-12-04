<h2>Video & Live</h2>
<div class="hero-grid">
<?php foreach ($videos as $video): ?>
    <article class="card">
        <?php if (!empty($video['image_main'])): ?>
            <img src="<?php echo HS_Helpers::esc($video['image_main']); ?>" alt="<?php echo HS_Helpers::esc($video['title']); ?>">
        <?php endif; ?>
        <h3><a href="<?php echo hs_news_url($video['slug']); ?>"><?php echo HS_Helpers::esc($video['title']); ?></a></h3>
        <?php if (!empty($video['video_url'])): ?><p>🎥 <?php echo HS_Helpers::esc($video['video_url']); ?></p><?php endif; ?>
    </article>
<?php endforeach; ?>
</div>
