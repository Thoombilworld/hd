<?php
require dirname(__DIR__) . '/../bootstrap.php';
require_once HS_ROOT . '/core/HomepageBuilder.php';

$builder = new HomepageBuilder(hs_db());
$config = $builder->getConfig();

$blocks = [
    'show_breaking' => 'Breaking ticker',
    'show_hero_world' => 'Hero – World',
    'show_india_focus' => 'India focus',
    'show_kerala_malayalam' => 'Kerala / Malayalam band',
    'show_world_sections' => 'World regions',
    'show_videos_live' => 'Video + Live TV',
    'show_opinion' => 'Opinion & Editorial',
    'show_photo_gallery' => 'Photo gallery',
    'show_trending_tags' => 'Trending tags',
    'show_newsletter' => 'Newsletter CTA',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Layout — NEWS HDSPTV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body{background:#f5f7fb;font-family:'Inter',system-ui,sans-serif;}
        .card{border-radius:16px;box-shadow:0 20px 50px rgba(15,23,42,0.08);border:1px solid #e5e7eb;}
        .section-title{font-weight:700;color:#0f172a;}
        .switcher{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px dashed #e2e8f0;}
        .badge-soft{background:#e0f2fe;color:#0f172a;font-weight:600;}
    </style>
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Homepage Builder</h1>
            <p class="text-muted mb-0">Toggle blocks, set limits, and tune Kerala/Malayalam density for the World News Captain experience.</p>
        </div>
        <span class="badge badge-soft">World + Malayalam layout</span>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status']==='saved'): ?>
        <div class="alert alert-success">Homepage layout saved.</div>
    <?php endif; ?>

    <form method="post" action="save.php" class="row g-4">
        <div class="col-lg-6">
            <div class="card p-3">
                <h2 class="section-title h5 mb-3">Blocks</h2>
                <?php foreach ($blocks as $key => $label): ?>
                    <label class="switcher">
                        <input type="checkbox" name="<?= htmlspecialchars($key); ?>" value="1" <?= !empty($config[$key]) ? 'checked' : ''; ?>>
                        <span><?= htmlspecialchars($label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="card p-3 mt-3">
                <h2 class="section-title h5 mb-3">Kerala / Malayalam</h2>
                <div class="mb-3">
                    <label class="form-label">Language mode</label>
                    <select class="form-select" name="kerala_language_mode">
                        <option value="mixed" <?= ($config['kerala_language_mode'] ?? '') === 'mixed' ? 'selected' : ''; ?>>Mixed (English + Malayalam)</option>
                        <option value="ml" <?= ($config['kerala_language_mode'] ?? '') === 'ml' ? 'selected' : ''; ?>>Malayalam only</option>
                        <option value="en" <?= ($config['kerala_language_mode'] ?? '') === 'en' ? 'selected' : ''; ?>>English only</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kerala stories</label>
                    <input type="number" min="3" max="20" class="form-control" name="kerala_limit" value="<?= (int)($config['kerala_limit'] ?? 8); ?>">
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-3">
                <h2 class="section-title h5 mb-3">Story limits</h2>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Hero (World)</label>
                        <input type="number" min="3" max="12" class="form-control" name="hero_limit" value="<?= (int)($config['hero_limit'] ?? 5); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Breaking ticker</label>
                        <input type="number" min="3" max="25" class="form-control" name="breaking_limit" value="<?= (int)($config['breaking_limit'] ?? 8); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">India</label>
                        <input type="number" min="3" max="20" class="form-control" name="india_limit" value="<?= (int)($config['india_limit'] ?? 6); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">World (per region)</label>
                        <input type="number" min="3" max="12" class="form-control" name="region_limit" value="<?= (int)($config['region_limit'] ?? 5); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Video</label>
                        <input type="number" min="2" max="12" class="form-control" name="video_limit" value="<?= (int)($config['video_limit'] ?? 4); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Opinion</label>
                        <input type="number" min="2" max="12" class="form-control" name="opinion_limit" value="<?= (int)($config['opinion_limit'] ?? 4); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Photo stories</label>
                        <input type="number" min="3" max="18" class="form-control" name="photo_limit" value="<?= (int)($config['photo_limit'] ?? 6); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Trending tags</label>
                        <input type="number" min="6" max="30" class="form-control" name="tags_limit" value="<?= (int)($config['tags_limit'] ?? 12); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button class="btn btn-primary px-4" type="submit">Save homepage</button>
        </div>
    </form>
</body>
</html>
