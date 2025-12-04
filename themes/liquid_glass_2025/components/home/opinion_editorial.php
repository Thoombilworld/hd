<section class="wnc-section" aria-label="Opinion and Editorial">
    <div class="wnc-section__head">
        <h2>Opinion & Editorial</h2>
        <a class="wnc-link" href="<?= hs_category_url('opinion'); ?>">More opinions</a>
    </div>
    <div class="wnc-grid cards-3">
        <?php foreach ($opinionPosts as $story): ?>
            <article class="wnc-card">
                <div class="wnc-meta-row">
                    <span class="wnc-chip ghost">Opinion</span>
                    <?php if (!empty($story['author'])): ?><span class="wnc-author">by <?= htmlspecialchars($story['author']); ?></span><?php endif; ?>
                </div>
                <h3><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></h3>
                <?php if (!empty($story['excerpt'])): ?><p><?= htmlspecialchars($story['excerpt']); ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
