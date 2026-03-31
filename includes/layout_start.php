<?php
require_once __DIR__ . '/../config/app.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$judul_halaman = $judul_halaman ?? 'Aplikasi Pengaduan Sarana';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($judul_halaman) ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>/assets/css/style.css">
</head>
<body>
<div class="shell">
