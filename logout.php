<?php
require_once __DIR__ . '/config/app.php';
session_start();
unset($_SESSION['admin_user'], $_SESSION['siswa_nis'], $_SESSION['siswa_kelas'], $_SESSION['siswa_nama']);
header('Location: ' . BASE_URL . '/index.php');
exit;
