<h2>Trending Tags</h2>
<ul class="inline">
<?php foreach ($tags as $tag): ?>
    <li><a href="<?php echo hs_tag_url($tag['slug']); ?>">#<?php echo HS_Helpers::esc($tag['name']); ?></a></li>
<?php endforeach; ?>
</ul>
