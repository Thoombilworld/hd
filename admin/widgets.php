<?php
require __DIR__ . '/../bootstrap.php';
hs_require_admin();
require_once HS_ROOT . '/core/Widgets.php';

$widgets = new Widgets(hs_db());
$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $widgets->delete((int)$_POST['delete']);
        $msg = 'Widget removed.';
    } elseif (isset($_POST['reorder'])) {
        $widgets->reorder($_POST['order'] ?? []);
        $msg = 'Widget order updated.';
    } else {
        $settings = [];
        if (!empty($_POST['settings_json'])) {
            $parsed = json_decode($_POST['settings_json'], true);
            $settings = is_array($parsed) ? $parsed : [];
        }
        $saved = $widgets->save([
            'id'          => (int)($_POST['id'] ?? 0),
            'widget_area' => $_POST['widget_area'] ?? 'homepage_body',
            'widget_type' => $_POST['widget_type'] ?? 'html',
            'title'       => $_POST['title'] ?? '',
            'settings'    => $settings,
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'active'      => !empty($_POST['active']),
        ]);
        if ($saved) {
            $msg = 'Widget saved.';
        } else {
            $error = 'Widget title or settings invalid.';
        }
    }
}

$list = $widgets->listAll();
$areas = $widgets->areas();
$types = $widgets->types();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Widget Builder — NEWS HDSPTV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Widget Builder</h1>
            <p class="text-muted mb-0">Drag-sort widgets for homepage and article sidebars.</p>
        </div>
    </div>
    <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Add / Edit Widget</h2>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= (int)($_GET['edit'] ?? 0); ?>">
                        <div class="mb-3">
                            <label class="form-label">Area</label>
                            <select class="form-select" name="widget_area">
                                <?php foreach ($areas as $key => $label): ?>
                                    <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Widget Type</label>
                            <select class="form-select" name="widget_type">
                                <?php foreach ($types as $key => $label): ?>
                                    <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Widget title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Settings (JSON)</label>
                            <textarea class="form-control" name="settings_json" rows="4" placeholder='{"limit":5,"tag":"kerala"}'></textarea>
                            <div class="form-text">Provide widget-specific configuration like tag filters or ad slot keys.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="active" id="active" checked>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <button class="btn btn-primary" type="submit">Save Widget</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Current Widgets</h2>
                    <form method="post">
                        <input type="hidden" name="reorder" value="1">
                        <table class="table align-middle">
                            <thead><tr><th>Area</th><th>Type</th><th>Title</th><th width="120">Order</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($list as $widget): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($areas[$widget['widget_area']] ?? $widget['widget_area']) ?></td>
                                        <td><?= htmlspecialchars($types[$widget['widget_type']] ?? $widget['widget_type']) ?></td>
                                        <td><?= htmlspecialchars($widget['title'] ?? '') ?></td>
                                        <td><input class="form-control form-control-sm" type="number" name="order[<?= (int)$widget['id'] ?>]" value="<?= (int)$widget['sort_order'] ?>"></td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-secondary" href="?edit=<?= (int)$widget['id'] ?>">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger" name="delete" value="<?= (int)$widget['id'] ?>" onclick="return confirm('Delete this widget?')">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end"><button class="btn btn-success" type="submit">Update Order</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
