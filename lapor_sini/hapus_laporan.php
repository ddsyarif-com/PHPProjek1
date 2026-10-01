<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_laporan'])) {
  $id_laporan  = (int) $_POST['id_laporan'];
  $id_pengguna = $_SESSION['id_pengguna'];

  // PENTING: cek dulu laporan itu memang punya user yang lagi login,
  // biar orang lain nggak bisa hapus laporan orang lain seenaknya
  $cek = $koneksi->prepare("SELECT foto FROM laporan WHERE id = ? AND id_pengguna = ?");
  $cek->bind_param("ii", $id_laporan, $id_pengguna);
  $cek->execute();
  $data = $cek->get_result()->fetch_assoc();

  if ($data) {
    // Hapus juga file foto dari server kalau ada
    if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])) {
      unlink('uploads/' . $data['foto']);
    }

    $hapus = $koneksi->prepare("DELETE FROM laporan WHERE id = ? AND id_pengguna = ?");
    $hapus->bind_param("ii", $id_laporan, $id_pengguna);
    $hapus->execute();
  }
  // Kalau $data kosong berarti laporan itu bukan milik user ini — diabaikan diam-diam, tidak dihapus
}

header("Location: profil.php?hapus=1");
exit;
?>
