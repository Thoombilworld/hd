<section class="wnc-section" aria-label="Trending tags">
    <div class="wnc-section__head">
        <h2>Trending Tags</h2>
        <span class="wnc-meta">Track the pulse</span>
    </div>
    <div class="wnc-tags">
        <?php foreach ($tags as $tag): ?>
            <a href="<?= htmlspecialchars(hs_tag_url($tag['slug'] ?? '')); ?>" class="wnc-tag">#<?= htmlspecialchars($tag['label'] ?? $tag['name'] ?? ''); ?></a>
        <?php endforeach; ?>
    </div>
</section>
