<section class="wnc-section" aria-label="Photo stories">
    <div class="wnc-section__head">
        <h2>Photo Stories</h2>
        <span class="wnc-link">Visual journalism</span>
    </div>
    <div class="wnc-gallery">
        <?php foreach ($photos as $story): ?>
            <figure class="wnc-gallery__item">
                <div class="img" style="background-image:url('<?= htmlspecialchars($story['image'] ?? hs_base_url('assets/img/photo-fallback.jpg')); ?>')"></div>
                <figcaption><a href="<?= htmlspecialchars(hg_story_url($story)); ?>"><?= htmlspecialchars($story['title']); ?></a></figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
