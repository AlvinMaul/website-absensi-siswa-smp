-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 04:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_jenis_absensi` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `waktu_input` datetime NOT NULL DEFAULT current_timestamp(),
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_siswa`, `id_mapel`, `id_guru`, `tanggal`, `id_jenis_absensi`, `keterangan`, `waktu_input`, `id_admin`) VALUES
(1, 1, 1, 1, '2025-06-30', 1, NULL, '2025-06-30 16:44:19', 1),
(2, 2, 1, 1, '2025-06-30', 2, 'Izin ke dokter', '2025-06-30 16:44:19', 1),
(3, 3, 4, 3, '2025-06-30', 4, NULL, '2025-06-30 16:44:19', 1),
(4, 1, 3, 3, '2025-06-30', 2, '', '2025-06-30 18:14:23', 1),
(5, 2, 3, 3, '2025-06-30', 2, 'Izin ke dokter', '2025-06-30 18:14:23', 1),
(6, 4, 3, 2, '2025-06-30', 4, '', '2025-06-30 18:20:37', 1),
(7, 1, 1, 1, '2025-07-10', 4, '', '2025-07-10 10:01:29', 1),
(8, 2, 1, 1, '2025-07-10', 1, '', '2025-07-10 10:01:29', 1),
(9, 3, 2, 1, '2025-07-10', 2, '', '2025-07-10 10:03:36', 1),
(10, 6, 2, 1, '2025-07-22', 1, '', '2025-07-22 23:44:27', 1),
(11, 1, 1, 3, '2025-07-22', 3, '', '2025-07-22 23:44:50', 1),
(12, 2, 1, 3, '2025-07-22', 4, '', '2025-07-22 23:44:50', 1),
(13, 3, 3, 2, '2025-07-22', 2, '', '2025-07-22 23:48:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `email`, `no_telp`, `alamat`, `foto_profil`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@smpsaya.sch.id', '08123456789', 'Jl. Sekolah No. 123, Kota Bandung', NULL, '2025-06-30 16:44:18', '2025-06-30 16:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nip`, `nama_guru`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_telp`, `email`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, '198003122003121001', 'Ahmad Nurhadi, S.Pd', 'Laki-laki', 'Bandung', '1980-03-12', 'Jl. Pendidikan No. 10, Bandung', '08123456789', 'ahmad@smpsaya.sch.id', NULL, '2025-06-30 16:44:19', NULL),
(2, '198204052005012002', 'Siti Rahayu, S.Pd', 'Perempuan', 'Jakarta', '1982-04-05', 'Jl. Merdeka No. 15, Bandung', '08198765432', 'siti@smpsaya.sch.id', NULL, '2025-06-30 16:44:19', NULL),
(3, '198601152007011003', 'Budi Santoso, S.Pd', 'Laki-laki', 'Surabaya', '1986-01-15', 'Jl. Pahlawan No. 20, Bandung', '08111222333', 'budi@smpsaya.sch.id', NULL, '2025-06-30 16:44:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guru_mapel`
--

CREATE TABLE `guru_mapel` (
  `id_guru_mapel` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru_mapel`
--

INSERT INTO `guru_mapel` (`id_guru_mapel`, `id_guru`, `id_mapel`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_absensi`
--

CREATE TABLE `jenis_absensi` (
  `id_jenis_absensi` int(11) NOT NULL,
  `kode_absensi` varchar(5) NOT NULL,
  `nama_absensi` varchar(20) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `warna` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_absensi`
--

INSERT INTO `jenis_absensi` (`id_jenis_absensi`, `kode_absensi`, `nama_absensi`, `keterangan`, `warna`) VALUES
(1, 'H', 'Hadir', 'Siswa hadir sesuai jadwal', 'success'),
(2, 'I', 'Izin', 'Siswa izin tidak masuk', 'warning'),
(3, 'S', 'Sakit', 'Siswa sakit', 'info'),
(4, 'A', 'Alpa', 'Siswa tidak hadir tanpa keterangan', 'danger');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `tingkat` enum('7','8','9') NOT NULL,
  `id_wali_kelas` int(11) DEFAULT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `tingkat`, `id_wali_kelas`, `tahun_ajaran`, `keterangan`) VALUES
(1, '7A', '7', 1, '2023/2024', 'Kelas 7A'),
(2, '7B', '7', 2, '2023/2024', 'Kelas 7B'),
(3, '8A', '8', 3, '2023/2024', 'Kelas 8A'),
(4, '8B', '8', NULL, '2023/2024', 'Kelas 8B'),
(5, '9A', '9', NULL, '2023/2024', 'Kelas 9A'),
(6, '9B', '9', NULL, '2023/2024', 'Kelas 9B');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id_mapel` int(11) NOT NULL,
  `kode_mapel` varchar(10) NOT NULL,
  `nama_mapel` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id_mapel`, `kode_mapel`, `nama_mapel`, `keterangan`) VALUES
(1, 'MAT', 'Matematika', 'Pelajaran matematika untuk semua kelas'),
(2, 'BIN', 'Bahasa Indonesia', 'Pelajaran bahasa Indonesia'),
(3, 'BIG', 'Bahasa Inggris', 'Pelajaran bahasa Inggris'),
(4, 'IPA', 'Ilmu Pengetahuan Alam', 'Pelajaran IPA'),
(5, 'IPS', 'Ilmu Pengetahuan Sosial', 'Pelajaran IPS');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nis` varchar(10) NOT NULL,
  `nisn` varchar(10) DEFAULT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `no_telp_ortu` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nisn`, `nama_siswa`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `id_kelas`, `nama_ayah`, `nama_ibu`, `no_telp_ortu`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, '101', '1234567890', 'Andi Wijaya', 'Laki-laki', 'Bandung', '2010-05-15', 'Jl. Merdeka No. 1, Bandung', 3, 'Budi Wijaya', 'Anita Wijaya', '08123456789', NULL, '2025-06-30 16:44:19', NULL),
(2, '102', '1234567891', 'Budi Santoso', 'Laki-laki', 'Bandung', '2010-07-22', 'Jl. Pahlawan No. 2, Bandung', 3, 'Cahyo Santoso', 'Dewi Santoso', '08198765432', NULL, '2025-06-30 16:44:19', NULL),
(3, '103', '1234567892', 'Cindy Putri', 'Perempuan', 'Bandung', '2009-12-10', 'Jl. Melati No. 3, Bandung', 6, 'Dedi Putra', 'Eva Putri', '08111222333', NULL, '2025-06-30 16:44:19', NULL),
(4, '104', NULL, 'Abdul', 'Laki-laki', 'Pekalongan', '2025-06-19', 'Jl.Sudirman', 4, 'Balmon', 'Angela', '081262838324', NULL, '2025-06-30 17:04:53', NULL),
(5, '105', NULL, 'Balmond', 'Perempuan', 'Tegal', '2006-02-04', 'Jalan len of don', 2, 'Lancelot', 'Odette', '082772934', NULL, '2025-07-10 10:06:07', NULL),
(6, '106', NULL, 'Argus', 'Laki-laki', 'Jakarta', '2007-05-12', 'Jl.Kasih sayang', 1, 'Alucard', 'Nana', '08523183218', NULL, '2025-07-10 10:32:43', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rekap_absensi`
-- (See below for the actual view)
--
CREATE TABLE `view_rekap_absensi` (
`nis` varchar(10)
,`nama_siswa` varchar(100)
,`nama_kelas` varchar(10)
,`hadir` bigint(21)
,`izin_sakit` bigint(21)
,`alpa` bigint(21)
,`persentase` decimal(26,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_statistik_bulanan`
-- (See below for the actual view)
--
CREATE TABLE `view_statistik_bulanan` (
`tahun` int(4)
,`bulan` int(2)
,`nama_kelas` varchar(10)
,`total_siswa` bigint(21)
,`hadir` bigint(21)
,`izin_sakit` bigint(21)
,`alpa` bigint(21)
,`persentase_hadir` decimal(26,2)
);

-- --------------------------------------------------------

--
-- Structure for view `view_rekap_absensi`
--
DROP TABLE IF EXISTS `view_rekap_absensi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rekap_absensi`  AS SELECT `s`.`nis` AS `nis`, `s`.`nama_siswa` AS `nama_siswa`, `k`.`nama_kelas` AS `nama_kelas`, count(case when `ja`.`kode_absensi` = 'H' then 1 end) AS `hadir`, count(case when `ja`.`kode_absensi` in ('I','S') then 1 end) AS `izin_sakit`, count(case when `ja`.`kode_absensi` = 'A' then 1 end) AS `alpa`, round(count(case when `ja`.`kode_absensi` = 'H' then 1 end) / count(0) * 100,2) AS `persentase` FROM (((`siswa` `s` join `kelas` `k` on(`s`.`id_kelas` = `k`.`id_kelas`)) left join `absensi` `a` on(`s`.`id_siswa` = `a`.`id_siswa`)) left join `jenis_absensi` `ja` on(`a`.`id_jenis_absensi` = `ja`.`id_jenis_absensi`)) GROUP BY `s`.`id_siswa`, `s`.`nis`, `s`.`nama_siswa`, `k`.`nama_kelas` ;

-- --------------------------------------------------------

--
-- Structure for view `view_statistik_bulanan`
--
DROP TABLE IF EXISTS `view_statistik_bulanan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_statistik_bulanan`  AS SELECT year(`a`.`tanggal`) AS `tahun`, month(`a`.`tanggal`) AS `bulan`, `k`.`nama_kelas` AS `nama_kelas`, count(distinct `s`.`id_siswa`) AS `total_siswa`, count(case when `ja`.`kode_absensi` = 'H' then 1 end) AS `hadir`, count(case when `ja`.`kode_absensi` in ('I','S') then 1 end) AS `izin_sakit`, count(case when `ja`.`kode_absensi` = 'A' then 1 end) AS `alpa`, round(count(case when `ja`.`kode_absensi` = 'H' then 1 end) / count(0) * 100,2) AS `persentase_hadir` FROM (((`absensi` `a` join `siswa` `s` on(`a`.`id_siswa` = `s`.`id_siswa`)) join `kelas` `k` on(`s`.`id_kelas` = `k`.`id_kelas`)) join `jenis_absensi` `ja` on(`a`.`id_jenis_absensi` = `ja`.`id_jenis_absensi`)) GROUP BY year(`a`.`tanggal`), month(`a`.`tanggal`), `k`.`nama_kelas` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_mapel` (`id_mapel`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_jenis_absensi` (`id_jenis_absensi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD PRIMARY KEY (`id_guru_mapel`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `jenis_absensi`
--
ALTER TABLE `jenis_absensi`
  ADD PRIMARY KEY (`id_jenis_absensi`),
  ADD UNIQUE KEY `kode_absensi` (`kode_absensi`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `nama_kelas` (`nama_kelas`),
  ADD KEY `id_wali_kelas` (`id_wali_kelas`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id_mapel`),
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  MODIFY `id_guru_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_absensi`
--
ALTER TABLE `jenis_absensi`
  MODIFY `id_jenis_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`),
  ADD CONSTRAINT `absensi_ibfk_3` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `absensi_ibfk_4` FOREIGN KEY (`id_jenis_absensi`) REFERENCES `jenis_absensi` (`id_jenis_absensi`),
  ADD CONSTRAINT `absensi_ibfk_5` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL;

--
-- Constraints for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD CONSTRAINT `guru_mapel_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `guru_mapel_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_wali_kelas`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
