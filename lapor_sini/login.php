<?php
session_start();
require 'koneksi.php';

$pesan_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = $koneksi->real_escape_string($_POST['email']);
  $password = $_POST['password'];

  $stmt = $koneksi->prepare("SELECT id, nama, password, peran, kelas_jabatan FROM pengguna WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $hasil = $stmt->get_result();

  if ($hasil->num_rows === 1) {
    $data = $hasil->fetch_assoc();
    if (password_verify($password, $data['password'])) {
      // Login berhasil — simpan sesi
      $_SESSION['id_pengguna'] = $data['id'];
      $_SESSION['nama']        = $data['nama'];
      $_SESSION['peran']       = $data['peran'];
      $_SESSION['kelas']       = $data['kelas_jabatan'];

      header("Location: profil.php");
      exit;
    } else {
      $pesan_error = "Kata sandi salah!";
    }
  } else {
    $pesan_error = "Email belum terdaftar. Silakan daftar akun dulu.";
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Masuk — LAPOR SINI</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<div class="login-wrapper">
  <div class="login-card reveal">

    <div class="text-center mb-4">
      <a href="index.php" class="navbar-brand" style="justify-content:center;display:inline-flex;">
        <div class="brand-icon"><i class="bi bi-megaphone-fill"></i></div>
        LAPOR SINI
      </a>
      <p style="color:var(--teks-muted);font-size:0.85rem;margin-top:0.5rem;">Masuk untuk menyampaikan dan memantau laporan Anda</p>
    </div>

    <?php if (isset($_GET['daftar'])): ?>
      <div class="alert alert-success" style="border-radius:var(--radius-sm);font-size:0.85rem;">✅ Akun berhasil dibuat! Silakan masuk.</div>
    <?php endif; ?>
    <?php if ($pesan_error): ?>
      <div class="alert alert-danger" style="border-radius:var(--radius-sm);font-size:0.85rem;"><?= htmlspecialchars($pesan_error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="mb-3">
        <label class="form-label-custom">📧 Email</label>
        <input type="email" class="form-control-custom" name="email" placeholder="nama@smkn2mjk.sch.id" required/>
      </div>
      <div class="mb-3">
        <label class="form-label-custom">🔑 Kata Sandi</label>
        <input type="password" class="form-control-custom" name="password" placeholder="Masukkan kata sandi" required/>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4" style="font-size:0.82rem;">
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;color:var(--teks-muted);">
          <input type="checkbox" style="accent-color:var(--biru);width:16px;height:16px;"/> Ingat saya
        </label>
        <a href="#" style="color:var(--biru);font-weight:600;text-decoration:none;">Lupa sandi?</a>
      </div>
      <button type="submit" class="btn-utama"><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:0.85rem;color:var(--teks-muted);">
      Belum punya akun? <a href="daftar_akun.php" style="color:var(--biru);font-weight:700;text-decoration:none;">Daftar di sini</a>
    </p>
    <div class="text-center mt-2">
      <a href="index.php" style="font-size:0.8rem;color:var(--teks-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
      </a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
