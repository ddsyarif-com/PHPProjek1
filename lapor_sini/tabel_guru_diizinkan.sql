-- ================================================
-- TABEL: guru_diizinkan
-- Daftar email guru/staf yang diizinkan mendaftar sebagai "Guru/Staf"
-- Tambahkan baris baru di sini setiap ada guru baru yang perlu akses admin
-- ================================================

USE lapor_sini;

CREATE TABLE guru_diizinkan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL UNIQUE,
  nama VARCHAR(100) DEFAULT NULL,     -- nama guru (opsional, cuma buat catatan biar gampang inget)
  sudah_daftar TINYINT(1) NOT NULL DEFAULT 0,  -- otomatis jadi 1 setelah guru itu berhasil daftar akun
  ditambahkan_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contoh data awal — GANTI dengan email guru asli di sekolah kamu
INSERT INTO guru_diizinkan (email, nama) VALUES
('guru1@smkn2mjk.sch.id', 'Contoh Nama Guru 1'),
('guru2@smkn2mjk.sch.id', 'Contoh Nama Guru 2');
