<section class="wnc-section" aria-label="Kerala and Malayalam">
    <div class="wnc-section__head">
        <h2>Kerala • മലയാളം</h2>
        <span class="wnc-chip ghost">Dense strip</span>
    </div>
    <div class="wnc-kerala">
        <div class="wnc-kerala__list">
            <?php foreach (array_slice($keralaStories, 0, 6) as $story): ?>
                <div class="wnc-kerala__item">
                    <div class="wnc-kerala__title"><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></div>
                    <div class="wnc-kerala__meta"><?= htmlspecialchars($story['region'] ?? 'Kerala'); ?> • <?= date('M j', strtotime($story['created_at'] ?? 'now')); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="wnc-kerala__cards">
            <?php foreach (array_slice($keralaStories, 6, 4) as $story): ?>
                <article class="wnc-mini-card">
                    <div class="thumb" style="background-image:url('<?= htmlspecialchars($story['image'] ?? hs_base_url('assets/img/thumb-fallback.jpg')); ?>')"></div>
                    <div class="body">
                        <span class="wnc-chip ghost">Kerala</span>
                        <h4><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></h4>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
