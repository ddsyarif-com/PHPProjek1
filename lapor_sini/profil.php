<?php
session_start();
require 'koneksi.php';

// Kalau belum login, tendang ke halaman login
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['id_pengguna'];

// Ambil data pengguna
$stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$pengguna = $stmt->get_result()->fetch_assoc();

// Ambil statistik laporan milik pengguna ini
$stat = $koneksi->prepare("SELECT
    COUNT(*) AS total,
    SUM(status='selesai') AS selesai,
    SUM(status='proses') AS proses
    FROM laporan WHERE id_pengguna = ?");
$stat->bind_param("i", $id);
$stat->execute();
$statistik = $stat->get_result()->fetch_assoc();

// Ambil riwayat laporan milik pengguna ini
$riwayat = $koneksi->prepare("SELECT * FROM laporan WHERE id_pengguna = ? ORDER BY dibuat_pada DESC LIMIT 5");
$riwayat->bind_param("i", $id);
$riwayat->execute();
$daftar_laporan = $riwayat->get_result();

function inisial($nama) {
  $kata = explode(' ', trim($nama));
  $huruf = strtoupper(substr($kata[0], 0, 1));
  if (count($kata) > 1) $huruf .= strtoupper(substr(end($kata), 0, 1));
  return $huruf;
}

function labelStatus($status) {
  $map = [
    'baru'    => ['Baru', 'badge-baru'],
    'proses'  => ['Diproses', 'badge-proses'],
    'selesai' => ['Selesai', 'badge-selesai'],
  ];
  return $map[$status] ?? [$status, 'badge-kategori'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil — LAPOR SINI</title>
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
        <li class="nav-item"><a class="nav-link aktif" href="profil.php">Profil</a></li>
        <?php if ($_SESSION['peran'] === 'guru'): ?>
          <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-speedometer2"></i> Admin</a></li>
        <?php endif; ?>
        <li class="nav-item ms-2"><a class="nav-link btn-masuk" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i> Keluar</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO PROFIL -->
<section class="profil-hero">
  <div class="hero-grid-bg"></div>
  <div class="container position-relative" style="z-index:2;">
    <div class="section-eyebrow" style="background:rgba(99,102,241,0.2);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">
      <i class="bi bi-person-circle"></i> Akun Saya
    </div>
    <h1 class="section-title" style="color:white;font-size:clamp(2rem,4vw,3rem);">Profil Pengguna</h1>
    <p style="color:rgba(255,255,255,0.65);max-width:520px;line-height:1.7;">
      Kelola informasi akun dan pantau riwayat laporan yang pernah Anda kirimkan.
    </p>
  </div>
</section>

<section class="bg-section" style="padding-bottom:5rem;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">

        <div class="profil-card p-4 p-md-5 reveal">

          <!-- IDENTITAS -->
          <div class="d-flex flex-wrap align-items-center gap-4 mb-4">
            <div class="avatar-besar"><?= inisial($pengguna['nama']) ?></div>
            <div class="flex-grow-1">
              <h3 style="font-weight:800;margin-bottom:4px;"><?= htmlspecialchars($pengguna['nama']) ?></h3>
              <p style="color:var(--teks-muted);margin:0;font-size:0.9rem;">
                <i class="bi bi-mortarboard me-1"></i>
                <?= $pengguna['peran'] === 'guru' ? 'Guru/Staf' : 'Siswa' ?> — <?= htmlspecialchars($pengguna['kelas_jabatan']) ?>
              </p>
            </div>
          </div>

          <!-- INFO -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="info-kotak">
                <small>Email</small>
                <strong><?= htmlspecialchars($pengguna['email']) ?></strong>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-kotak">
                <small>Kelas / Jabatan</small>
                <strong><?= htmlspecialchars($pengguna['kelas_jabatan']) ?></strong>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-kotak">
                <small>Peran</small>
                <strong><?= $pengguna['peran'] === 'guru' ? 'Guru/Staf' : 'Siswa' ?></strong>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-kotak">
                <small>Bergabung Sejak</small>
                <strong><?= date('F Y', strtotime($pengguna['dibuat_pada'])) ?></strong>
              </div>
            </div>
          </div>

          <!-- STATISTIK -->
          <div class="row g-3 mb-4">
            <div class="col-4">
              <div class="stat-kotak">
                <div class="angka"><?= $statistik['total'] ?? 0 ?></div>
                <div class="label-stat">Total Laporan</div>
              </div>
            </div>
            <div class="col-4">
              <div class="stat-kotak">
                <div class="angka"><?= $statistik['selesai'] ?? 0 ?></div>
                <div class="label-stat">Selesai</div>
              </div>
            </div>
            <div class="col-4">
              <div class="stat-kotak">
                <div class="angka"><?= $statistik['proses'] ?? 0 ?></div>
                <div class="label-stat">Diproses</div>
              </div>
            </div>
          </div>

          <hr style="border-color:rgba(37,99,235,0.1);">

          <!-- RIWAYAT -->
          <?php if (isset($_GET['hapus'])): ?>
            <div class="alert alert-success" style="border-radius:var(--radius-sm);font-size:0.85rem;">✅ Laporan berhasil dihapus.</div>
          <?php endif; ?>
          <h5 style="font-weight:700;margin:1.5rem 0 1rem;">
            <i class="bi bi-clock-history me-2"></i> Riwayat Laporan Saya
          </h5>
          <div class="d-flex flex-column gap-3 mb-4">
            <?php if ($daftar_laporan->num_rows === 0): ?>
              <p style="color:var(--teks-muted);font-size:0.88rem;">Anda belum pernah mengirim laporan.</p>
            <?php else: ?>
              <?php while ($lp = $daftar_laporan->fetch_assoc()):
                [$labelSt, $kelasSt] = labelStatus($lp['status']);
              ?>
              <div class="laporan-card">
                <div class="laporan-meta">
                  <span class="badge-pill badge-kategori"><i class="bi bi-tag"></i> <?= ucfirst($lp['kategori']) ?></span>
                  <span class="badge-pill <?= $kelasSt ?>"><?= $labelSt ?></span>
                </div>
                <?php if (!empty($lp['foto'])): ?>
                  <img src="uploads/<?= htmlspecialchars($lp['foto']) ?>" alt="Bukti foto laporan" style="width:100%;max-height:140px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:8px;"/>
                <?php endif; ?>
                <h6 style="font-weight:700;margin-bottom:4px;"><?= htmlspecialchars(mb_strimwidth($lp['isi_laporan'], 0, 60, '...')) ?></h6>
                <div style="font-size:0.76rem;color:var(--teks-muted);">
                  <i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($lp['lokasi']) ?>
                  &nbsp;•&nbsp;
                  <i class="bi bi-clock me-1"></i> <?= date('d M Y, H:i', strtotime($lp['dibuat_pada'])) ?>
                </div>
                <form method="POST" action="hapus_laporan.php" onsubmit="return confirm('Yakin mau hapus laporan ini? Tidak bisa dikembalikan lagi.');" class="mt-2">
                  <input type="hidden" name="id_laporan" value="<?= $lp['id'] ?>"/>
                  <button type="submit" style="background:none;border:none;color:var(--bahaya);font-size:0.78rem;font-weight:600;padding:0;">
                    <i class="bi bi-trash3 me-1"></i> Hapus Laporan
                  </button>
                </form>
              </div>
              <?php endwhile; ?>
            <?php endif; ?>
          </div>

          <div class="text-center mb-4">
            <a href="daftar.php" style="color:var(--biru);font-weight:600;font-size:0.85rem;text-decoration:none;">
              Lihat semua laporan <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>

          <hr style="border-color:rgba(37,99,235,0.1);">

          <!-- AKSI AKUN -->
          <div class="d-flex gap-3 mt-4 flex-wrap">
            <a href="formulir.php" class="btn-utama" style="width:auto;">
              <i class="bi bi-pencil-square"></i> Buat Laporan Baru
            </a>
            <a href="logout.php" class="btn-sekunder" style="width:auto;color:var(--bahaya);border-color:#FCA5A5;">
              <i class="bi bi-box-arrow-right"></i> Keluar
            </a>
          </div>

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