-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 22 Sep 2026 pada 09.45
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lapor_sini`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru_diizinkan`
--

CREATE TABLE `guru_diizinkan` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `sudah_daftar` tinyint(1) NOT NULL DEFAULT 0,
  `ditambahkan_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru_diizinkan`
--

INSERT INTO `guru_diizinkan` (`id`, `email`, `nama`, `sudah_daftar`, `ditambahkan_pada`) VALUES
(1, 'guru1@smkn2mjk.sch.id', 'Contoh Nama Guru 1', 0, '2026-08-23 06:26:57'),
(2, 'guru2@smkn2mjk.sch.id', 'Contoh Nama Guru 2', 0, '2026-08-23 06:26:57'),
(3, 'delvin.syarif@gmail.com', 'Delvin Syarif', 0, '2026-09-01 16:21:23'),
(4, 'budi.santoso@gmail.com', 'Budi Santoso', 0, '2026-09-01 16:21:23'),
(5, 'siti.aminah@gmail.com', 'Siti Aminah', 0, '2026-09-01 16:21:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `kategori` enum('fasilitas','keamanan','kebersihan','informasi') NOT NULL,
  `isi_laporan` text NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `anonim` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('baru','proses','selesai') NOT NULL DEFAULT 'baru',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id`, `id_pengguna`, `kategori`, `isi_laporan`, `lokasi`, `foto`, `anonim`, `status`, `dibuat_pada`) VALUES
(1, 1, 'fasilitas', 'Atap ruang kelas X RPL 2 mengalami kebocoran saat hujan deras.', 'Kelas X RPL 2', NULL, 0, 'baru', '2026-08-23 06:26:20'),
(2, 1, 'fasilitas', 'Pendingin ruangan di Lab Komputer 1 tidak menyala.', 'Lab Komputer 1', NULL, 0, 'proses', '2026-08-23 06:26:20'),
(3, 1, 'kebersihan', 'Saluran air toilet dekat ruang guru tersumbat.', 'Toilet Lantai 2', NULL, 1, 'baru', '2026-08-23 06:26:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `kelas_jabatan` varchar(50) DEFAULT NULL,
  `peran` enum('siswa','guru') NOT NULL DEFAULT 'siswa',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `kelas_jabatan`, `peran`, `dibuat_pada`) VALUES
(1, 'Ahmad Rizky', 'ahmad.rizky@smkn2mjk.sch.id', '$2y$10$examplehashedpassword', 'X RPL 2', 'siswa', '2026-08-23 06:26:20'),
(2, 'Delvin Syarif', 'delvin.syarif@gmail.com', '$2y$10$5o5BhUakHmQ/WeHVmewTL.iwJfyNPvcL97SsXEfdjpWZYmKoiJuJy', 'Admin', 'siswa', '2026-09-01 16:22:31');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru_diizinkan`
--
ALTER TABLE `guru_diizinkan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru_diizinkan`
--
ALTER TABLE `guru_diizinkan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
