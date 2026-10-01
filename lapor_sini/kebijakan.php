<?php
session_start();
$sudah_login = isset($_SESSION['id_pengguna']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kebijakan — LAPOR SINI</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<nav class="navbar-lapor navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <div class="brand-icon"><i class="bi bi-megaphone-fill"></i></div>
      LAPOR SINI
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <i class="bi bi-list fs-4" style="color:var(--biru)"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="tentang.php">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="cara.php">Cara Lapor</a></li>
        <li class="nav-item"><a class="nav-link" href="daftar.php">Daftar Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <?php if ($sudah_login && isset($_SESSION['peran']) && $_SESSION['peran'] === 'guru'): ?>
          <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-speedometer2"></i> Admin</a></li>
        <?php endif; ?>
        <?php if ($sudah_login): ?>
          <li class="nav-item ms-2"><a class="nav-link btn-masuk" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i> Keluar</a></li>
        <?php else: ?>
          <li class="nav-item ms-2"><a class="nav-link btn-masuk" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="tentang-hero">
  <div class="hero-grid-bg"></div>
  <div class="container position-relative" style="z-index:2;text-align:center;">
    <div class="section-eyebrow" style="background:rgba(99,102,241,0.2);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">
      <i class="bi bi-shield-check"></i> Kebijakan Penggunaan
    </div>
    <h1 class="section-title" style="color:white;font-size:clamp(2rem,4vw,3rem);">Kebijakan & Privasi</h1>
    <p style="color:rgba(255,255,255,0.65);max-width:560px;margin:auto;line-height:1.7;">
      Baca ketentuan penggunaan LAPOR SINI agar laporan yang kamu sampaikan diproses dengan aman dan bertanggung jawab.
    </p>
  </div>
</section>

<section class="section-py bg-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">

        <div class="info-card-tentang reveal">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="fitur-ikon ikon-biru" style="margin-bottom:0;">🔒</div>
            <div>
              <h3 style="font-weight:800;font-size:1.3rem;margin-bottom:4px;">Privasi & Kerahasiaan Data</h3>
            </div>
          </div>
          <ul style="color:var(--teks-muted);line-height:1.9;padding-left:1.2rem;">
            <li>Data pribadi (nama, email, kelas) yang kamu daftarkan hanya digunakan untuk keperluan verifikasi dan komunikasi terkait laporan.</li>
            <li>Jika kamu memilih mode <strong>Anonim</strong> saat mengirim laporan, nama pelapor tidak akan ditampilkan ke publik dan akan tercatat sebagai "RAHASIA" di halaman Daftar Laporan.</li>
            <li>Kata sandi akun disimpan dalam bentuk terenkripsi (hashed) dan tidak dapat dilihat oleh siapa pun, termasuk pengelola sistem.</li>
            <li>Data laporan hanya dapat diakses oleh pihak sekolah yang berwenang untuk keperluan penanganan.</li>
          </ul>
        </div>

        <div class="info-card-tentang reveal">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="fitur-ikon ikon-indigo" style="margin-bottom:0;">📋</div>
            <div>
              <h3 style="font-weight:800;font-size:1.3rem;margin-bottom:4px;">Ketentuan Pengiriman Laporan</h3>
            </div>
          </div>
          <ul style="color:var(--teks-muted);line-height:1.9;padding-left:1.2rem;">
            <li>Laporan yang dikirim harus berdasarkan fakta dan kejadian yang benar-benar terjadi di lingkungan sekolah.</li>
            <li>Dilarang mengirim laporan yang berisi fitnah, ujaran kebencian, atau informasi palsu yang dapat merugikan pihak lain.</li>
            <li>Setiap laporan yang telah dikirim tidak dapat diedit atau dihapus oleh pelapor, guna menjaga keaslian data.</li>
            <li>Sekolah berhak menindaklanjuti, mengarsipkan, atau menghubungi pelapor (jika bukan anonim) untuk konfirmasi lebih lanjut.</li>
          </ul>
        </div>

        <div class="info-card-tentang reveal">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="fitur-ikon ikon-sukses" style="margin-bottom:0;">⚖️</div>
            <div>
              <h3 style="font-weight:800;font-size:1.3rem;margin-bottom:4px;">Tanggung Jawab Pengguna</h3>
            </div>
          </div>
          <ul style="color:var(--teks-muted);line-height:1.9;padding-left:1.2rem;">
            <li>Pengguna bertanggung jawab penuh atas kebenaran informasi yang disampaikan dalam laporan.</li>
            <li>Penyalahgunaan sistem (spam, laporan palsu berulang, dsb.) dapat berakibat pemblokiran akun.</li>
            <li>Dengan menggunakan LAPOR SINI, pengguna dianggap telah membaca dan menyetujui kebijakan ini.</li>
          </ul>
        </div>

        <div class="text-center mt-4 reveal">
          <a href="index.php" class="btn-utama" style="width:auto;display:inline-flex;">
            <i class="bi bi-house"></i> Kembali ke Beranda
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="brand mb-2">📢 LAPOR SINI</div>
        <p style="font-size:0.87rem;line-height:1.7;">Sistem Pengaduan Digital resmi SMKN 2 Kota Mojokerto.</p>
      </div>
      <div class="col-md-4">
        <h6>Navigasi</h6>
        <ul class="list-unstyled">
          <li><a href="index.php">Beranda</a></li>
          <li><a href="tentang.php">Tentang</a></li>
          <li><a href="cara.php">Cara Lapor</a></li>
          <li><a href="daftar.php">Daftar Laporan</a></li>
          <li><a href="kebijakan.php">Kebijakan</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6>Kontak</h6>
        <ul class="list-unstyled" style="font-size:0.87rem;">
          <li class="mb-1"><i class="bi bi-building me-2" style="color:#93C5FD"></i> SMKN 2 Kota Mojokerto</li>
          <li><i class="bi bi-envelope me-2" style="color:#93C5FD"></i> lapor_sini@smkn2mjk.sch.id</li>
        </ul>
      </div>
    </div>
    <hr class="mt-4 mb-3"/>
    <p class="copy text-center mb-0">&copy; 2026 LAPOR SINI — Dibuat oleh Siswa Kelas X RPL 2 SMKN 2 Kota Mojokerto</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>