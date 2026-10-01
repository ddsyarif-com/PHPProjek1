<?php
session_start();
require 'koneksi.php';

// Wajib login DAN harus guru
if (!isset($_SESSION['id_pengguna']) || $_SESSION['peran'] !== 'guru') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_laporan'])) {
  $id_laporan = (int) $_POST['id_laporan'];

  // Admin boleh hapus laporan siapa saja, tidak perlu cek kepemilikan
  $cek = $koneksi->prepare("SELECT foto FROM laporan WHERE id = ?");
  $cek->bind_param("i", $id_laporan);
  $cek->execute();
  $data = $cek->get_result()->fetch_assoc();

  if ($data) {
    if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])) {
      unlink('uploads/' . $data['foto']);
    }
    $hapus = $koneksi->prepare("DELETE FROM laporan WHERE id = ?");
    $hapus->bind_param("i", $id_laporan);
    $hapus->execute();
  }
}

header("Location: admin.php?hapus=1");
exit;
?>
