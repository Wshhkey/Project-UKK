<?php
$judul_halaman = 'Form Aspirasi Siswa';
require_once __DIR__ . '/../includes/layout_start.php';
require_once __DIR__ . '/../includes/functions.php';

$opsi_kategori = ambil_baris_kategori($conn);
$error = '';
$sukses = false;

$nis_input = trim($_POST['nis'] ?? '');
$kelas_input = trim($_POST['kelas'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kat = (int)($_POST['id_kategori'] ?? 0);
    $lokasi = trim($_POST['lokasi'] ?? '');
    $ket = trim($_POST['ket'] ?? '');

    if ($nis_input === '' || !ctype_digit($nis_input)) {
        $error = 'NIS wajib diisi (angka).';
    } elseif ($kelas_input === '') {
        $error = 'Kelas wajib diisi.';
    } elseif ($id_kat <= 0) {
        $error = 'Pilih kategori.';
    } elseif ($lokasi === '' || $ket === '') {
        $error = 'Lokasi dan keterangan wajib diisi.';
    } else {
        $nis = $nis_input;

        // 1) Simpan / perbarui data siswa (nis + kelas).
        $sqlS = "INSERT INTO siswa (nis, kelas) VALUES ('" . esc($conn, $nis) . "', '" . esc($conn, $kelas_input) . "') "
              . "ON DUPLICATE KEY UPDATE kelas = VALUES(kelas)";

        if ($conn->query($sqlS)) {
            // 2) Simpan pelaporan ke input_aspirasi.
            $sqlI = "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket) VALUES ('" .
                esc($conn, $nis) . "', " . $id_kat . ", '" . esc($conn, $lokasi) . "', '" . esc($conn, $ket) . "')";

            if ($conn->query($sqlI)) {
                $id_pelaporan = (int)$conn->insert_id;
                // 3) Buat baris aspirasi yang terhubung ke id_pelaporan.
                $sqlA = "INSERT INTO aspirasi (status, id_pelaporan, feedback) VALUES ('Menunggu', " . $id_pelaporan . ", '')";
                if ($conn->query($sqlA)) {
                    $sukses = true;
                } else {
                    $error = 'Gagal membuat data aspirasi: ' . $conn->error;
                }
            } else {
                $error = 'Gagal membuat data pelaporan: ' . $conn->error;
            }
        } else {
            $error = 'Gagal menyimpan data siswa: ' . $conn->error;
        }
    }
}

require_once __DIR__ . '/../includes/nav_siswa.php';
?>
<div class="card">
  <h1>Form Aspirasi Siswa</h1>

  <?php if ($sukses): ?>
    <div class="alert alert-success">Laporan berhasil dikirim.</div>
  <?php elseif ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="form-group">
      <label>NIS</label>
      <input type="text" name="nis" inputmode="numeric" pattern="[0-9]+" maxlength="20" required value="<?= htmlspecialchars($nis_input) ?>">
    </div>

    <div class="form-group">
      <label>Kelas</label>
      <input type="text" name="kelas" maxlength="10" required value="<?= htmlspecialchars($kelas_input) ?>">
    </div>

    <div class="form-group">
      <label>Kategori</label>
      <select name="id_kategori" required>
        <option value="">— Pilih —</option>
        <?php foreach ($opsi_kategori as $op): ?>
          <option value="<?= (int)$op['id_kategori'] ?>"><?= htmlspecialchars($op['ket_kategori']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Lokasi</label>
      <input type="text" name="lokasi" maxlength="50" required>
    </div>

    <div class="form-group">
      <label>Keterangan</label>
      <textarea name="ket" maxlength="50" required placeholder="Jelaskan secara singkat"></textarea>
    </div>

    <?php if (!$sukses):?>
      <button type="submit" class="btn btn-primary">Kirim Aspirasi</button>
    <?php endif; ?>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
