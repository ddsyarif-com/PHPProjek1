<?php
require 'koneksi.php';

$pesan_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama     = $koneksi->real_escape_string($_POST['nama']);
  $email    = $koneksi->real_escape_string($_POST['email']);
  $kelas    = $koneksi->real_escape_string($_POST['kelas']);
  $peran    = $_POST['peran'] === 'guru' ? 'guru' : 'siswa';
  $password = $_POST['password'];
  $konfirmasi = $_POST['konfirmasi'];

  if ($password !== $konfirmasi) {
    $pesan_error = "Konfirmasi kata sandi tidak cocok!";
  } elseif ($peran === 'guru' && !emailGuruDiizinkan($koneksi, $email)) {
    // Blokir pendaftaran sebagai guru kalau emailnya tidak ada di daftar guru yang diizinkan
    $pesan_error = "Email ini belum terdaftar sebagai guru/staf yang diizinkan. Hubungi admin sekolah untuk didaftarkan lebih dulu.";
  } else {
    // Cek email sudah dipakai atau belum
    $cek = $koneksi->prepare("SELECT id FROM pengguna WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
      $pesan_error = "Email ini sudah terdaftar. Silakan masuk.";
    } else {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $koneksi->prepare("INSERT INTO pengguna (nama, email, password, kelas_jabatan, peran) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $nama, $email, $password_hash, $kelas, $peran);
      if ($stmt->execute()) {
        // Kalau daftar sebagai guru, tandai email itu di whitelist sebagai "sudah dipakai"
        if ($peran === 'guru') {
          $tandai = $koneksi->prepare("UPDATE guru_diizinkan SET sudah_daftar = 1 WHERE email = ?");
          $tandai->bind_param("s", $email);
          $tandai->execute();
        }
        header("Location: login.php?daftar=sukses");
        exit;
      } else {
        $pesan_error = "Gagal mendaftar: " . $koneksi->error;
      }
      $stmt->close();
    }
    $cek->close();
  }
}

// Cek apakah email ada di daftar guru yang diizinkan dan belum dipakai daftar sebelumnya
function emailGuruDiizinkan($koneksi, $email) {
  $stmt = $koneksi->prepare("SELECT id FROM guru_diizinkan WHERE email = ? AND sudah_daftar = 0");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $hasil = $stmt->get_result();
  $ada = $hasil->num_rows > 0;
  $stmt->close();
  return $ada;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Akun — LAPOR SINI</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<div class="daftar-wrapper">
  <div class="hero-grid-bg" style="position:absolute;inset:0;"></div>
  <div class="login-card reveal" style="position:relative;z-index:2;">

    <div class="text-center mb-4">
      <a href="index.php" class="navbar-brand" style="justify-content:center;display:inline-flex;">
        <div class="brand-icon"><i class="bi bi-megaphone-fill"></i></div>
        LAPOR SINI
      </a>
      <p style="color:var(--teks-muted);font-size:0.85rem;margin-top:0.5rem;">Buat akun untuk mulai melapor</p>
    </div>

    <?php if ($pesan_error): ?>
      <div class="alert alert-danger" style="border-radius:var(--radius-sm);font-size:0.85rem;"><?= htmlspecialchars($pesan_error) ?></div>
    <?php endif; ?>

    <form method="POST" action="daftar_akun.php">
      <div class="mb-3">
        <label class="form-label-custom">👤 Nama Lengkap</label>
        <input type="text" class="form-control-custom" name="nama" placeholder="Nama lengkap Anda" required/>
      </div>
      <div class="mb-3">
        <label class="form-label-custom">📧 Email</label>
        <input type="email" class="form-control-custom" name="email" placeholder="nama@smkn2mjk.sch.id" required/>
      </div>
      <div class="mb-3">
        <label class="form-label-custom">🏫 Kelas / Jabatan</label>
        <input type="text" class="form-control-custom" name="kelas" placeholder="Contoh: X RPL 2" required/>
      </div>
      <div class="mb-3">
        <label class="form-label-custom">🎭 Peran</label>
        <select class="form-select-custom" name="peran" id="inputPeran" onchange="toggleKodeGuru()" required>
          <option value="siswa">Siswa</option>
          <option value="guru">Guru / Staf</option>
        </select>
      </div>
      <div class="mb-3" id="wrapperKodeGuru" style="display:none;">
        <div class="alert alert-info" style="border-radius:var(--radius-sm);font-size:0.82rem;margin:0;">
          <i class="bi bi-info-circle me-1"></i> Pendaftaran sebagai Guru/Staf hanya bisa dilakukan dengan email yang sudah didaftarkan sebelumnya oleh admin sekolah. Kalau email kamu belum terdaftar, hubungi admin.
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label-custom">🔑 Kata Sandi</label>
        <input type="password" class="form-control-custom" name="password" placeholder="Minimal 6 karakter" minlength="6" required/>
      </div>
      <div class="mb-4">
        <label class="form-label-custom">🔑 Konfirmasi Kata Sandi</label>
        <input type="password" class="form-control-custom" name="konfirmasi" placeholder="Ulangi kata sandi" minlength="6" required/>
      </div>
      <button type="submit" class="btn-utama"><i class="bi bi-person-plus"></i> Daftar Akun</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:0.85rem;color:var(--teks-muted);">
      Sudah punya akun? <a href="login.php" style="color:var(--biru);font-weight:700;text-decoration:none;">Masuk di sini</a>
    </p>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleKodeGuru() {
  const peran = document.getElementById('inputPeran').value;
  document.getElementById('wrapperKodeGuru').style.display = (peran === 'guru') ? 'block' : 'none';
}
</script>
</body>
</html>