<?php
session_start();
$sudah_login = isset($_SESSION["id_pengguna"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LAPOR SINI — Sistem Pengaduan Digital SMKN 2 Mojokerto</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
  <script type="module" src="https://unpkg.com/@splinetool/viewer@1.12.97/build/spline-viewer.js"></script>
</head>
<body>

<!-- NAVBAR -->
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
        <li class="nav-item"><a class="nav-link aktif" href="index.php">Beranda</a></li>
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
<section class="hero" style="position:relative;overflow:hidden;">

  <!-- Spline 3D Background -->
  <div style="position:absolute;inset:0;z-index:0;pointer-events:none;">
    <spline-viewer
      url="https://prod.spline.design/s6q0PngYrgejMFzb/scene.splinecode"
      style="width:100%;height:100%;display:block;"
    ></spline-viewer>
  </div>

  <!-- Overlay gelap agar teks tetap terbaca -->
  <div style="position:absolute;inset:0;z-index:1;background:rgba(15,23,42,0.55);"></div>

  <div class="hero-grid-bg" style="position:relative;z-index:2;"></div>
  <div class="container hero-content" style="position:relative;z-index:2;">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="hero-badge">
          <span class="dot"></span>
          Sistem Aktif — SMKN 2 Kota Mojokerto
        </div>
        <h1>
          Sampaikan <span class="aksen">Laporan</span><br>dengan Aman & Mudah
        </h1>
        <p class="sub">
          Platform pengaduan digital resmi sekolah. Laporkan fasilitas rusak, pelanggaran, atau keluhan lingkungan — terbuka maupun anonim.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="formulir.php" class="btn-hero-utama">
            <i class="bi bi-pencil-square"></i> Buat Laporan
          </a>
          <a href="cara.php" class="btn-hero-sekunder">
            <i class="bi bi-play-circle"></i> Cara Melapor
          </a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-visual">
          <div class="kartu-3d">
            <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-bottom:1rem;">
              <i class="bi bi-list-check me-1"></i> Laporan Terbaru
            </div>
            <div class="kartu-mini">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span style="color:white;font-size:0.85rem;font-weight:600;">Atap kelas X RPL 2 bocor</span>
                <span class="badge-status s-baru">Baru</span>
              </div>
              <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;"><i class="bi bi-geo-alt me-1"></i> Kelas X RPL 2 • 2 jam lalu</div>
            </div>
            <div class="kartu-mini">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span style="color:white;font-size:0.85rem;font-weight:600;">AC Lab Komputer mati</span>
                <span class="badge-status s-proses">Diproses</span>
              </div>
              <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;"><i class="bi bi-geo-alt me-1"></i> Lab Komputer 1 • 1 hari lalu</div>
            </div>
            <div class="kartu-mini">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span style="color:white;font-size:0.85rem;font-weight:600;">Perbaikan kursi aula selesai</span>
                <span class="badge-status s-selesai">Selesai</span>
              </div>
              <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;"><i class="bi bi-geo-alt me-1"></i> Aula Utama • 3 hari lalu</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="py-0">
  <div class="container">
    <div class="stats-bar">
      <div class="row align-items-center justify-content-around g-3">
        <div class="col-6 col-md-3 stat-item">
          <div class="stat-angka" data-count="148">0</div>
          <div class="stat-label">Total Laporan Diterima</div>
        </div>
        <div class="col-md-auto d-none d-md-block">
          <div class="stat-divider"></div>
        </div>
        <div class="col-6 col-md-3 stat-item">
          <div class="stat-angka" data-count="112">0</div>
          <div class="stat-label">Laporan Terselesaikan</div>
        </div>
        <div class="col-md-auto d-none d-md-block">
          <div class="stat-divider"></div>
        </div>
        <div class="col-6 col-md-3 stat-item">
          <div class="stat-angka" data-count="24">0</div>
          <div class="stat-label">Sedang Diproses</div>
        </div>
        <div class="col-md-auto d-none d-md-block">
          <div class="stat-divider"></div>
        </div>
        <div class="col-6 col-md-3 stat-item">
          <div class="stat-angka">96<span style="font-size:1.2rem">%</span></div>
          <div class="stat-label">Tingkat Kepuasan</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FITUR -->
<section class="section-py bg-section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-eyebrow"><i class="bi bi-stars"></i> Keunggulan Platform</div>
      <h2 class="section-title">Mengapa Memilih LAPOR SINI?</h2>
      <p class="section-sub">Dirancang khusus untuk warga sekolah — cepat, aman, dan transparan.</p>
    </div>
    <div class="fitur-grid">
      <div class="fitur-card reveal">
        <div class="fitur-ikon ikon-biru">🔒</div>
        <h4>Laporan Anonim</h4>
        <p>Kirim laporan tanpa khawatir identitas terungkap. Pilih mode rahasia dan nama Anda tidak akan ditampilkan.</p>
      </div>
      <div class="fitur-card reveal">
        <div class="fitur-ikon ikon-indigo">⚡</div>
        <h4>Penanganan Cepat</h4>
        <p>Laporan diverifikasi dalam 1×24 jam dan langsung diteruskan ke petugas sekolah yang berwenang.</p>
      </div>
      <div class="fitur-card reveal">
        <div class="fitur-ikon ikon-sukses">👁️</div>
        <h4>Pantau Status</h4>
        <p>Lihat perkembangan laporan secara real-time: baru diterima, sedang diproses, hingga selesai ditangani.</p>
      </div>
      <div class="fitur-card reveal">
        <div class="fitur-ikon ikon-peringatan">📸</div>
        <h4>Bukti Foto</h4>
        <p>Sertakan foto sebagai bukti pendukung laporan agar penanganan lebih tepat dan akurat.</p>
      </div>
    </div>
  </div>
</section>

<!-- CARA LAPOR SINGKAT -->
<section class="section-py bg-white-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 reveal">
        <div class="section-eyebrow"><i class="bi bi-journal-check"></i> Panduan Singkat</div>
        <h2 class="section-title">Melapor Semudah 5 Langkah</h2>
        <p class="text-muted mb-4">Tidak perlu ribet. Isi formulir, pilih kategori, dan laporan langsung terkirim ke pihak berwenang.</p>
        <a href="cara.php" class="btn-utama" style="width:auto;display:inline-flex;">
          Panduan Lengkap <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
      <div class="col-lg-7">
        <div class="d-flex flex-column gap-3">
          <div class="step-card reveal">
            <div class="step-nomor">1</div>
            <div>
              <h6 style="font-weight:700;margin-bottom:4px;">Buka Formulir Laporan</h6>
              <p class="mb-0" style="font-size:0.87rem;color:var(--teks-muted);">Klik tombol "Buat Laporan" di halaman utama.</p>
            </div>
          </div>
          <div class="step-card reveal">
            <div class="step-nomor">2</div>
            <div>
              <h6 style="font-weight:700;margin-bottom:4px;">Pilih Kategori Laporan</h6>
              <p class="mb-0" style="font-size:0.87rem;color:var(--teks-muted);">Fasilitas, keamanan, kebersihan, atau penyampaian informasi.</p>
            </div>
          </div>
          <div class="step-card reveal">
            <div class="step-nomor">3</div>
            <div>
              <h6 style="font-weight:700;margin-bottom:4px;">Isi Detail Laporan</h6>
              <p class="mb-0" style="font-size:0.87rem;color:var(--teks-muted);">Tulis deskripsi, lokasi kejadian, dan unggah foto bila ada.</p>
            </div>
          </div>
          <div class="step-card reveal">
            <div class="step-nomor">4</div>
            <div>
              <h6 style="font-weight:700;margin-bottom:4px;">Atur Kerahasiaan</h6>
              <p class="mb-0" style="font-size:0.87rem;color:var(--teks-muted);">Pilih laporan terbuka atau anonim sesuai kenyamanan Anda.</p>
            </div>
          </div>
          <div class="step-card reveal">
            <div class="step-nomor">5</div>
            <div>
              <h6 style="font-weight:700;margin-bottom:4px;">Kirim & Pantau</h6>
              <p class="mb-0" style="font-size:0.87rem;color:var(--teks-muted);">Laporan terkirim! Pantau perkembangannya kapan saja.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section-py" style="background: linear-gradient(135deg, #0F172A, #1E3A8A);">
  <div class="container text-center reveal">
    <div style="max-width:560px;margin:auto;">
      <div class="section-eyebrow" style="background:rgba(99,102,241,0.2);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">
        <i class="bi bi-megaphone"></i> Mulai Sekarang
      </div>
      <h2 class="section-title" style="color:white;">Punya keluhan atau laporan?</h2>
      <p style="color:rgba(255,255,255,0.65);margin-bottom:2rem;">Jangan diam. Satu laporan Anda bisa membuat sekolah menjadi lebih baik untuk semua.</p>
      <a href="formulir.php" class="btn-hero-utama" style="margin:auto;">
        <i class="bi bi-pencil-square"></i> Buat Laporan Sekarang
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="brand mb-2">📢 LAPOR SINI</div>
        <p style="font-size:0.87rem;line-height:1.7;">Sistem Pengaduan Digital resmi SMKN 2 Kota Mojokerto. Sampaikan laporan secara aman, cepat, dan transparan.</p>
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
          <li class="mb-1"><i class="bi bi-geo-alt me-2" style="color:#93C5FD"></i> Mojokerto, Jawa Timur</li>
          <li class="mb-1"><i class="bi bi-envelope me-2" style="color:#93C5FD"></i> lapor_sini@smkn2mjk.sch.id</li>
          <li class="mb-1"><i class="bi bi-phone me-2" style="color:#93C5FD"></i> +62 812-XXXX-XXXX</li>
          <li><i class="bi bi-building me-2" style="color:#93C5FD"></i> SMKN 2 Kota Mojokerto</li>
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