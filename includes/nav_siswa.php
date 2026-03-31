<div class="topbar">
  <div class="brand">Pengaduan <span>Sarana</span> — Siswa</div>
  <nav class="nav-links">
    <a href="<?= htmlspecialchars(BASE_URL) ?>/siswa/form_aspirasi.php">Form Aspirasi</a>
    <a href="<?= htmlspecialchars(BASE_URL) ?>/siswa/status_siswa.php">Status</a>
    <a href="<?= htmlspecialchars(BASE_URL) ?>/siswa/riwayat_siswa.php">Riwayat</a>
    <?php
      $siswa_nama = $_SESSION['siswa_nama'] ?? '';
      $siswa_nis = $_SESSION['siswa_nis'] ?? '';
      $label_user = ($siswa_nama !== '' || $siswa_nis !== '')
          ? ($siswa_nama . ' (' . $siswa_nis . ')')
          : '';
    ?>
    <?php if ($label_user !== ''): ?>
      <span class="muted"><?= htmlspecialchars($label_user) ?></span>
    <?php else: ?>
      <a class="btn btn-small btn-primary" href="<?= htmlspecialchars(BASE_URL) ?>/login_admin.php">Login sebagai Admin</a>
    <?php endif; ?>
  </nav>
</div>
