<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">Trending Tags</h2>
        <span>What readers follow</span>
    </div>
    <div class="tags-cloud">
        <?php foreach ($tags as $tag): ?>
            <a class="tag-pill" href="<?php echo hs_tag_url($tag['slug']); ?>">#<?php echo HS_Helpers::esc($tag['name']); ?></a>
        <?php endforeach; ?>
    </div>
</div>
