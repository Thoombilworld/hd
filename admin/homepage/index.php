<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../core/HomepageBuilder.php';

$blocks = HS_DB::fetchAll("SELECT * FROM hs_homepage_blocks ORDER BY sort_order ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Homepage Builder</title></head>
<body>
<h1>Homepage Blocks</h1>
<form method="post" action="save.php">
    <table border="1" cellpadding="8">
        <tr><th>Order</th><th>Key</th><th>Enabled</th><th>Settings (JSON)</th></tr>
        <?php foreach ($blocks as $block): ?>
            <tr>
                <td><input type="number" name="order[<?php echo (int)$block['id']; ?>]" value="<?php echo (int)$block['sort_order']; ?>" /></td>
                <td><?php echo HS_Helpers::esc($block['block_key']); ?></td>
                <td><input type="checkbox" name="enabled[<?php echo (int)$block['id']; ?>]" value="1" <?php echo $block['enabled'] ? 'checked' : ''; ?> /></td>
                <td><textarea name="settings[<?php echo (int)$block['id']; ?>]" rows="2" cols="40"><?php echo HS_Helpers::esc($block['settings']); ?></textarea></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit">Save Layout</button>
</form>
</body>
</html>
