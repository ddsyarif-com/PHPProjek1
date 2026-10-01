<?php
session_start();
require 'koneksi.php';

// Wajib login DAN harus berperan guru
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}
if ($_SESSION['peran'] !== 'guru') {
  header("Location: index.php");
  exit;
}

// Proses update status kalau ada form yang disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_laporan'], $_POST['status_baru'])) {
  $id = (int) $_POST['id_laporan'];
  $status_valid = ['baru', 'proses', 'selesai'];
  if (in_array($_POST['status_baru'], $status_valid)) {
    $stmt = $koneksi->prepare("UPDATE laporan SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['status_baru'], $id);
    $stmt->execute();
    $stmt->close();
  }
  header("Location: admin.php?update=1");
  exit;
}

$hasil = $koneksi->query("SELECT l.*, p.nama AS nama_pelapor, p.email
                           FROM laporan l
                           JOIN pengguna p ON l.id_pengguna = p.id
                           ORDER BY FIELD(l.status,'baru','proses','selesai'), l.dibuat_pada DESC");

function labelKategori($kode) {
  $map = [
    'fasilitas'  => ['🏗️ Fasilitas Sekolah', 'bi-tools'],
    'keamanan'   => ['🛡️ Keamanan & Ketertiban', 'bi-shield-exclamation'],
    'kebersihan' => ['🧹 Kebersihan Lingkungan', 'bi-droplet-half'],
    'informasi'  => ['📢 Aspirasi & Informasi', 'bi-megaphone'],
  ];
  return $map[$kode] ?? [$kode, 'bi-file-earmark'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin — LAPOR SINI</title>
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
        <li class="nav-item"><a class="nav-link aktif" href="admin.php"><i class="bi bi-speedometer2 me-1"></i> Admin</a></li>
        <li class="nav-item ms-2"><a class="nav-link btn-masuk" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i> Keluar</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="tentang-hero">
  <div class="hero-grid-bg"></div>
  <div class="container position-relative" style="z-index:2;text-align:center;">
    <div class="section-eyebrow" style="background:rgba(99,102,241,0.2);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">
      <i class="bi bi-speedometer2"></i> Panel Petugas
    </div>
    <h1 class="section-title" style="color:white;font-size:clamp(2rem,4vw,3rem);">Kelola Laporan</h1>
    <p style="color:rgba(255,255,255,0.65);max-width:520px;margin:auto;line-height:1.7;">
      Ubah status penanganan laporan yang masuk dari warga sekolah.
    </p>
  </div>
</section>

<section class="section-py bg-section">
  <div class="container">

    <?php if (isset($_GET['update'])): ?>
      <div class="alert alert-success reveal text-center" style="border-radius:var(--radius-sm);">
        ✅ Status laporan berhasil diperbarui!
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['hapus'])): ?>
      <div class="alert alert-success reveal text-center" style="border-radius:var(--radius-sm);">
        🗑️ Laporan berhasil dihapus.
      </div>
    <?php endif; ?>

    <div class="row g-3">
      <?php if ($hasil->num_rows === 0): ?>
        <p class="text-center" style="color:var(--teks-muted);">Belum ada laporan yang masuk.</p>
      <?php else: ?>
        <?php while ($baris = $hasil->fetch_assoc()):
          [$labelKat, $ikonKat] = labelKategori($baris['kategori']);
          $namaTampil = $baris['anonim'] ? 'RAHASIA' : htmlspecialchars($baris['nama_pelapor']);
        ?>
        <div class="col-md-6 reveal">
          <div class="laporan-card h-100">
            <div class="laporan-meta">
              <span class="badge-pill badge-kategori"><i class="bi <?= $ikonKat ?>"></i> <?= $labelKat ?></span>
              <span class="badge-pill badge-<?= $baris['status'] === 'baru' ? 'baru' : ($baris['status'] === 'proses' ? 'proses' : 'selesai') ?>">
                <?= ucfirst($baris['status']) ?>
              </span>
            </div>

            <?php if (!empty($baris['foto'])): ?>
              <img src="uploads/<?= htmlspecialchars($baris['foto']) ?>" alt="Bukti foto laporan" style="width:100%;max-height:160px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:10px;"/>
            <?php endif; ?>

            <p style="color:var(--teks-muted);font-size:0.88rem;margin-bottom:10px;"><?= htmlspecialchars($baris['isi_laporan']) ?></p>

            <div style="font-size:0.78rem;color:var(--teks-muted);margin-bottom:4px;">
              <i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($baris['lokasi']) ?>
            </div>
            <div style="font-size:0.78rem;color:var(--teks-muted);margin-bottom:4px;">
              <i class="bi bi-person me-1"></i> <?= $namaTampil ?>
              <?php if (!$baris['anonim']): ?>
                &nbsp;•&nbsp; <?= htmlspecialchars($baris['email']) ?>
              <?php endif; ?>
            </div>
            <div style="font-size:0.78rem;color:var(--teks-muted);margin-bottom:14px;">
              <i class="bi bi-clock me-1"></i> <?= date('d M Y, H:i', strtotime($baris['dibuat_pada'])) ?>
            </div>

            <form method="POST" action="admin.php" class="d-flex gap-2">
              <input type="hidden" name="id_laporan" value="<?= $baris['id'] ?>"/>
              <select name="status_baru" class="form-select-custom" style="flex:1;padding:0.55rem 0.8rem;font-size:0.85rem;">
                <option value="baru" <?= $baris['status'] === 'baru' ? 'selected' : '' ?>>Baru</option>
                <option value="proses" <?= $baris['status'] === 'proses' ? 'selected' : '' ?>>Diproses</option>
                <option value="selesai" <?= $baris['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
              </select>
              <button type="submit" class="btn-utama" style="width:auto;padding:0.5rem 1.2rem;font-size:0.85rem;">
                <i class="bi bi-check2"></i> Update
              </button>
            </form>
            <form method="POST" action="admin_hapus_laporan.php" onsubmit="return confirm('Yakin mau hapus laporan ini secara permanen?');" class="mt-2">
              <input type="hidden" name="id_laporan" value="<?= $baris['id'] ?>"/>
              <button type="submit" style="background:none;border:none;color:var(--bahaya);font-size:0.78rem;font-weight:600;padding:0;">
                <i class="bi bi-trash3 me-1"></i> Hapus Laporan Ini
              </button>
            </form>
          </div>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>
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