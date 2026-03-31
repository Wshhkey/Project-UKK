<?php
$judul_halaman = 'Status Aspirasi';
require_once __DIR__ . '/../includes/layout_start.php';
require_once __DIR__ . '/../includes/functions.php';

$map_kat = ambil_map_kategori($conn);

/** Kumpulan status untuk penjelasan progres perbaikan (array). */
$keterangan_progres = [
    'Menunggu' => 'Laporan Anda sedang dalam antrean peninjauan admin.',
    'Proses' => 'Tim sarana sedang menangani atau memperbaiki.',
    'Selesai' => 'Perbaikan atau tindak lanjut telah selesai.',
];

$nis_input = trim($_POST['nis'] ?? $_GET['nis'] ?? '');
$nis = 0;
$error = '';
$rows = [];

if ($nis_input !== '') {
    if (!ctype_digit($nis_input)) {
        $error = 'NIS harus berupa angka.';
    } else {
      $nis = $nis_input;
      $qS = $conn->query("SELECT nis FROM siswa WHERE nis = '" . esc($conn, $nis) . "' LIMIT 1");
      if (!$qS || $qS->num_rows === 0) {
          $error = 'NIS tidak terdaftar.';
      } else {
          $sql = "SELECT i.id_pelaporan, i.lokasi, i.ket, i.tanggal, i.id_kategori AS ik_input,
                  a.status, a.feedback
                  FROM input_aspirasi i
                  JOIN aspirasi a ON a.id_pelaporan = i.id_pelaporan
                  WHERE i.nis = '" . esc($conn, $nis) . "'
                  ORDER BY i.tanggal ASC";
          $res = $conn->query($sql);
            if ($res) {
              while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
              }
            }
        }
    }
}

require_once __DIR__ . '/../includes/nav_siswa.php';
?>
<div class="card">
  <h1>Status &amp; Progres Perbaikan</h1>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($nis <= 0): ?>
    <form method="post" class="filters" style="grid-template-columns: 1fr auto; align-items:start">
      <div class="form-group" style="margin-bottom:0">
        <label>NIS</label>
        <input type="text" name="nis" inputmode="numeric" pattern="[0-9]+" maxlength="10" required value="<?= htmlspecialchars($nis_input) ?>">
      </div>
      <div class="form-group" style="margin-bottom:0">
        <label>&nbsp;</label>
        <button type="submit" class="btn btn-primary">Lihat Status</button>
      </div>
    </form>
  <?php endif; ?>

  <?php if ($nis > 0): ?>
    <?php if ($rows === []): ?>
      <p class="muted">Belum ada laporan untuk NIS tersebut.</p>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <div class="card" style="box-shadow:none;border-style:dashed">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.75rem">
            <div>
              <strong>#<?= (int)$r['id_pelaporan'] ?> — <?= htmlspecialchars($map_kat[(int)$r['ik_input']] ?? '-') ?></strong>
              <p class="muted" style="margin:.35rem 0"><?= htmlspecialchars($r['lokasi']) ?></p>
              <p><?= nl2br(htmlspecialchars($r['ket'])) ?></p>
            </div>
            <span class="badge <?= htmlspecialchars(kelas_badge_status($r['status'])) ?>"><?= htmlspecialchars($r['status']) ?></span>
          </div>
          <p class="muted" style="margin:.75rem 0 0"><?= htmlspecialchars($r['tanggal']) ?></p>
          <p style="margin:.5rem 0 0"><em><?= htmlspecialchars($keterangan_progres[$r['status']] ?? '') ?></em></p>
          <?php if (trim($r['feedback']) !== ''): ?>
            <hr class="soft">
            <strong>Umpan balik admin</strong>
            <p class="muted"><?= nl2br(htmlspecialchars($r['feedback'])) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
