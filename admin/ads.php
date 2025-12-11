<?php
require __DIR__ . '/../bootstrap.php';
hs_require_admin();
$db = hs_db();
$msg = '';
$error = '';

// Ensure new columns exist for scheduling and targeting
if (!hs_table_has_columns('hs_ads', ['start_date', 'end_date', 'device_target', 'notes'], $db)) {
    mysqli_query($db, "ALTER TABLE hs_ads ADD COLUMN start_date DATE NULL AFTER active,
        ADD COLUMN end_date DATE NULL AFTER start_date,
        ADD COLUMN device_target ENUM('all','desktop','mobile') NOT NULL DEFAULT 'all' AFTER end_date,
        ADD COLUMN notes VARCHAR(255) NULL AFTER device_target");
}

if (isset($_GET['delete'])) {
    $delId = (int) $_GET['delete'];
    mysqli_query($db, "DELETE FROM hs_ads WHERE id = {$delId} LIMIT 1");
    $msg = 'Ad deleted.';
    header('Location: ' . hs_base_url('admin/ads.php?msg=' . urlencode($msg)));
    exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $res = mysqli_query($db, "SELECT * FROM hs_ads WHERE id = {$editId} LIMIT 1");
    $editing = $res ? mysqli_fetch_assoc($res) : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) ($_POST['id'] ?? 0);
    $slot   = trim($_POST['slot'] ?? 'homepage_right');
    $image  = trim($_POST['image_url'] ?? '');
    $link   = trim($_POST['link_url'] ?? '');
    $active = !empty($_POST['active']) ? 1 : 0;
    $code   = trim($_POST['code'] ?? '');
    $start  = $_POST['start_date'] ?? null;
    $end    = $_POST['end_date'] ?? null;
    $device = $_POST['device_target'] ?? 'all';
    $notes  = $_POST['notes'] ?? '';

    if ($slot === '') {
        $error = 'Slot is required.';
    }

    if (!$error) {
        if ($id > 0) {
            $stmt = mysqli_prepare($db, "UPDATE hs_ads SET slot = ?, image_url = ?, link_url = ?, active = ?, code = ?, start_date = ?, end_date = ?, device_target = ?, notes = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'sssisssssi', $slot, $image, $link, $active, $code, $start, $end, $device, $notes, $id);
            mysqli_stmt_execute($stmt);
            $msg = 'Ad updated.';
        } else {
            $stmt = mysqli_prepare($db, "INSERT INTO hs_ads (slot, image_url, link_url, active, code, start_date, end_date, device_target, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE image_url = VALUES(image_url), link_url = VALUES(link_url), active = VALUES(active), code = VALUES(code), start_date=VALUES(start_date), end_date=VALUES(end_date), device_target=VALUES(device_target), notes=VALUES(notes)");
            mysqli_stmt_bind_param($stmt, 'sssisssss', $slot, $image, $link, $active, $code, $start, $end, $device, $notes);
            mysqli_stmt_execute($stmt);
            $msg = 'Ad saved.';
        }

        header('Location: ' . hs_base_url('admin/ads.php?msg=' . urlencode($msg)));
        exit;
    }
}

$slots = hs_ad_slots();
$res = mysqli_query($db, "SELECT * FROM hs_ads ORDER BY slot ASC");
$ads = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Ads Manager – NEWS HDSPTV</title>
  <link rel="stylesheet" href="<?= hs_base_url('assets/css/style.css') ?>">
  <style>
    body { max-width: 1100px; margin: 20px auto; padding: 0 16px; font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { border: 1px solid #e5e7eb; padding: 8px; font-size: 14px; }
    th { background: #f9fafb; text-align: left; }
    .actions a { margin-right: 8px; }
    .msg { color: green; margin: 8px 0; }
    .error { color: #b91c1c; margin: 8px 0; }
    label { font-weight: 600; display: block; margin-top: 10px; }
    input[type="text"], select, textarea, input[type="date"] { width: 100%; padding: 8px; box-sizing: border-box; }
    textarea { min-height: 90px; }
  </style>
</head>
<body>
  <h1>Banner Ads Manager</h1>
  <?php if (!empty($_GET['msg'])): ?><div class="msg"><?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>
  <?php if ($msg && empty($_GET['msg'])): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post">
    <input type="hidden" name="id" value="<?= $editing['id'] ?? 0 ?>">
    <label>Slot</label>
    <select name="slot">
      <?php foreach ($slots as $key => $label): ?>
        <option value="<?= htmlspecialchars($key) ?>" <?= (($editing['slot'] ?? '') === $key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Ad Image URL (uploaded path)</label>
    <input type="text" name="image_url" value="<?= htmlspecialchars($editing['image_url'] ?? '') ?>">

    <label>Click Link URL</label>
    <input type="text" name="link_url" value="<?= htmlspecialchars($editing['link_url'] ?? '') ?>">

    <label>Custom Embed Code (optional)</label>
    <textarea name="code" placeholder="Paste script or HTML snippet"><?= htmlspecialchars($editing['code'] ?? '') ?></textarea>

    <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
        <div>
            <label>Start date</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($editing['start_date'] ?? '') ?>">
        </div>
        <div>
            <label>End date</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($editing['end_date'] ?? '') ?>">
        </div>
        <div>
            <label>Device Target</label>
            <select name="device_target">
                <?php foreach (['all' => 'All', 'desktop' => 'Desktop', 'mobile' => 'Mobile'] as $key => $label): ?>
                    <option value="<?= $key ?>" <?= (($editing['device_target'] ?? 'all') === $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <label>Internal Notes</label>
    <input type="text" name="notes" value="<?= htmlspecialchars($editing['notes'] ?? '') ?>" placeholder="Campaign owner or tags">

    <label><input type="checkbox" name="active" value="1" <?= (!empty($editing['active']) || $editing === null) ? 'checked' : '' ?>> Active</label>

    <button type="submit">Save Ad</button>
  </form>

  <h2>Current Ads</h2>
  <table>
    <tr><th>Slot</th><th>Image</th><th>Link</th><th>Device</th><th>Window</th><th>Status</th><th>Notes</th><th>Actions</th></tr>
    <?php foreach ($ads as $ad): ?>
      <tr>
        <td><?= htmlspecialchars($slots[$ad['slot']] ?? $ad['slot']) ?></td>
        <td><?= htmlspecialchars($ad['image_url']) ?></td>
        <td><?= htmlspecialchars($ad['link_url']) ?></td>
        <td><?= htmlspecialchars($ad['device_target'] ?? 'all') ?></td>
        <td><?= htmlspecialchars(($ad['start_date'] ?? '') . ' → ' . ($ad['end_date'] ?? '')) ?></td>
        <td><?= !empty($ad['active']) ? 'Active' : 'Hidden' ?></td>
        <td><?= htmlspecialchars($ad['notes'] ?? '') ?></td>
        <td class="actions">
          <a href="<?= hs_base_url('admin/ads.php?edit=' . urlencode($ad['id'])) ?>">Edit</a>
          <a href="<?= hs_base_url('admin/ads.php?delete=' . urlencode($ad['id'])) ?>" onclick="return confirm('Delete this ad?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
