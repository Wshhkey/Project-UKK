<?php
/**
 * Fungsi & struktur array untuk aplikasi pengaduan sarana.
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

/**
 * Array label status untuk tampilan dan validasi.
 */
function daftar_status_aspirasi(): array
{
    return ['Menunggu' => 'Menunggu', 'Proses' => 'Diproses', 'Selesai' => 'Selesai'];
}

/**
 * Validasi status masuk dari form admin.
 */
function status_valid(string $s): bool
{
    $daftar = daftar_status_aspirasi();
    return isset($daftar[$s]);
}

/**
 * Mapping warna badge status (nama CSS class).
 */
function kelas_badge_status(string $status): string
{
    $peta = [
        'Menunggu' => 'badge-wait',
        'Proses' => 'badge-progress',
        'Selesai' => 'badge-done',
    ];
    return $peta[$status] ?? 'badge-wait';
}

/**
 * Ambil semua kategori sebagai array [id => ket].
 */
function ambil_map_kategori(mysqli $conn): array
{
    $hasil = [];
    $q = $conn->query('SELECT id_kategori, ket_kategori FROM kategori ORDER BY id_kategori');
    if ($q) {
        while ($row = $q->fetch_assoc()) {
            $hasil[(int)$row['id_kategori']] = $row['ket_kategori'];
        }
    }
    return $hasil;
}

/**
 * Array baris kategori untuk dropdown (nested array).
 */
function ambil_baris_kategori(mysqli $conn): array
{
    $baris = [];
    $q = $conn->query('SELECT id_kategori, ket_kategori FROM kategori ORDER BY ket_kategori');
    if ($q) {
        while ($row = $q->fetch_assoc()) {
            $baris[] = [
                'id_kategori' => (int)$row['id_kategori'],
                'ket_kategori' => $row['ket_kategori'],
            ];
        }
    }
    return $baris;
}

/**
 * Susun filter SQL dari array kondisi (untuk daftar aspirasi admin).
 */
function gabung_filter_sql(array $where): string
{
    if ($where === []) {
        return '1=1';
    }
    return implode(' AND ', $where);
}

/**
 * Escape string untuk query sederhana (tugas sekolah).
 */
function esc(mysqli $conn, ?string $s): string
{
    if ($s === null) {
        return '';
    }
    return $conn->real_escape_string($s);
}

/**
 * Cek login admin session.
 */
function wajib_admin(): void
{
    if (empty($_SESSION['admin_user'])) {
        header('Location: ' . BASE_URL . '/login_admin.php');
        exit;
    }
}

/**
 * Cek login siswa session.
 */
function wajib_siswa(): void
{
    if (empty($_SESSION['siswa_nis'])) {
        header('Location: ' . BASE_URL . '/login_siswa.php');
        exit;
    }
}
