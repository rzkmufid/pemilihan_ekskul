-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2024 at 06:17 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pemilihan_ekskul`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_siswa`
--

CREATE TABLE `detail_siswa` (
  `kd_detail` int(11) NOT NULL,
  `kd_siswa` int(11) NOT NULL,
  `kd_ekskul` int(11) NOT NULL,
  `c1` int(11) DEFAULT NULL,
  `c2` int(11) DEFAULT NULL,
  `c3` int(11) DEFAULT NULL,
  `c4` int(11) DEFAULT NULL,
  `c5` int(11) DEFAULT NULL,
  `c6` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_siswa`
--

INSERT INTO `detail_siswa` (`kd_detail`, `kd_siswa`, `kd_ekskul`, `c1`, `c2`, `c3`, `c4`, `c5`, `c6`) VALUES
(68, 1, 1, 1, 5, 1, 4, 2, 2),
(69, 2, 1, 1, 3, 1, 4, 3, 1),
(70, 3, 1, 1, 1, 2, 5, 3, 4),
(71, 4, 1, 4, 3, 4, 4, 1, 2),
(72, 5, 1, 4, 3, 1, 4, 2, 5),
(73, 1, 2, 2, 5, 5, 3, 4, 2),
(74, 2, 2, 2, 1, 5, 3, 4, 3),
(75, 3, 2, 5, 1, 3, 2, 4, 5),
(76, 4, 2, 4, 1, 5, 3, 2, 5),
(77, 5, 2, 3, 2, 1, 2, 5, 4),
(78, 1, 3, 2, 2, 2, 3, 4, 1),
(79, 2, 3, 1, 3, 2, 4, 4, 3),
(80, 3, 3, 1, 5, 5, 1, 3, 4),
(81, 4, 3, 4, 3, 1, 4, 1, 2),
(82, 5, 3, 3, 3, 5, 4, 1, 4),
(83, 1, 4, 2, 3, 3, 2, 4, 5),
(84, 2, 4, 2, 3, 2, 2, 5, 3),
(85, 3, 4, 2, 2, 2, 1, 4, 2),
(86, 4, 4, 3, 2, 5, 3, 1, 4),
(87, 5, 4, 1, 5, 3, 1, 3, 4),
(88, 1, 5, 5, 3, 4, 2, 4, 4),
(89, 2, 5, 4, 4, 5, 2, 1, 3),
(90, 3, 5, 5, 5, 4, 3, 1, 1),
(91, 4, 5, 4, 3, 3, 1, 4, 4),
(92, 5, 5, 3, 3, 5, 4, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `ekskul`
--

CREATE TABLE `ekskul` (
  `kd_ekskul` int(11) NOT NULL,
  `nm_ekskul` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ekskul`
--

INSERT INTO `ekskul` (`kd_ekskul`, `nm_ekskul`) VALUES
(1, 'Basket'),
(2, 'Volly'),
(3, 'Futsal'),
(4, 'Pramuka	'),
(5, 'Osis ');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `kd_siswa` int(11) NOT NULL,
  `nm_siswa` varchar(100) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_telepon` char(15) DEFAULT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `kd_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `ekskul`
--
ALTER TABLE `ekskul`
  MODIFY `kd_ekskul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
