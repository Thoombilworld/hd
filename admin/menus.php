<?php
require __DIR__ . '/../bootstrap.php';
hs_require_admin();
require_once HS_ROOT . '/core/Menus.php';

$menus = new Menus(hs_db());
$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $menus->deleteItem((int)$_POST['delete']);
        $msg = 'Menu item removed.';
    } elseif (isset($_POST['reorder'])) {
        $menus->reorder($_POST['order'] ?? []);
        $msg = 'Menu order updated.';
    } else {
        $data = [
            'id'         => (int)($_POST['id'] ?? 0),
            'location'   => $_POST['location'] ?? 'header',
            'label'      => $_POST['label'] ?? '',
            'url'        => $_POST['url'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'visible'    => !empty($_POST['visible']) ? 1 : 0,
        ];
        if ($menus->saveItem($data)) {
            $msg = $data['id'] ? 'Menu item updated.' : 'Menu item added.';
        } else {
            $error = 'Label and URL are required.';
        }
    }
}

$items = $menus->getAll();
$locations = $menus->allLocations();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Menu Builder — NEWS HDSPTV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Menu Builder</h1>
            <p class="text-muted mb-0">Manage header, footer, mobile, and utility navigation.</p>
        </div>
    </div>
    <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Add / Edit Link</h2>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= (int)($_GET['edit'] ?? 0); ?>">
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <select class="form-select" name="location">
                                <?php foreach ($locations as $key => $label): ?>
                                    <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="label" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="url" placeholder="/category/world" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="visible" id="visible" checked>
                            <label class="form-check-label" for="visible">Visible</label>
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Current Items</h2>
                    <form method="post">
                        <input type="hidden" name="reorder" value="1">
                        <table class="table align-middle">
                            <thead>
                                <tr><th>Location</th><th>Label</th><th>URL</th><th width="120">Order</th><th class="text-end">Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($locations[$item['location']] ?? $item['location']) ?></td>
                                        <td><?= htmlspecialchars($item['label']) ?></td>
                                        <td><small><?= htmlspecialchars($item['url']) ?></small></td>
                                        <td>
                                            <input class="form-control form-control-sm" type="number" name="order[<?= (int)$item['id'] ?>]" value="<?= (int)$item['sort_order'] ?>">
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-secondary" href="?edit=<?= (int)$item['id'] ?>">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger" name="delete" value="<?= (int)$item['id'] ?>" onclick="return confirm('Delete this item?')">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <button class="btn btn-success" type="submit">Update Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
