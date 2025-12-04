<h2>Opinion & Editorial</h2>
<ul>
<?php foreach ($opinions as $post): ?>
    <li><a href="<?php echo hs_news_url($post['slug']); ?>"><?php echo HS_Helpers::esc($post['title']); ?></a></li>
<?php endforeach; ?>
</ul>
