<section class="wnc-breaking" aria-label="Breaking news">
    <div class="wnc-breaking__label">Breaking</div>
    <div class="wnc-breaking__list">
        <?php foreach ($breakingNews as $item): ?>
            <a href="<?= htmlspecialchars(hg_story_url($item)); ?>" class="wnc-breaking__item" title="<?= htmlspecialchars($item['title']); ?>">
                <?= htmlspecialchars($item['title']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
