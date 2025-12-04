<section class="wnc-hero" aria-label="World headlines hero">
    <div class="wnc-hero__main">
        <?php $lead = $heroPosts[0] ?? null; if ($lead): ?>
            <div class="wnc-hero__image" style="background-image:url('<?= htmlspecialchars($lead['image'] ?? hs_base_url('assets/img/hero-fallback.jpg')); ?>')"></div>
            <div class="wnc-hero__content">
                <div class="wnc-chip">World</div>
                <h1><a href="<?= htmlspecialchars(hg_story_url($lead)); ?>"><?= htmlspecialchars($lead['title']); ?></a></h1>
                <?php if (!empty($lead['excerpt'])): ?><p><?= htmlspecialchars($lead['excerpt']); ?></p><?php endif; ?>
                <div class="wnc-meta-row">
                    <span><?= htmlspecialchars(strtoupper($lead['region'] ?? $lead['category'] ?? '')); ?></span>
                    <span>•</span>
                    <span><?= date('M j, Y', strtotime($lead['created_at'] ?? 'now')); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="wnc-hero__side">
        <?php foreach (array_slice($heroPosts, 1, 3) as $story): ?>
            <article class="wnc-side-card">
                <span class="wnc-chip ghost"><?= htmlspecialchars(strtoupper($story['region'] ?? $story['category'] ?? 'World')); ?></span>
                <h3><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></h3>
                <?php if (!empty($story['excerpt'])): ?><p><?= htmlspecialchars($story['excerpt']); ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
