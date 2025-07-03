-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 08:01 AM
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
-- Database: `keuangan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `saldo` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `user_id`, `nama_kategori`, `saldo`, `created_at`) VALUES
(9, 1, 'Skincare', -105000.00, '2025-06-10 08:03:22'),
(10, 1, 'Kost', -1100000.00, '2025-06-10 09:33:05'),
(11, 1, 'Make Up', -67800.00, '2025-06-10 09:38:56'),
(12, 1, 'Kuliah', -23000.00, '2025-06-11 02:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `kategori1`
--

CREATE TABLE `kategori1` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori1`
--

INSERT INTO `kategori1` (`id`, `nama_kategori`, `user_id`) VALUES
(1, 'Biaya Tambahan', 1),
(3, 'A', 1),
(4, 'Skincare', 1),
(5, 'Skincare', 1),
(6, 'Makan', 1),
(7, 'Skincare', 1),
(8, 'Skincare', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `user_id`, `kategori_id`, `nominal`, `deskripsi`, `tanggal`) VALUES
(7, 1, 9, 130000.00, 'BCA', '2025-06-10'),
(8, 1, 10, 1500000.00, 'BCA', '2025-06-10'),
(9, 1, 11, 2000000.00, 'Mandiri', '2025-06-10'),
(10, 1, 9, 9999999999.99, 'WXDWSX', '2025-06-10'),
(11, 1, 9, 9999999999.99, 'DSCS', '2025-06-10'),
(12, 1, 12, 2000000.00, 'BCA', '2025-06-11');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `barang` varchar(100) DEFAULT NULL,
  `tempat` varchar(100) DEFAULT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `user_id`, `kategori_id`, `nominal`, `barang`, `tempat`, `tanggal`) VALUES
(5, 1, 9, 60000.00, 'Moisturizer', 'CF Beauty', '2025-06-10'),
(6, 1, 10, 1100000.00, 'Bayar Kost', 'Kost', '2025-06-10'),
(7, 1, 9, 45000.00, 'Peeling Serum', 'Jelita', '2025-06-10'),
(8, 1, 11, 67800.00, 'Lipstik', 'ELS Beauty', '2025-06-10'),
(9, 1, 12, 23000.00, 'Print', 'Kampus', '2025-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `password`, `role`, `created_at`) VALUES
(1, 'Ayudddd', '$2y$10$69NIzKUyam3kXC3pnSAFFufY1iQX5jwScGnwTcjmeNTt4cVqsW8rW', 'user', '2025-06-10 05:19:10'),
(2, 'zulfa', '$2y$10$zbOdRvaXkCVw6rCxxWWkeeuMuUKKc3MlmnS08mahB71b2DWDCpuKS', 'user', '2025-06-10 05:21:51'),
(3, 'ayam', '$2y$10$jNhRdIhC7z7RNhYDner8l.VE5E6SzVza01aGy9b6i/oLoWa1klcg6', 'user', '2025-06-10 05:54:43'),
(4, 'Zulfa P', '$2y$10$nVdqkY8StKirs5uR8r7Neu74pXT33Sq.AQNmixVDn3GFnegcJvyve', 'user', '2025-06-10 09:45:27'),
(5, 'Zulfaa', '$2y$10$.CKS2SshBwoNsXNJQf0LZ.wq11T3nSWU77EZiFynnl6xdlokZISPO', 'user', '2025-06-10 09:45:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kategori1`
--
ALTER TABLE `kategori1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kategori1`
--
ALTER TABLE `kategori1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kategori`
--
ALTER TABLE `kategori`
  ADD CONSTRAINT `kategori_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemasukan_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
