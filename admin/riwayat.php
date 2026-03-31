<?php
$judul_halaman = 'Riwayat Aspirasi';
require_once __DIR__ . '/../includes/layout_start.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_admin();

/** Riwayat: tampilkan semua pelaporan dengan ringkasan status (array untuk dokumentasi prosedur). */
$map_kat = ambil_map_kategori($conn);
$sql = "SELECT i.id_pelaporan, i.nis, i.lokasi, i.ket, i.tanggal, i.id_kategori AS ik_input,
        a.status, a.feedback, s.kelas
        FROM input_aspirasi i
        JOIN aspirasi a ON a.id_aspirasi
        JOIN siswa s ON s.nis = i.nis
        ORDER BY i.tanggal ASC";
$res = $conn->query($sql);
$riwayat = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $riwayat[] = $r;
    }
}

require_once __DIR__ . '/../includes/nav_admin.php';
?>
<div class="card">
  <h1>Riwayat Seluruh Aspirasi</h1>
  <p class="muted">Log kronologis semua pengaduan yang pernah masuk.</p>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Siswa</th>
          <th>Kategori</th>
          <th>Uraian singkat</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($riwayat === []): ?>
          <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
        <?php endif; ?>
        <?php foreach ($riwayat as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['tanggal']) ?></td>
            <td><?= htmlspecialchars($r['nis'] . ' (' . $r['kelas'] . ')') ?></td>
            <td><?= htmlspecialchars($map_kat[(int)$r['ik_input']] ?? '-') ?></td>
            <td><?= htmlspecialchars(strlen($r['ket']) > 55 ? substr($r['ket'], 0, 52) . '...' : $r['ket']) ?></td>
            <td><span class="badge <?= htmlspecialchars(kelas_badge_status($r['status'])) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p><a href="<?= htmlspecialchars(BASE_URL) ?>/admin/daftar_aspirasi.php">← Ke daftar &amp; filter</a></p>
</div>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
