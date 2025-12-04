<?php
require_once __DIR__ . '/../../bootstrap.php';

foreach (($_POST['order'] ?? []) as $id => $order) {
    $enabled = isset($_POST['enabled'][$id]) ? 1 : 0;
    $settings = $_POST['settings'][$id] ?? '{}';
    HS_DB::query("UPDATE hs_homepage_blocks SET sort_order=?, enabled=?, settings=? WHERE id=?", [
        (int)$order,
        $enabled,
        $settings,
        (int)$id
    ]);
}
header('Location: index.php');
exit;
