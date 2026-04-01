<?php
require_once __DIR__ . '/config/app.php';
session_start();
unset($_SESSION['admin_user'], $_SESSION['siswa_nis'];
header('Location: ' . BASE_URL . '/index.php');
exit;
