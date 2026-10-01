<?php
session_start();
$sudah_login = isset($_SESSION["id_pengguna"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cara Lapor — LAPOR SINI</title>
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
        <li class="nav-item"><a class="nav-link aktif" href="cara.php">Cara Lapor</a></li>
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
      <i class="bi bi-journal-text"></i> Panduan Pelaporan
    </div>
    <h1 class="section-title" style="color:white;font-size:clamp(2rem,4vw,3rem);">Tata Cara Melapor</h1>
    <p style="color:rgba(255,255,255,0.65);max-width:520px;margin:auto;line-height:1.7;">
      Ikuti langkah mudah di bawah ini untuk menyampaikan laporan Anda. Prosesnya cepat dan tidak rumit.
    </p>
  </div>
</section>

<!-- HIGHLIGHTS -->
<section class="py-4 bg-white-section">
  <div class="container">
    <div class="row g-3 justify-content-center">
      <div class="col-md-4">
        <div class="info-kotak text-center reveal" style="border-radius:var(--radius);">
          <div style="font-size:1.8rem;margin-bottom:8px;">✍️</div>
          <strong>Isi Laporan</strong>
          <p style="font-size:0.82rem;color:var(--teks-muted);margin:4px 0 0;">Tulis dengan jelas dan lengkap</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-kotak text-center reveal" style="border-radius:var(--radius);">
          <div style="font-size:1.8rem;margin-bottom:8px;">🔒</div>
          <strong>Privasi Aman</strong>
          <p style="font-size:0.82rem;color:var(--teks-muted);margin:4px 0 0;">Pilih laporan terbuka atau anonim</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-kotak text-center reveal" style="border-radius:var(--radius);">
          <div style="font-size:1.8rem;margin-bottom:8px;">📡</div>
          <strong>Pantau Proses</strong>
          <p style="font-size:0.82rem;color:var(--teks-muted);margin:4px 0 0;">Lihat perkembangan penanganan</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- LANGKAH-LANGKAH -->
<section class="section-py bg-section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-eyebrow"><i class="bi bi-list-ol"></i> Langkah demi Langkah</div>
      <h2 class="section-title">5 Langkah Mudah Melapor</h2>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="d-flex flex-column gap-3">

          <div class="step-card reveal">
            <div class="step-nomor">1</div>
            <div>
              <h5 style="font-weight:700;margin-bottom:6px;">Buka Formulir Laporan</h5>
              <p style="color:var(--teks-muted);margin:0;font-size:0.9rem;">Klik tombol <strong>"Buat Laporan Sekarang"</strong> di halaman utama atau menu Formulir.</p>
            </div>
          </div>

          <div class="step-card reveal">
            <div class="step-nomor">2</div>
            <div>
              <h5 style="font-weight:700;margin-bottom:6px;">Pilih Kategori</h5>
              <p style="color:var(--teks-muted);margin:0;font-size:0.9rem;">Pilih jenis laporan yang sesuai: <strong>Fasilitas Sekolah</strong>, <strong>Keamanan & Ketertiban</strong>, atau <strong>Penyampaian Informasi</strong>.</p>
            </div>
          </div>

          <div class="step-card reveal">
            <div class="step-nomor">3</div>
            <div>
              <h5 style="font-weight:700;margin-bottom:6px;">Isi Detail Laporan</h5>
              <p style="color:var(--teks-muted);margin:0;font-size:0.9rem;">Jelaskan masalah secara rinci, cantumkan <strong>lokasi kejadian</strong> (misalnya: Kelas X RPL 2, Lab Komputer 1), dan unggah foto bila ada bukti.</p>
            </div>
          </div>

          <div class="step-card reveal">
            <div class="step-nomor">4</div>
            <div>
              <h5 style="font-weight:700;margin-bottom:6px;">Atur Kerahasiaan</h5>
              <p style="color:var(--teks-muted);margin:0;font-size:0.9rem;">Centang opsi <strong>Laporan Anonim</strong> jika tidak ingin identitas Anda ditampilkan. Nama akan otomatis diganti "RAHASIA".</p>
            </div>
          </div>

          <div class="step-card reveal">
            <div class="step-nomor">5</div>
            <div>
              <h5 style="font-weight:700;margin-bottom:6px;">Kirim & Pantau</h5>
              <p style="color:var(--teks-muted);margin:0;font-size:0.9rem;">Tekan <strong>Kirim Laporan</strong> dan konfirmasi. Pantau status laporan Anda melalui halaman <a href="daftar.php" style="color:var(--biru);font-weight:600;">Daftar Laporan</a> kapan saja.</p>
            </div>
          </div>

        </div>

        <div class="text-center mt-5 reveal">
          <a href="formulir.php" class="btn-utama" style="width:auto;display:inline-flex;padding:1rem 2.5rem;">
            ✍️ Saya Sudah Mengerti, Mulai Melapor
          </a>
          <p style="font-size:0.82rem;color:var(--teks-muted);margin-top:1rem;">Pastikan laporan yang dikirim sesuai fakta dan sopan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TIPS -->
<section class="section-py bg-white-section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-eyebrow"><i class="bi bi-lightbulb"></i> Tips Melapor</div>
      <h2 class="section-title">Agar Laporan Cepat Ditangani</h2>
    </div>
    <div class="row g-3 justify-content-center">
      <div class="col-md-6 col-lg-3">
        <div class="fitur-card reveal text-center">
          <div class="fitur-ikon ikon-biru" style="margin:0 auto 1rem;">📍</div>
          <h6 style="font-weight:700;">Lokasi Jelas</h6>
          <p style="font-size:0.84rem;">Tulis lokasi secara spesifik, misalnya: "Toilet Lantai 2 dekat ruang guru".</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="fitur-card reveal text-center">
          <div class="fitur-ikon ikon-indigo" style="margin:0 auto 1rem;">📸</div>
          <h6 style="font-weight:700;">Sertakan Foto</h6>
          <p style="font-size:0.84rem;">Foto bukti sangat membantu petugas memahami masalah lebih cepat.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="fitur-card reveal text-center">
          <div class="fitur-ikon ikon-sukses" style="margin:0 auto 1rem;">📝</div>
          <h6 style="font-weight:700;">Deskripsi Detail</h6>
          <p style="font-size:0.84rem;">Jelaskan kapan terjadi, seberapa parah, dan dampaknya terhadap kegiatan.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="fitur-card reveal text-center">
          <div class="fitur-ikon ikon-peringatan" style="margin:0 auto 1rem;">🤝</div>
          <h6 style="font-weight:700;">Sopan & Faktual</h6>
          <p style="font-size:0.84rem;">Sampaikan dengan bahasa yang santun dan berdasarkan fakta yang ada.</p>
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