<section class="wnc-section" aria-label="World by region">
    <div class="wnc-section__head">
        <h2>World Regions</h2>
        <span class="wnc-meta">Asia • GCC • Europe • Americas • Africa</span>
    </div>
    <div class="wnc-grid cards-2">
        <?php foreach ($worldSections as $region => $payload): ?>
            <div class="wnc-card">
                <div class="wnc-section__head tight">
                    <h3><?= htmlspecialchars($payload['label']); ?></h3>
                    <a class="wnc-link" href="<?= hs_category_url($region); ?>">See all</a>
                </div>
                <div class="wnc-stack">
                    <?php foreach ($payload['stories'] as $story): ?>
                        <div class="wnc-stack__item">
                            <div class="wnc-stack__title"><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></div>
                            <div class="wnc-meta-row">
                                <span><?= htmlspecialchars($story['region'] ?? $payload['label']); ?></span>
                                <span class="dot"></span>
                                <span><?= date('M j', strtotime($story['created_at'] ?? 'now')); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
