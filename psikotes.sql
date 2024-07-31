-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Jul 2024 pada 08.51
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
-- Database: `psikotes`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bank_soal`
--

CREATE TABLE `bank_soal` (
  `id_bank` int(11) NOT NULL,
  `nama_soal` varchar(50) DEFAULT NULL,
  `no_soal` varchar(50) DEFAULT NULL,
  `status_bank` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bank_soal`
--

INSERT INTO `bank_soal` (`id_bank`, `nama_soal`, `no_soal`, `status_bank`) VALUES
(1, 'Bank Soal 20240711211033', '20240711211033', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hak_akses`
--

CREATE TABLE `hak_akses` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hak_akses`
--

INSERT INTO `hak_akses` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(6, 1, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban`
--

CREATE TABLE `jawaban` (
  `id_jawaban` int(11) NOT NULL,
  `soal_id` int(11) DEFAULT NULL,
  `jawaban` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jawaban`
--

INSERT INTO `jawaban` (`id_jawaban`, `soal_id`, `jawaban`, `user_id`) VALUES
(1, 20, 'c', 2),
(2, 22, 'c', 2),
(3, 25, 'a', 2),
(4, 26, 'c', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_soal`
--

CREATE TABLE `kategori_soal` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_soal`
--

INSERT INTO `kategori_soal` (`id_kategori`, `nama_kategori`) VALUES
(1, 'gambar'),
(2, 'uraian'),
(3, 'perhitungan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `hasil` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `email`, `username`, `nik`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `hasil`, `created_at`) VALUES
(1, 'hanta@gmail.com', 'hanta', '0866546456', 'Hanta Sero', 'Laki-laki', 'Solo', '2002-01-15', '{\"jumlah_soal\":5,\"waktu_pengerjaan\":15,\"start_time\":\"2024-07-22 10:42:43\",\"end_time\":\"2024-07-22 10:56:47\",\"skor_per_soal\":\"10\",\"not_answered\":1,\"pass_answered\":3,\"wrong_answered\":1,\"total_skor\":30,\"detail_soal\":[{\"soal\":\"1, 4, 9, 16, 25, ... ?\",\"pilgan\":{\"a\":\"30\",\"b\":\"35\",\"c\":\"36\",\"d\":\"40\"},\"jawab\":\"c\",\"kunci\":\"c\"},{\"soal\":\"Anjing : Gonggong = Kucing : __?\",\"pilgan\":{\"a\":\"Meong\",\"b\":\"Mengaum\",\"c\":\"Mengeong\",\"d\":\"Menggeram\"},\"jawab\":\"c\",\"kunci\":\"c\"},{\"soal\":\"test 123\",\"pilgan\":{\"a\":\"24_a.png\",\"b\":\"24_b.png\",\"c\":\"24_c.png\",\"d\":\"24_d.jpg\"},\"jawab\":\"Belum dijawab\",\"kunci\":\"a\"},{\"soal\":\"Jika 5x + 3 = 23, maka berapakah nilai x?\",\"pilgan\":{\"a\":\"4\",\"b\":\"5\",\"c\":\"6\",\"d\":\"7\"},\"jawab\":\"a\",\"kunci\":\"a\"},{\"soal\":\"Mobil : Jalan = Kapal : ...\",\"pilgan\":{\"a\":\"Pelabuhan\",\"b\":\"Laut\",\"c\":\"Sungai\",\"d\":\"Danau\"},\"jawab\":\"c\",\"kunci\":\"b\"}]}', '2024-07-22 10:56:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(50) DEFAULT NULL,
  `direktori` varchar(100) DEFAULT NULL,
  `icon` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `direktori`, `icon`) VALUES
(1, 'soal', 'new_soal', 'ri-presentation-fill'),
(2, 'laporan', 'laporan', 'ri-git-repository-fill'),
(3, 'data user', 'data_user', 'ri-team-fill'),
(4, 'setting', 'setting', 'ri-list-settings-line'),
(6, 'penjadwalan', 'penjadwalan', 'ri-calendar-todo-fill');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengerjaan`
--

CREATE TABLE `pengerjaan` (
  `id_pengerjaan` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT current_timestamp(),
  `waktu` int(11) DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT current_timestamp(),
  `status_pengerjaan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengerjaan`
--

INSERT INTO `pengerjaan` (`id_pengerjaan`, `user_id`, `start_time`, `waktu`, `end_time`, `status_pengerjaan`) VALUES
(1, 2, '2024-07-22 03:42:43', 15, '2024-07-22 03:56:47', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjadwalan`
--

CREATE TABLE `penjadwalan` (
  `id_penjadwalan` int(11) NOT NULL,
  `no_jadwal` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `peserta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjadwalan`
--

INSERT INTO `penjadwalan` (`id_penjadwalan`, `no_jadwal`, `tanggal`, `peserta`) VALUES
(1, '20240731082600998', '2024-08-03 08:25:00', '2'),
(2, '20240731082600998', '2024-08-03 08:25:00', '4');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `nama_role`) VALUES
(1, 'Admin'),
(2, 'Calon Karyawan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `waktu_pengerjaan` int(11) DEFAULT NULL,
  `skor_soal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting`
--

INSERT INTO `setting` (`id`, `waktu_pengerjaan`, `skor_soal`) VALUES
(1, 15, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL,
  `no_soal` varchar(50) DEFAULT NULL,
  `soal` text DEFAULT NULL,
  `jawaban_a` text DEFAULT NULL,
  `jawaban_b` text DEFAULT NULL,
  `jawaban_c` text DEFAULT NULL,
  `jawaban_d` text DEFAULT NULL,
  `file` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `kunci_jawaban` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id_soal`, `no_soal`, `soal`, `jawaban_a`, `jawaban_b`, `jawaban_c`, `jawaban_d`, `file`, `kategori_id`, `kunci_jawaban`) VALUES
(20, '20240716083143', '1, 4, 9, 16, 25, ... ?', '30', '35', '36', '40', NULL, 3, 'c'),
(22, '20240716104338', 'Anjing : Gonggong = Kucing : __?', 'Meong', 'Mengaum', 'Mengeong', 'Menggeram', NULL, 3, 'c'),
(24, '20240716112142', 'test 123', '24_a.png', '24_b.png', '24_c.png', '24_d.jpg', '[\"24_66963d869c0c72.39371533.png\",\"24_66963d869c1054.46932831.png\",\"24_66963d869c1070.24460206.png\",\"24_66963d869c1094.96193912.png\"]', 1, 'a'),
(25, '20240716113030', 'Jika 5x + 3 = 23, maka berapakah nilai x?', '4', '5', '6', '7', NULL, 3, 'a'),
(26, '20240719045134', 'Mobil : Jalan = Kapal : ...', 'Pelabuhan', 'Laut', 'Sungai', 'Danau', NULL, 2, 'b');

-- --------------------------------------------------------

--
-- Struktur dari tabel `submenu`
--

CREATE TABLE `submenu` (
  `id_submenu` int(11) NOT NULL,
  `nama_submenu` varchar(50) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `direktori` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `photo_profile` text DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `email`, `username`, `password`, `nik`, `photo_profile`, `nama_lengkap`, `no_hp`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `role_id`, `is_active`) VALUES
(1, 'izuku@gmail.com', 'izuku', '202cb962ac59075b964b07152d234b70', '0866546456', 'izukumidoriya_1.png', 'Izuku Midoriya', '082243634601', 'Laki-laki', 'Solo', '2020-05-21', 1, 1),
(2, 'hanta@gmail.com', 'hanta', '202cb962ac59075b964b07152d234b70', '0866546456', 'hantasero_2.jpg', 'Hanta Sero', '082243634601', 'Laki-laki', 'Solo', '2002-01-15', 2, 1),
(3, 'naufalamajid@gmail.com', 'naufal', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'naufal', NULL, NULL, NULL, NULL, 1, 0),
(4, 'bakugo@gmail.com', 'bakugo', '202cb962ac59075b964b07152d234b70', NULL, 'test.png', 'Katsuki Bakugo', NULL, NULL, NULL, NULL, 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bank_soal`
--
ALTER TABLE `bank_soal`
  ADD PRIMARY KEY (`id_bank`);

--
-- Indeks untuk tabel `hak_akses`
--
ALTER TABLE `hak_akses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hak_akses_role_FK` (`role_id`),
  ADD KEY `hak_akses_menu_FK` (`menu_id`);

--
-- Indeks untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD KEY `jawaban_user_FK` (`user_id`),
  ADD KEY `jawaban_soal_FK` (`soal_id`);

--
-- Indeks untuk tabel `kategori_soal`
--
ALTER TABLE `kategori_soal`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `pengerjaan`
--
ALTER TABLE `pengerjaan`
  ADD PRIMARY KEY (`id_pengerjaan`);

--
-- Indeks untuk tabel `penjadwalan`
--
ALTER TABLE `penjadwalan`
  ADD UNIQUE KEY `penjadwalan_unique` (`id_penjadwalan`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD KEY `soal_kategori_soal_FK` (`kategori_id`);

--
-- Indeks untuk tabel `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`id_submenu`),
  ADD KEY `submenu_menu_FK` (`menu_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `user_role_FK` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bank_soal`
--
ALTER TABLE `bank_soal`
  MODIFY `id_bank` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hak_akses`
--
ALTER TABLE `hak_akses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kategori_soal`
--
ALTER TABLE `kategori_soal`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengerjaan`
--
ALTER TABLE `pengerjaan`
  MODIFY `id_pengerjaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penjadwalan`
--
ALTER TABLE `penjadwalan`
  MODIFY `id_penjadwalan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `submenu`
--
ALTER TABLE `submenu`
  MODIFY `id_submenu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hak_akses`
--
ALTER TABLE `hak_akses`
  ADD CONSTRAINT `hak_akses_menu_FK` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hak_akses_role_FK` FOREIGN KEY (`role_id`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD CONSTRAINT `jawaban_soal_FK` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id_soal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jawaban_user_FK` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD CONSTRAINT `soal_kategori_soal_FK` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_soal` (`id_kategori`);

--
-- Ketidakleluasaan untuk tabel `submenu`
--
ALTER TABLE `submenu`
  ADD CONSTRAINT `submenu_menu_FK` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_FK` FOREIGN KEY (`role_id`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
