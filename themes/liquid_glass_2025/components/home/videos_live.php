<section class="wnc-section" aria-label="Videos and live">
    <div class="wnc-section__head">
        <h2>Video + Live TV</h2>
        <span class="wnc-chip ghost">Live visuals</span>
    </div>
    <div class="wnc-video">
        <div class="wnc-video__player">
            <?php $primary = $videos[0] ?? null; if ($primary): ?>
                <div class="wnc-video__frame">
                    <div class="wnc-live-badge">LIVE</div>
                    <img src="<?= htmlspecialchars($primary['image'] ?? hs_base_url('assets/img/video-fallback.jpg')); ?>" alt="<?= htmlspecialchars($primary['title']); ?>">
                </div>
                <h3><?= htmlspecialchars($primary['title']); ?></h3>
                <div class="wnc-meta-row"><span><?= date('M j, Y', strtotime($primary['created_at'] ?? 'now')); ?></span></div>
            <?php endif; ?>
        </div>
        <div class="wnc-video__list">
            <?php foreach (array_slice($videos, 1) as $video): ?>
                <div class="wnc-video__item">
                    <div class="thumb" style="background-image:url('<?= htmlspecialchars($video['image'] ?? hs_base_url('assets/img/video-fallback.jpg')); ?>')"></div>
                    <div>
                        <div class="wnc-meta-row"><span><?= !empty($video['is_live']) ? 'LIVE' : 'Clip'; ?></span></div>
                        <a href="<?= htmlspecialchars(hg_story_url($video)); ?>" class="wnc-video__title"><?= htmlspecialchars($video['title']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
