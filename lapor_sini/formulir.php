<?php
session_start();
require 'koneksi.php';

// Wajib login dulu sebelum bisa membuat laporan
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}

$pesan_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kategori = $koneksi->real_escape_string($_POST['kategori']);
  $isi      = $koneksi->real_escape_string($_POST['isiLaporan']);
  $lokasi   = $koneksi->real_escape_string($_POST['lokasi']);
  $anonim   = isset($_POST['rahasia']) ? 1 : 0;

  $id_pengguna = $_SESSION['id_pengguna'];

  // Proses upload foto (opsional) — dengan validasi keamanan
  $foto_nama = null;
  $upload_gagal = false;
  $tipe_asli = null;
  $stmt = null;

  if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

    $ukuran_maks = 3 * 1024 * 1024; // 3 MB
    $tipe_diizinkan = [
      'image/jpeg' => 'jpg',
      'image/png'  => 'png',
      'image/webp' => 'webp',
      'image/gif'  => 'gif',
    ];

    // 1. Cek ukuran file
    if ($_FILES['foto']['size'] > $ukuran_maks) {
      $pesan_error = "Ukuran foto maksimal 3MB.";
      $upload_gagal = true;
    }

    // 2. Cek TIPE ASLI file dengan membaca isinya (bukan cuma percaya nama file/ekstensi dari pengguna)
    if (!$upload_gagal) {
      $info_file = finfo_open(FILEINFO_MIME_TYPE);
      $tipe_asli = finfo_file($info_file, $_FILES['foto']['tmp_name']);
      finfo_close($info_file);

      if (!array_key_exists($tipe_asli, $tipe_diizinkan)) {
        $pesan_error = "File yang diunggah harus berupa gambar (JPG, PNG, WEBP, atau GIF).";
        $upload_gagal = true;
      }
    }

    // 3. Kalau lolos validasi, simpan dengan nama file BARU (acak) — nama asli dari pengguna diabaikan total
    if (!$upload_gagal) {
      $folder_upload = 'uploads/';
      if (!is_dir($folder_upload)) {
        mkdir($folder_upload, 0777, true);
      }
      $ekstensi_aman = $tipe_diizinkan[$tipe_asli];
      $foto_nama = bin2hex(random_bytes(12)) . '.' . $ekstensi_aman;
      move_uploaded_file($_FILES['foto']['tmp_name'], $folder_upload . $foto_nama);
    }
  }

  if ($upload_gagal) {
    // Jangan simpan laporan kalau fotonya tidak valid — user harus perbaiki dulu
  } else {

  $stmt = $koneksi->prepare("INSERT INTO laporan (id_pengguna, kategori, isi_laporan, lokasi, foto, anonim, status) VALUES (?, ?, ?, ?, ?, ?, 'baru')");
  $stmt->bind_param("issssi", $id_pengguna, $kategori, $isi, $lokasi, $foto_nama, $anonim);

  if ($stmt->execute()) {
    header("Location: daftar.php?sukses=1");
    exit;
  } else {
    $pesan_error = "Gagal menyimpan laporan: " . $koneksi->error;
  }
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulir Laporan — LAPOR SINI</title>
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
        <?php if ($_SESSION['peran'] === 'guru'): ?>
          <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-speedometer2"></i> Admin</a></li>
        <?php endif; ?>
        <li class="nav-item ms-2"><a class="nav-link btn-masuk" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i> Keluar</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="section-py bg-section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-eyebrow"><i class="bi bi-pencil-square"></i> Formulir Pengaduan</div>
      <h1 class="section-title">Buat Laporan Baru</h1>
      <p class="section-sub">Isi formulir di bawah ini dengan lengkap dan jujur. Laporan Anda akan segera ditangani.</p>
    </div>

    <?php if ($pesan_error): ?>
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="alert alert-danger reveal"><?= htmlspecialchars($pesan_error) ?></div>
        </div>
      </div>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="form-card reveal">
          <form id="formLapor" method="POST" action="formulir.php" enctype="multipart/form-data">

            <!-- KATEGORI -->
            <div class="mb-4">
              <label class="form-label-custom">📂 Kategori Laporan <span style="color:var(--bahaya)">*</span></label>
              <select class="form-select-custom" id="kategori" name="kategori" required>
                <option value="">— Pilih Kategori —</option>
                <option value="fasilitas">🏗️ Kerusakan Fasilitas Sekolah</option>
                <option value="keamanan">🛡️ Keamanan & Ketertiban</option>
                <option value="kebersihan">🧹 Kebersihan Lingkungan</option>
                <option value="informasi">📢 Penyampaian Informasi / Aspirasi</option>
              </select>
            </div>

            <!-- NAMA (tampilan saja, tidak dikirim ke server jika anonim) -->
            <div class="mb-4">
              <label class="form-label-custom">👤 Nama Pelapor <span style="color:var(--bahaya)">*</span></label>
              <input type="text" class="form-control-custom" id="nama" name="nama" value="<?= htmlspecialchars($_SESSION['nama']) ?>" placeholder="Tuliskan nama Anda" required/>
            </div>

            <!-- MODE ANONIM -->
            <div class="mb-4 p-3 reveal" style="background:var(--biru-muda);border-radius:var(--radius-sm);border:1px solid var(--biru-aksen);">
              <div class="d-flex align-items-center gap-3">
                <input type="checkbox" id="rahasia" name="rahasia" onchange="sembunyiNama()" style="width:20px;height:20px;accent-color:var(--biru);cursor:pointer;"/>
                <div>
                  <label for="rahasia" style="font-weight:700;cursor:pointer;color:var(--biru);font-size:0.9rem;">
                    🔒 Kirim sebagai Anonim
                  </label>
                  <p style="font-size:0.8rem;color:var(--teks-muted);margin:2px 0 0;">Centang ini agar nama Anda tidak ditampilkan di publik</p>
                </div>
              </div>
            </div>

            <!-- ISI LAPORAN -->
            <div class="mb-4">
              <label class="form-label-custom">📝 Isi Laporan <span style="color:var(--bahaya)">*</span></label>
              <textarea class="form-control-custom" id="isiLaporan" name="isiLaporan" placeholder="Jelaskan masalah atau informasi yang ingin Anda sampaikan secara rinci..." rows="5" required></textarea>
            </div>

            <!-- LOKASI -->
            <div class="mb-4">
              <label class="form-label-custom">📍 Lokasi Kejadian <span style="color:var(--bahaya)">*</span></label>
              <input type="text" class="form-control-custom" id="lokasi" name="lokasi" placeholder="Contoh: Kelas X RPL 2, Lab Komputer 1, Toilet Lantai 2" required/>
            </div>

            <!-- FOTO -->
            <div class="mb-5">
              <label class="form-label-custom">📷 Bukti Foto <span style="color:var(--teks-muted);font-weight:400;">(opsional)</span></label>
              <input type="file" class="form-control-custom" id="foto" name="foto" accept="image/*"/>
              <img id="preview-img" src="" alt="" style="display:none;width:100%;border-radius:var(--radius-sm);margin-top:0.75rem;max-height:200px;object-fit:cover;"/>
            </div>

            <!-- TOMBOL -->
            <div class="d-flex flex-column gap-3">
              <button type="button" class="btn-utama" onclick="tampilPeringatan()">
                <i class="bi bi-send"></i> Kirim Laporan
              </button>
              <button type="reset" class="btn-sekunder">
                <i class="bi bi-arrow-counterclockwise"></i> Batalkan
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MODAL KONFIRMASI -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius-xl);border:none;box-shadow:0 32px 80px rgba(0,0,0,0.2);">
      <div class="modal-body p-4 text-center">
        <div style="width:64px;height:64px;background:var(--biru-aksen);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.8rem;">⚠️</div>
        <h5 style="font-weight:800;margin-bottom:0.5rem;">Kirim Laporan?</h5>
        <p style="color:var(--teks-muted);font-size:0.9rem;">Pastikan semua informasi sudah benar. Laporan yang terkirim tidak dapat diubah.</p>
        <div class="d-flex gap-3 mt-4">
          <button class="btn-sekunder" data-bs-dismiss="modal">Batal</button>
          <button class="btn-utama" id="btnKirim" onclick="document.getElementById('formLapor').submit()">Ya, Kirim</button>
        </div>
      </div>
    </div>
  </div>
</div>

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
<script>
function sembunyiNama() {
  const input = document.getElementById('nama');
  const cb = document.getElementById('rahasia');
  if (cb.checked) {
    input.value = 'ANONIM';
    input.readOnly = true;
    input.style.background = '#F8FAFC';
    input.style.color = '#94A3B8';
  } else {
    input.value = '';
    input.readOnly = false;
    input.style.background = '';
    input.style.color = '';
  }
}

function validasiForm() {
  const k = document.getElementById('kategori').value;
  const n = document.getElementById('nama').value.trim();
  const i = document.getElementById('isiLaporan').value.trim();
  const l = document.getElementById('lokasi').value.trim();
  if (!k) { alert('Pilih kategori laporan terlebih dahulu!'); return false; }
  if (!n) { alert('Nama pelapor harus diisi!'); return false; }
  if (!i) { alert('Isi laporan tidak boleh kosong!'); return false; }
  if (!l) { alert('Lokasi kejadian harus diisi!'); return false; }
  return true;
}

function tampilPeringatan() {
  if (!validasiForm()) return;
  new bootstrap.Modal(document.getElementById('modalKonfirmasi')).show();
}

document.getElementById('foto').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    const img = document.getElementById('preview-img');
    img.src = e.target.result;
    img.style.display = 'block';
  };
  reader.readAsDataURL(file);
});
</script>
</body>
</html>