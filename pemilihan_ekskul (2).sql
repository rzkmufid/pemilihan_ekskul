-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2024 at 09:34 PM
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
-- Database: `pemilihan_ekskul`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_siswa`
--

CREATE TABLE `detail_siswa` (
  `kd_detail` int(11) NOT NULL,
  `kd_siswa` int(11) DEFAULT NULL,
  `kd_ekskul` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_siswa`
--

INSERT INTO `detail_siswa` (`kd_detail`, `kd_siswa`, `kd_ekskul`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 2, 1),
(7, 2, 2),
(8, 2, 3),
(9, 2, 4),
(10, 2, 5),
(11, 3, 1),
(12, 3, 2),
(13, 3, 3),
(14, 3, 4),
(15, 3, 5),
(16, 4, 1),
(17, 4, 2),
(18, 4, 3),
(19, 4, 4),
(20, 4, 5),
(21, 5, 1),
(22, 5, 2),
(23, 5, 3),
(24, 5, 4),
(25, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `ekskul`
--

CREATE TABLE `ekskul` (
  `kd_ekskul` int(11) NOT NULL,
  `nm_ekskul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ekskul`
--

INSERT INTO `ekskul` (`kd_ekskul`, `nm_ekskul`) VALUES
(1, 'Basket'),
(2, 'Volly'),
(3, 'Futsal'),
(4, 'Pramuka'),
(5, 'Osis');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(255) NOT NULL,
  `kriteria` varchar(225) NOT NULL,
  `bobot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `kriteria`, `bobot`) VALUES
(1, 'C1', 'Minat', 0.1),
(2, 'C2', 'Pengalaman', 0.2),
(3, 'C3', 'Teknikal', 0.23),
(4, 'C4', 'Fisik', 0.17),
(5, 'C5', 'Komunikasi', 0.15),
(6, 'C6', 'Kerjasama Tim', 0.15);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_kriteria`
--

CREATE TABLE `nilai_kriteria` (
  `id_nilai` int(11) NOT NULL,
  `kd_detail` int(11) DEFAULT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `nilai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_kriteria`
--

INSERT INTO `nilai_kriteria` (`id_nilai`, `kd_detail`, `id_kriteria`, `nilai`) VALUES
(1, 1, 1, '1'),
(2, 1, 2, '5'),
(3, 1, 3, '1'),
(4, 1, 4, '4'),
(5, 1, 5, '2'),
(6, 1, 6, '2'),
(7, 2, 1, '2'),
(8, 2, 2, '5'),
(9, 2, 3, '5'),
(10, 2, 4, '3'),
(11, 2, 5, '4'),
(12, 2, 6, '2'),
(13, 3, 1, '2'),
(14, 3, 2, '2'),
(15, 3, 3, '2'),
(16, 3, 4, '3'),
(17, 3, 5, '4'),
(18, 3, 6, '1'),
(19, 4, 1, '2'),
(20, 4, 2, '3'),
(21, 4, 3, '3'),
(22, 4, 4, '2'),
(23, 4, 5, '4'),
(24, 4, 6, '5'),
(25, 5, 1, '5'),
(26, 5, 2, '3'),
(27, 5, 3, '4'),
(28, 5, 4, '2'),
(29, 5, 5, '4'),
(30, 5, 6, '4'),
(31, 6, 1, '1'),
(32, 6, 2, '3'),
(33, 6, 3, '1'),
(34, 6, 4, '4'),
(35, 6, 5, '3'),
(36, 6, 6, '1'),
(37, 7, 1, '2'),
(38, 7, 2, '1'),
(39, 7, 3, '5'),
(40, 7, 4, '3'),
(41, 7, 5, '4'),
(42, 7, 6, '3'),
(43, 8, 1, '1'),
(44, 8, 2, '3'),
(45, 8, 3, '2'),
(46, 8, 4, '4'),
(47, 8, 5, '4'),
(48, 8, 6, '3'),
(49, 9, 1, '2'),
(50, 9, 2, '3'),
(51, 9, 3, '2'),
(52, 9, 4, '2'),
(53, 9, 5, '5'),
(54, 9, 6, '3'),
(55, 10, 1, '4'),
(56, 10, 2, '4'),
(57, 10, 3, '5'),
(58, 10, 4, '2'),
(59, 10, 5, '1'),
(60, 10, 6, '3'),
(61, 11, 1, '1'),
(62, 11, 2, '1'),
(63, 11, 3, '2'),
(64, 11, 4, '5'),
(65, 11, 5, '3'),
(66, 11, 6, '4'),
(67, 12, 1, '5'),
(68, 12, 2, '1'),
(69, 12, 3, '3'),
(70, 12, 4, '2'),
(71, 12, 5, '4'),
(72, 12, 6, '5'),
(73, 13, 1, '1'),
(74, 13, 2, '5'),
(75, 13, 3, '5'),
(76, 13, 4, '1'),
(77, 13, 5, '3'),
(78, 13, 6, '4'),
(79, 14, 1, '2'),
(80, 14, 2, '2'),
(81, 14, 3, '2'),
(82, 14, 4, '1'),
(83, 14, 5, '4'),
(84, 14, 6, '2'),
(85, 15, 1, '5'),
(86, 15, 2, '5'),
(87, 15, 3, '4'),
(88, 15, 4, '3'),
(89, 15, 5, '1'),
(90, 15, 6, '1'),
(91, 16, 1, '4'),
(92, 16, 2, '3'),
(93, 16, 3, '4'),
(94, 16, 4, '4'),
(95, 16, 5, '1'),
(96, 16, 6, '2'),
(97, 17, 1, '4'),
(98, 17, 2, '1'),
(99, 17, 3, '5'),
(100, 17, 4, '3'),
(101, 17, 5, '2'),
(102, 17, 6, '5'),
(103, 18, 1, '4'),
(104, 18, 2, '3'),
(105, 18, 3, '1'),
(106, 18, 4, '4'),
(107, 18, 5, '1'),
(108, 18, 6, '2'),
(109, 19, 1, '3'),
(110, 19, 2, '2'),
(111, 19, 3, '5'),
(112, 19, 4, '3'),
(113, 19, 5, '1'),
(114, 19, 6, '4'),
(115, 20, 1, '4'),
(116, 20, 2, '3'),
(117, 20, 3, '3'),
(118, 20, 4, '1'),
(119, 20, 5, '4'),
(120, 20, 6, '4'),
(121, 21, 1, '4'),
(122, 21, 2, '3'),
(123, 21, 3, '1'),
(124, 21, 4, '4'),
(125, 21, 5, '2'),
(126, 21, 6, '5'),
(127, 22, 1, '3'),
(128, 22, 2, '2'),
(129, 22, 3, '1'),
(130, 22, 4, '2'),
(131, 22, 5, '5'),
(132, 22, 6, '4'),
(133, 23, 1, '3'),
(134, 23, 2, '3'),
(135, 23, 3, '5'),
(136, 23, 4, '4'),
(137, 23, 5, '1'),
(138, 23, 6, '4'),
(139, 24, 1, '1'),
(140, 24, 2, '5'),
(141, 24, 3, '3'),
(142, 24, 4, '1'),
(143, 24, 5, '3'),
(144, 24, 6, '4'),
(145, 25, 1, '3'),
(146, 25, 2, '3'),
(147, 25, 3, '5'),
(148, 25, 4, '4'),
(149, 25, 5, '5'),
(150, 25, 6, '5');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `kd_siswa` int(11) NOT NULL,
  `nm_siswa` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`kd_siswa`, `nm_siswa`, `alamat`, `no_telepon`, `jenis_kelamin`, `tanggal_lahir`, `kelas`) VALUES
(1, 'AXEL DAFAHIMZA', 'Jl. Merdeka No. 123', '08123456789', 'Laki-laki', '2003-05-10', 'XII IPA 1'),
(2, 'AYLI ASYIFA PUTRI', 'Jl. Pahlawan No. 45', '08234567890', 'Laki-laki', '2003-03-15', 'XI IPS 2'),
(3, 'BIMBIM', 'Jl. Jendral Sudirman No. 78', '08345678901', 'Perempuan', '2003-07-20', 'XII IPA 2'),
(4, 'DANIEL', 'Jl. Diponegoro No. 56', '08456789012', 'Perempuan', '2003-01-05', 'XI IPS 1'),
(5, 'ELJIRA RISTINA', 'Jl. Gatot Subroto No. 34', '08567890123', 'Laki-laki', '2003-09-12', 'XII IPS 3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_siswa`
--
ALTER TABLE `detail_siswa`
  ADD PRIMARY KEY (`kd_detail`),
  ADD KEY `kd_siswa` (`kd_siswa`),
  ADD KEY `kd_ekskul` (`kd_ekskul`);

--
-- Indexes for table `ekskul`
--
ALTER TABLE `ekskul`
  ADD PRIMARY KEY (`kd_ekskul`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai_kriteria`
--
ALTER TABLE `nilai_kriteria`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `kd_detail` (`kd_detail`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`kd_siswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_siswa`
--
ALTER TABLE `detail_siswa`
  MODIFY `kd_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ekskul`
--
ALTER TABLE `ekskul`
  MODIFY `kd_ekskul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nilai_kriteria`
--
ALTER TABLE `nilai_kriteria`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `kd_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_siswa`
--
ALTER TABLE `detail_siswa`
  ADD CONSTRAINT `detail_siswa_ibfk_1` FOREIGN KEY (`kd_siswa`) REFERENCES `siswa` (`kd_siswa`),
  ADD CONSTRAINT `detail_siswa_ibfk_2` FOREIGN KEY (`kd_ekskul`) REFERENCES `ekskul` (`kd_ekskul`);

--
-- Constraints for table `nilai_kriteria`
--
ALTER TABLE `nilai_kriteria`
  ADD CONSTRAINT `nilai_kriteria_ibfk_1` FOREIGN KEY (`kd_detail`) REFERENCES `detail_siswa` (`kd_detail`),
  ADD CONSTRAINT `nilai_kriteria_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
