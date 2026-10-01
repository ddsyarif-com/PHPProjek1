<?php
session_start();
require 'koneksi.php';
$sudah_login = isset($_SESSION['id_pengguna']);

// ===== PAGINATION =====
$per_halaman = 6;
$halaman_ini = isset($_GET['p']) ? max(1, (int) $_GET['p']) : 1;
$offset = ($halaman_ini - 1) * $per_halaman;

$total_data = $koneksi->query("SELECT COUNT(*) AS jumlah FROM laporan")->fetch_assoc()['jumlah'];
$total_halaman = max(1, ceil($total_data / $per_halaman));
if ($halaman_ini > $total_halaman) $halaman_ini = $total_halaman;
$offset = ($halaman_ini - 1) * $per_halaman;

$hasil = $koneksi->query("SELECT l.*, p.nama AS nama_pelapor
                           FROM laporan l
                           JOIN pengguna p ON l.id_pengguna = p.id
                           ORDER BY l.dibuat_pada DESC
                           LIMIT $per_halaman OFFSET $offset");

function labelKategori($kode) {
  $map = [
    'fasilitas'  => ['🏗️ Fasilitas Sekolah', 'bi-tools'],
    'keamanan'   => ['🛡️ Keamanan & Ketertiban', 'bi-shield-exclamation'],
    'kebersihan' => ['🧹 Kebersihan Lingkungan', 'bi-droplet-half'],
    'informasi'  => ['📢 Aspirasi & Informasi', 'bi-megaphone'],
  ];
  return $map[$kode] ?? [$kode, 'bi-file-earmark'];
}

function labelStatus($status) {
  $map = [
    'baru'    => ['Baru', 'badge-baru'],
    'proses'  => ['Diproses', 'badge-proses'],
    'selesai' => ['Selesai', 'badge-selesai'],
  ];
  return $map[$status] ?? [$status, 'badge-kategori'];
}

function waktuLalu($tanggal) {
  $selisih = time() - strtotime($tanggal);
  if ($selisih < 0) return 'Baru saja';
  if ($selisih < 60) return 'Baru saja';
  if ($selisih < 3600) return floor($selisih / 60) . ' menit lalu';
  if ($selisih < 86400) return floor($selisih / 3600) . ' jam lalu';
  return floor($selisih / 86400) . ' hari lalu';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Laporan — LAPOR SINI</title>
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
        <li class="nav-item"><a class="nav-link aktif" href="daftar.php">Daftar Laporan</a></li>
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
      <i class="bi bi-card-list"></i> Transparansi Publik
    </div>
    <h1 class="section-title" style="color:white;font-size:clamp(2rem,4vw,3rem);">Daftar Laporan</h1>
    <p style="color:rgba(255,255,255,0.65);max-width:520px;margin:auto;line-height:1.7;">
      Pantau seluruh laporan yang telah masuk beserta status penanganannya secara terbuka dan transparan.
    </p>
  </div>
</section>

<!-- FILTER & LIST -->
<section class="section-py bg-section">
  <div class="container">

    <?php if (isset($_GET['sukses'])): ?>
      <div class="alert alert-success reveal text-center" style="border-radius:var(--radius-sm);">
        ✅ Laporan berhasil dikirim dan tersimpan!
      </div>
    <?php endif; ?>

    <div class="filter-bar reveal">
      <span style="font-weight:700;font-size:0.82rem;color:var(--teks-muted);white-space:nowrap;"><i class="bi bi-funnel"></i> Filter Status:</span>
      <button class="filter-btn aktif" onclick="filterStatus(this,'semua')">Semua</button>
      <button class="filter-btn" onclick="filterStatus(this,'baru')">Baru</button>
      <button class="filter-btn" onclick="filterStatus(this,'proses')">Diproses</button>
      <button class="filter-btn" onclick="filterStatus(this,'selesai')">Selesai</button>
      <div style="margin-left:auto;flex:1;min-width:200px;max-width:280px;">
        <input type="text" class="form-control-custom" id="cariLaporan" placeholder="🔍 Cari laporan..." oninput="cariData()" style="padding:0.55rem 1rem;font-size:0.85rem;">
      </div>
    </div>

    <div class="row g-3" id="daftarLaporan">
      <?php if ($hasil->num_rows === 0): ?>
        <p class="text-center" style="color:var(--teks-muted);">Belum ada laporan yang masuk.</p>
      <?php else: ?>
        <?php while ($baris = $hasil->fetch_assoc()):
          [$labelKat, $ikonKat] = labelKategori($baris['kategori']);
          [$labelSt, $kelasSt] = labelStatus($baris['status']);
          $namaTampil = $baris['anonim'] ? 'RAHASIA' : htmlspecialchars($baris['nama_pelapor']);
        ?>
        <div class="col-md-6 data-laporan reveal" data-status="<?= $baris['status'] ?>">
          <div class="laporan-card h-100">
            <div class="laporan-meta">
              <span class="badge-pill badge-kategori"><i class="bi <?= $ikonKat ?>"></i> <?= $labelKat ?></span>
              <span class="badge-pill <?= $kelasSt ?>"><?= $labelSt ?></span>
            </div>
            <?php if (!empty($baris['foto'])): ?>
              <img src="uploads/<?= htmlspecialchars($baris['foto']) ?>" alt="Bukti foto laporan" style="width:100%;max-height:160px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:10px;"/>
            <?php endif; ?>
            <h5 style="font-weight:700;margin-bottom:6px;font-size:1rem;"><?= htmlspecialchars(mb_strimwidth($baris['isi_laporan'], 0, 60, '...')) ?></h5>
            <p style="color:var(--teks-muted);font-size:0.85rem;margin-bottom:12px;"><?= htmlspecialchars(mb_strimwidth($baris['isi_laporan'], 0, 140, '...')) ?></p>
            <div class="d-flex justify-content-between align-items-center" style="font-size:0.76rem;color:var(--teks-muted);">
              <span><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($baris['lokasi']) ?></span>
              <span><i class="bi bi-clock me-1"></i> <?= waktuLalu($baris['dibuat_pada']) ?></span>
            </div>
            <div style="font-size:0.74rem;color:var(--teks-muted);margin-top:6px;">
              <i class="bi bi-person me-1"></i> <?= $namaTampil ?>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <p class="text-center reveal" id="pesanKosong" style="display:none;color:var(--teks-muted);margin-top:2rem;">
      <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
      Tidak ada laporan yang sesuai dengan pencarian.
    </p>

    <?php if ($total_halaman > 1): ?>
    <div class="d-flex justify-content-center align-items-center gap-2 mt-5 reveal flex-wrap">
      <?php if ($halaman_ini > 1): ?>
        <a href="daftar.php?p=<?= $halaman_ini - 1 ?>" class="filter-btn"><i class="bi bi-chevron-left"></i> Sebelumnya</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
        <a href="daftar.php?p=<?= $i ?>" class="filter-btn <?= $i === $halaman_ini ? 'aktif' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>

      <?php if ($halaman_ini < $total_halaman): ?>
        <a href="daftar.php?p=<?= $halaman_ini + 1 ?>" class="filter-btn">Selanjutnya <i class="bi bi-chevron-right"></i></a>
      <?php endif; ?>
    </div>
    <p class="text-center mt-2" style="font-size:0.78rem;color:var(--teks-muted);">
      Halaman <?= $halaman_ini ?> dari <?= $total_halaman ?> — total <?= $total_data ?> laporan
    </p>
    <?php endif; ?>

    <div class="text-center mt-4 reveal">
      <a href="formulir.php" class="btn-utama" style="width:auto;display:inline-flex;padding:0.9rem 2.2rem;">
        <i class="bi bi-pencil-square"></i> Buat Laporan Baru
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
<script>
function filterStatus(btn, status) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  document.querySelectorAll('.data-laporan').forEach(item => {
    item.style.display = (status === 'semua' || item.dataset.status === status) ? '' : 'none';
  });
  cekKosong();
}

function cariData() {
  const kata = document.getElementById('cariLaporan').value.toLowerCase();
  document.querySelectorAll('.data-laporan').forEach(item => {
    const teks = item.textContent.toLowerCase();
    item.style.display = teks.includes(kata) ? '' : 'none';
  });
  cekKosong();
}

function cekKosong() {
  const tampil = [...document.querySelectorAll('.data-laporan')].some(i => i.style.display !== 'none');
  document.getElementById('pesanKosong').style.display = tampil ? 'none' : 'block';
}
</script>
</body>
</html>