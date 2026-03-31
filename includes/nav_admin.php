<div class="topbar">
  <div class="brand">Pengaduan <span>Sarana</span> — Admin</div>
  <nav class="nav-links">
    <a href="<?= htmlspecialchars(BASE_URL) ?>/admin/daftar_aspirasi.php">Daftar Aspirasi</a>
    <a href="<?= htmlspecialchars(BASE_URL) ?>/admin/riwayat.php">Riwayat</a>
    <span class="muted"><?= htmlspecialchars($_SESSION['admin_user'] ?? '') ?></span>
    <a class="btn btn-small btn-ghost" href="<?= htmlspecialchars(BASE_URL) ?>/logout.php">Keluar</a>
  </nav>
</div>
