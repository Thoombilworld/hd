<div class="section-block">
    <div class="section-title">
        <h2 style="margin:0;">India Focus</h2>
        <span>Nation | Parliament | Cities</span>
    </div>
    <?php $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="320" height="200" viewBox="0 0 320 200" fill="none"><rect width="320" height="200" fill="#0f172a"/><text x="50%" y="50%" fill="#334155" font-size="24" font-family="Inter" dominant-baseline="middle" text-anchor="middle">India</text></svg>'); ?>
    <div class="card-list">
        <?php foreach ($india as $item): ?>
            <a class="card-row" href="<?php echo hs_news_url($item['slug']); ?>">
                <?php $img = !empty($item['image_main']) ? $item['image_main'] : $placeholder; ?>
                <img src="<?php echo HS_Helpers::esc($img); ?>" alt="<?php echo HS_Helpers::esc($item['title']); ?>">
                <div>
                    <h4><?php echo HS_Helpers::esc($item['title']); ?></h4>
                    <small>Updated <?php echo date('M j', strtotime($item['created_at'] ?? 'now')); ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
