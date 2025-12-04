<h2>World Regions</h2>
<?php foreach ($regions as $region => $posts): ?>
    <h3 style="text-transform:uppercase;"><?php echo HS_Helpers::esc($region); ?></h3>
    <div class="hero-grid">
        <?php foreach ($posts as $post): ?>
            <article class="card">
                <?php if (!empty($post['image_main'])): ?>
                    <img src="<?php echo HS_Helpers::esc($post['image_main']); ?>" alt="<?php echo HS_Helpers::esc($post['title']); ?>">
                <?php endif; ?>
                <h4><a href="<?php echo hs_news_url($post['slug']); ?>"><?php echo HS_Helpers::esc($post['title']); ?></a></h4>
            </article>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
