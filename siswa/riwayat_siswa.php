<?php
$judul_halaman = 'Riwayat Aspirasi';
require_once __DIR__ . '/../includes/layout_start.php';
require_once __DIR__ . '/../includes/functions.php';

$map_kat = ambil_map_kategori($conn);

$nis_input = trim($_POST['nis'] ?? $_GET['nis'] ?? '');
$nis = 0;
$error = '';
$data = [];

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
                    $data[] = $r;
                }
            }
        }
    }
}

require_once __DIR__ . '/../includes/nav_siswa.php';
?>
<div class="card">
  <h1>Riwayat Aspirasi</h1>

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
        <button type="submit" class="btn btn-primary">Lihat Riwayat</button>
      </div>
    </form>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Status</th>
            <th>Feedback</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($data === []): ?>
            <tr><td colspan="5" class="muted">Kosong.</td></tr>
          <?php endif; ?>
          <?php foreach ($data as $d): ?>
            <tr>
              <td><?= htmlspecialchars($d['tanggal']) ?></td>
              <td><?= htmlspecialchars($map_kat[(int)$d['ik_input']] ?? '-') ?></td>
              <td><?= htmlspecialchars($d['lokasi']) ?></td>
              <td><span class="badge <?= htmlspecialchars(kelas_badge_status($d['status'])) ?>"><?= htmlspecialchars($d['status']) ?></span></td>
              <td><?= $d['feedback'] !== '' ? nl2br(htmlspecialchars($d['feedback'])) : '<span class="muted">—</span>' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
