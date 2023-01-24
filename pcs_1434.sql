-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 24, 2023 at 04:15 PM
-- Server version: 5.7.33
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pcs_1434`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `nama`) VALUES
(1, 'naufalans@gmail.com', '202cb962ac59075b964b07152d234b70', 'Naufal'),
(4, 'admin@example.com', '202cb962ac59075b964b07152d234b70', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `item_transaksi`
--

CREATE TABLE `item_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga_saat_transaksi` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_transaksi`
--

INSERT INTO `item_transaksi` (`id`, `transaksi_id`, `produk_id`, `qty`, `harga_saat_transaksi`, `sub_total`) VALUES
(11, 5, 8, 1, 103000, 103000),
(12, 6, 9, 1, 52000, 52000),
(13, 7, 10, 1, 120000, 120000),
(14, 8, 9, 1, 52000, 52000),
(15, 10, 9, 1, 52000, 52000),
(16, 11, 9, 1, 52000, 52000),
(17, 12, 14, 1, 52000, 52000),
(18, 12, 8, 1, 103000, 103000),
(19, 12, 10, 1, 120000, 120000),
(20, 13, 9, 1, 52000, 52000);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `is_supplier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `admin_id`, `supplier_id`, `nama`, `harga`, `stok`, `is_supplier`) VALUES
(8, 4, NULL, 'Keyboard K1', 103000, 8, 0),
(9, NULL, 1, 'Mouse K2', 52000, 16, 1),
(10, 4, NULL, 'Mousepad K1', 120000, 3, 0),
(14, 4, 9, 'Mouse K2', 52000, 1, 0),
(16, 0, 0, 'Komputer', 3000000, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `type` enum('penjualan','pembelian') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `admin_id`, `total`, `tanggal`, `type`) VALUES
(5, 4, 103000, '2023-01-24 05:45:08', 'penjualan'),
(6, 4, 52000, '2023-01-24 15:53:12', 'pembelian'),
(7, 4, 120000, '2023-01-24 16:00:15', 'penjualan'),
(8, 4, 52000, '2023-01-24 16:00:25', 'pembelian'),
(9, 1, 1, '2023-01-24 16:02:01', 'pembelian'),
(10, 4, 52000, '2023-01-24 16:10:33', 'pembelian'),
(11, 4, 52000, '2023-01-24 16:11:45', 'pembelian'),
(12, 4, 275000, '2023-01-24 16:12:33', 'penjualan'),
(13, 4, 52000, '2023-01-24 16:12:40', 'pembelian');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_transaksi_transaksi_id` (`transaksi_id`),
  ADD KEY `item_transaksi_produk_id` (`produk_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_admin_id` (`admin_id`),
  ADD KEY `produk_supplier_id` (`supplier_id`) USING BTREE;

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD CONSTRAINT `item_transaksi_produk_id` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`),
  ADD CONSTRAINT `item_transaksi_transaksi_id` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
