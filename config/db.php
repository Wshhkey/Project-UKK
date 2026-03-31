<?php
/**
 * Koneksi database — sesuaikan dengan instalasi MySQL lokal.
 */
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '1234';
$db_name = 'pengaduan_sarana';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die('Gagal koneksi database: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
