<?php
$judul_halaman = 'Umpan Balik Aspirasi';
require_once __DIR__ . '/../includes/layout_start.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . '/admin/daftar_aspirasi.php');
    exit;
}

$map_kat = ambil_map_kategori($conn);
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = trim($_POST['status'] ?? '');
    $feedback = trim($_POST['feedback'] ?? '');
    if (!status_valid($status)) {
        $pesan = 'Status tidak valid.';
    } else {
        $sql = "UPDATE aspirasi SET status='" . esc($conn, $status) . "', feedback='" . esc($conn, $feedback) . "' WHERE id_aspirasi=" . $id;
        if ($conn->query($sql)) {
            $pesan = 'ok';
        } else {
            $pesan = 'Gagal menyimpan: ' . $conn->error;
        }
    }
}

$sql = "SELECT a.id_aspirasi, a.status, a.feedback, a.id_pelaporan,
        i.id_pelaporan, i.nis, i.lokasi, i.ket, i.tanggal, i.id_pelaporan AS ik_input,
        s.kelas
        FROM aspirasi a
        JOIN input_aspirasi i ON i.id_pelaporan = a.id_pelaporan
        JOIN siswa s ON s.nis = i.nis
        WHERE a.id_aspirasi = " . $id . "
        LIMIT 1";
$res = $conn->query($sql);
$row = $res ? $res->fetch_assoc() : null;
if (!$row) {
    header('Location: ' . BASE_URL . '/admin/daftar_aspirasi.php');
    exit;
}

$daftar_status = daftar_status_aspirasi();
require_once __DIR__ . '/../includes/nav_admin.php';
?>
<div class="card">
  <h1>Umpan Balik &amp; Status</h1>
  <p class="muted">ID Aspirasi #<?= (int)$row['id_aspirasi'] ?> — Pelaporan #<?= (int)$row['id_pelaporan'] ?></p>

  <?php if ($pesan === 'ok'): ?>
    <div class="alert alert-success">Perubahan disimpan.</div>
  <?php elseif ($pesan !== ''): ?>
    <div class="alert alert-error"><?= htmlspecialchars($pesan) ?></div>
  <?php endif; ?>

  <div class="grid-2" style="margin-bottom:1rem">
    <div>
      <strong>Siswa</strong>
      <p class="muted">NIS <?= htmlspecialchars((string)$row['nis']) ?> — <?= htmlspecialchars($row['kelas']) ?></p>
      <strong>Kategori</strong>
      <p class="muted"><?= htmlspecialchars($map_kat[(int)$row['ik_input']] ?? '-') ?></p>
      <strong>Lokasi</strong>
      <p class="muted"><?= htmlspecialchars($row['lokasi']) ?></p>
      <strong>Uraian</strong>
      <p class="muted"><?= nl2br(htmlspecialchars($row['ket'])) ?></p>
      <strong>Waktu lapor</strong>
      <p class="muted"><?= htmlspecialchars($row['tanggal']) ?></p>
    </div>
  </div>

  <hr class="soft">

  <form method="post">
    <div class="form-group">
      <label>Status penanganan</label>
      <select name="status" required>
        <?php foreach ($daftar_status as $k => $label): ?>
          <option value="<?= htmlspecialchars($k) ?>" <?= $row['status'] === $k ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Umpan balik untuk siswa</label>
      <textarea name="feedback" rows="5" maxlength="500" placeholder="Jelaskan tindak lanjut atau jawaban untuk siswa..."><?= htmlspecialchars($row['feedback']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a class="btn btn-ghost" href="<?= htmlspecialchars(BASE_URL) ?>/admin/daftar_aspirasi.php" style="margin-left:.5rem">Kembali</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
