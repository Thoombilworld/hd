<section class="wnc-section" aria-label="India focus">
    <div class="wnc-section__head">
        <h2>India Focus</h2>
        <a class="wnc-link" href="<?= hs_category_url('india'); ?>">View all</a>
    </div>
    <div class="wnc-grid cards-3">
        <?php foreach ($indiaStories as $story): ?>
            <article class="wnc-card">
                <span class="wnc-chip ghost"><?= htmlspecialchars(strtoupper($story['category'] ?? 'India')); ?></span>
                <h3><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></h3>
                <?php if (!empty($story['excerpt'])): ?><p><?= htmlspecialchars($story['excerpt']); ?></p><?php endif; ?>
                <div class="wnc-meta-row">
                    <span><?= date('M j', strtotime($story['created_at'] ?? 'now')); ?></span>
                    <span class="dot"></span>
                    <span><?= htmlspecialchars($story['region'] ?? 'India'); ?></span>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
