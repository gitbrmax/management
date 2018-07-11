-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2018 at 05:32 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hvcares`
--

-- --------------------------------------------------------

--
-- Table structure for table `target_churn`
--

CREATE TABLE `target_churn` (
  `id_target_churn` int(11) NOT NULL,
  `id_region` int(11) NOT NULL,
  `tahun_target_churn` varchar(4) NOT NULL,
  `bulan_target_churn` varchar(2) NOT NULL,
  `nilai_target_churn` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `tanggal_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `target_churn`
--

INSERT INTO `target_churn` (`id_target_churn`, `id_region`, `tahun_target_churn`, `bulan_target_churn`, `nilai_target_churn`, `updated_by`, `tanggal_update`) VALUES
(1, 1, '2018', '1', 15689, 362, '2018-07-10 20:05:03'),
(2, 1, '2018', '2', 15322, 362, '2018-07-10 20:05:03'),
(3, 1, '2018', '3', 17717, 362, '2018-07-10 20:05:03'),
(4, 1, '2018', '4', 18070, 362, '2018-07-10 20:05:03'),
(5, 1, '2018', '5', 15006, 362, '2018-07-10 20:05:03'),
(6, 1, '2018', '6', 17079, 362, '2018-07-10 20:05:03'),
(7, 1, '2018', '7', 19408, 362, '2018-07-10 20:05:03'),
(8, 1, '2018', '8', 18399, 362, '2018-07-10 20:05:03'),
(9, 1, '2018', '9', 21101, 362, '2018-07-10 20:05:03'),
(10, 1, '2018', '10', 21299, 362, '2018-07-10 20:05:03'),
(11, 1, '2018', '11', 23189, 362, '2018-07-10 20:05:03'),
(12, 1, '2018', '12', 23558, 362, '2018-07-10 20:05:03'),
(13, 2, '2018', '1', 7129, 362, '2018-07-10 20:05:03'),
(14, 2, '2018', '2', 6090, 362, '2018-07-10 20:05:03'),
(15, 2, '2018', '3', 7175, 362, '2018-07-10 20:05:03'),
(16, 2, '2018', '4', 6764, 362, '2018-07-10 20:05:03'),
(17, 2, '2018', '5', 5732, 362, '2018-07-10 20:05:03'),
(18, 2, '2018', '6', 5827, 362, '2018-07-10 20:05:03'),
(19, 2, '2018', '7', 6984, 362, '2018-07-10 20:05:03'),
(20, 2, '2018', '8', 7920, 362, '2018-07-10 20:05:03'),
(21, 2, '2018', '9', 7820, 362, '2018-07-10 20:05:03'),
(22, 2, '2018', '10', 8636, 362, '2018-07-10 20:05:03'),
(23, 2, '2018', '11', 9399, 362, '2018-07-10 20:05:03'),
(24, 2, '2018', '12', 9398, 362, '2018-07-10 20:05:03'),
(25, 3, '2018', '1', 7329, 362, '2018-07-10 20:05:03'),
(26, 3, '2018', '2', 6999, 362, '2018-07-10 20:05:03'),
(27, 3, '2018', '3', 8965, 362, '2018-07-10 20:05:03'),
(28, 3, '2018', '4', 8563, 362, '2018-07-10 20:05:03'),
(29, 3, '2018', '5', 8161, 362, '2018-07-10 20:05:03'),
(30, 3, '2018', '6', 8443, 362, '2018-07-10 20:05:03'),
(31, 3, '2018', '7', 8033, 362, '2018-07-10 20:05:03'),
(32, 3, '2018', '8', 8795, 362, '2018-07-10 20:05:03'),
(33, 3, '2018', '9', 8747, 362, '2018-07-10 20:05:03'),
(34, 3, '2018', '10', 9945, 362, '2018-07-10 20:05:03'),
(35, 3, '2018', '11', 11042, 362, '2018-07-10 20:05:03'),
(36, 3, '2018', '12', 12124, 362, '2018-07-10 20:05:03'),
(37, 4, '2018', '1', 8311, 362, '2018-07-10 20:05:03'),
(38, 4, '2018', '2', 7237, 362, '2018-07-10 20:05:03'),
(39, 4, '2018', '3', 8814, 362, '2018-07-10 20:05:03'),
(40, 4, '2018', '4', 7877, 362, '2018-07-10 20:05:03'),
(41, 4, '2018', '5', 7444, 362, '2018-07-10 20:05:03'),
(42, 4, '2018', '6', 7222, 362, '2018-07-10 20:05:03'),
(43, 4, '2018', '7', 8274, 362, '2018-07-10 20:05:03'),
(44, 4, '2018', '8', 9436, 362, '2018-07-10 20:05:03'),
(45, 4, '2018', '9', 9942, 362, '2018-07-10 20:05:03'),
(46, 4, '2018', '10', 11082, 362, '2018-07-10 20:05:03'),
(47, 4, '2018', '11', 12186, 362, '2018-07-10 20:05:03'),
(48, 4, '2018', '12', 11100, 362, '2018-07-10 20:05:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `target_churn`
--
ALTER TABLE `target_churn`
  ADD PRIMARY KEY (`id_target_churn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `target_churn`
--
ALTER TABLE `target_churn`
  MODIFY `id_target_churn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
