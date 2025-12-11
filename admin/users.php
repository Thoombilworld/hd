<?php
require __DIR__ . '/../bootstrap.php';
hs_require_staff(['admin']);
$db = hs_db();
$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = $_POST['role'] ?? 'editor';
    $status = $_POST['status'] ?? 'active';
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '') {
        $error = 'Name and email are required.';
    }

    if (!$error && $id === 0 && $password === '') {
        $error = 'Password required for new user.';
    }

    if (!$error) {
        if ($id > 0) {
            $stmt = mysqli_prepare($db, "UPDATE hs_users SET name=?, email=?, role=?, status=? WHERE id=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ssssi', $name, $email, $role, $status, $id);
            mysqli_stmt_execute($stmt);
            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $pstmt = mysqli_prepare($db, "UPDATE hs_users SET password_hash=? WHERE id=? LIMIT 1");
                mysqli_stmt_bind_param($pstmt, 'si', $hash, $id);
                mysqli_stmt_execute($pstmt);
            }
            $msg = 'User updated.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = mysqli_prepare($db, "INSERT INTO hs_users (name, email, password_hash, role, status) VALUES (?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $hash, $role, $status);
            if (!@mysqli_stmt_execute($stmt)) {
                $error = 'Email already in use.';
            } else {
                $msg = 'User created.';
            }
        }
    }
}

if (isset($_GET['deactivate'])) {
    $id = (int)$_GET['deactivate'];
    mysqli_query($db, "UPDATE hs_users SET status='inactive' WHERE id={$id} LIMIT 1");
    header('Location: users.php');
    exit;
}

$res = mysqli_query($db, "SELECT id, name, email, role, status, created_at FROM hs_users ORDER BY created_at DESC");
$staff = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Staff Users – NEWS HDSPTV</title>
  <link rel="stylesheet" href="<?= hs_base_url('assets/css/style.css') ?>">
</head>
<body style="max-width:960px;margin:20px auto;padding:0 16px;">
  <h1>Staff Users (Admin / Editor / Reporter)</h1>
  <?php if ($msg): ?><div style="color:green;" role="status"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if ($error): ?><div style="color:#b91c1c;" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" style="margin:14px 0; padding:12px; border:1px solid #e5e7eb; border-radius:10px;">
    <h2 style="margin:0 0 10px;">Create / Update</h2>
    <input type="hidden" name="id" value="<?= (int)($_GET['edit'] ?? 0); ?>">
    <label>Name</label>
    <input type="text" name="name" style="width:100%;" required value="<?= htmlspecialchars($_GET['name'] ?? '') ?>"><br>
    <label>Email</label>
    <input type="email" name="email" style="width:100%;" required value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"><br>
    <label>Role</label>
    <select name="role">
        <?php foreach (['admin'=>'Admin','editor'=>'Editor','reporter'=>'Reporter'] as $key=>$label): ?>
            <option value="<?= $key ?>"><?= $label ?></option>
        <?php endforeach; ?>
    </select>
    <label>Status</label>
    <select name="status">
        <?php foreach (['active'=>'Active','inactive'=>'Inactive','pending'=>'Pending'] as $key=>$label): ?>
            <option value="<?= $key ?>"><?= $label ?></option>
        <?php endforeach; ?>
    </select>
    <label>Password (leave blank to keep current)</label>
    <input type="password" name="password" style="width:100%;"><br>
    <button type="submit">Save User</button>
  </form>

  <table border="1" cellpadding="4" cellspacing="0" width="100%">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr>
    <?php foreach ($staff as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td><?= htmlspecialchars($u['status']) ?></td>
        <td><?= htmlspecialchars($u['created_at']) ?></td>
        <td>
            <a href="?edit=<?= (int)$u['id'] ?>&name=<?= urlencode($u['name']) ?>&email=<?= urlencode($u['email']) ?>">Edit</a>
            <?php if ($u['status'] !== 'inactive'): ?>
                | <a href="?deactivate=<?= (int)$u['id'] ?>">Deactivate</a>
            <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
