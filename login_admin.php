<?php
$judul_halaman = 'Login Admin';
require_once __DIR__ . '/includes/layout_start.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    if ($u === '' || $p === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $sql = "SELECT username FROM admin WHERE username='" . esc($conn, $u) . "' AND password='" . esc($conn, $p) . "' LIMIT 1";
        $res = $conn->query($sql);
        if ($res && $res->num_rows === 1) {
            $_SESSION['admin_user'] = $u;
            header('Location: ' . BASE_URL . '/admin/daftar_aspirasi.php');
            exit;
        }
        $error = 'Login gagal. Periksa username/password.';
    }
}

?>
<div class="card" style="max-width:420px;margin:2rem auto 0">
  <h1>Login Admin</h1>
  <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" required autocomplete="username">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn btn-primary">Masuk</button>
    <a class="btn btn-ghost" href="<?= htmlspecialchars(BASE_URL) ?>/index.php" style="margin-left:.5rem">Beranda</a>
  </form>
</div>
<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
