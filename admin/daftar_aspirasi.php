<?php
$judul_halaman = 'Daftar Aspirasi';
require_once __DIR__ . '/../includes/layout_start.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_admin();

$tanggal = trim($_GET['tanggal'] ?? '');
$bulan = trim($_GET['bulan'] ?? '');
$nis_filter = trim($_GET['nis'] ?? '');
$id_kategori = trim($_GET['id_kategori'] ?? '');

$where = [];
if ($tanggal !== '') {
    $where[] = "DATE(i.tanggal) = '" . esc($conn, $tanggal) . "'";
}
if ($bulan !== '') {
    $where[] = "DATE_FORMAT(i.tanggal, '%Y-%m') = '" . esc($conn, $bulan) . "'";
}
if ($nis_filter !== '' && ctype_digit($nis_filter)) {
    $where[] = 'i.nis = ' . (int)$nis_filter;
}
if ($id_kategori !== '' && ctype_digit($id_kategori)) {
    $where[] = 'i.id_kategori = ' . (int)$id_kategori;
}

$sql_where = gabung_filter_sql($where);
$map_kat = ambil_map_kategori($conn);

$sql = "SELECT i.id_pelaporan,i.nis, i.id_kategori, s.kelas, i.lokasi, i.ket, i.tanggal, i.id_kategori AS ik_input,
        a.status, a.feedback
        FROM input_aspirasi i
        JOIN aspirasi a ON a.id_pelaporan = i.id_pelaporan
        JOIN siswa s ON s.nis = i.nis
        WHERE $sql_where
        ORDER BY i.tanggal DESC";
$res = $conn->query($sql);
$baris_list = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $baris_list[] = $r;
    }
}

$opsi_kategori = ambil_baris_kategori($conn);
require_once __DIR__ . '/../includes/nav_admin.php';
?>
<div class="card">
  <h1>Daftar Aspirasi</h1>
  <p class="muted">Filter berdasarkan tanggal, bulan, NIS siswa, atau kategori.</p>

  <form method="get" class="filters">
    <div class="form-group" style="margin-bottom:0">
      <label>Tanggal</label>
      <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
    </div>
    <div class="form-group" style="margin-bottom:0">
      <label>Bulan</label>
      <input type="month" name="bulan" value="<?= htmlspecialchars($bulan) ?>">
    </div>
    <div class="form-group" style="margin-bottom:0">
      <label>NIS</label>
      <input type="text" name="nis" placeholder="NIS" value="<?= htmlspecialchars($nis_filter) ?>">
    </div>
    <div class="form-group" style="margin-bottom:0">
      <label>Kategori</label>
      <select name="id_kategori">
        <option value="">Semua</option>
        <?php foreach ($opsi_kategori as $op): ?>
          <option value="<?= (int)$op['id_kategori'] ?>" <?= $id_kategori === (string)$op['id_kategori'] ? 'selected' : '' ?>><?= htmlspecialchars($op['ket_kategori']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin-bottom:0">
      <label>&nbsp;</label>
      <button type="submit" class="btn btn-primary">Terapkan</button>
    </div>
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tanggal</th>
          <th>Siswa</th>
          <th>Kategori</th>
          <th>Lokasi</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if ($baris_list === []): ?>
          <tr><td colspan="7" class="muted">Tidak ada data.</td></tr>
        <?php endif; ?>
        <?php foreach ($baris_list as $row): ?>
          <tr>
            <td><?= (int)$row['id_pelaporan'] ?></td>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td><?= htmlspecialchars($row['nis']) ?><br><span class="muted"><?= htmlspecialchars($row['kelas']) ?></span></td>
            <td><?= htmlspecialchars($map_kat[(int)$row['ik_input']] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['lokasi']) ?></td>
            <td><span class="badge <?= htmlspecialchars(kelas_badge_status($row['status'])) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
            <td><a class="btn btn-small btn-primary" href="<?= htmlspecialchars(BASE_URL) ?>/admin/detail_aspirasi.php?id=<?= (int)$row['id_pelaporan'] ?>">Kelola</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
