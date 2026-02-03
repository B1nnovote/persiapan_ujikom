-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 04:23 AM
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
-- Database: `parkir_otomatis`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_admn01@gmail.com|127.0.0.1', 'i:1;', 1752584062),
('laravel_cache_admn01@gmail.com|127.0.0.1:timer', 'i:1752584062;', 1752584062);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_kendaraans`
--

CREATE TABLE `data_kendaraans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_polisi` varchar(255) NOT NULL,
  `jenis_kendaraan` enum('mobil','motor') NOT NULL,
  `pemilik` varchar(255) DEFAULT NULL,
  `status_pemilik` enum('tamu','guru','karyawan') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_kendaraans`
--

INSERT INTO `data_kendaraans` (`id`, `no_polisi`, `jenis_kendaraan`, `pemilik`, `status_pemilik`, `created_at`, `updated_at`) VALUES
(1, 'BDG 1546 HG', 'motor', NULL, 'tamu', '2025-07-13 15:40:17', '2025-07-13 15:40:17'),
(2, 'D 1654 YX', 'motor', NULL, 'tamu', '2025-07-13 15:42:25', '2025-07-13 15:42:25'),
(3, 'B 5435 ZX', 'mobil', 'Saiful', 'karyawan', '2025-07-14 02:58:58', '2025-07-14 02:58:58'),
(4, 'fg 4536 yh', 'motor', NULL, 'tamu', '2025-07-14 13:58:23', '2025-07-14 13:58:23'),
(5, '456789ujh', 'mobil', NULL, 'tamu', '2025-07-14 14:07:39', '2025-07-14 14:07:39'),
(6, 'GH 0978 KJ', 'motor', 'Dan', 'karyawan', '2025-07-15 01:27:33', '2025-07-15 01:27:33'),
(7, 'po 09 uui', 'mobil', NULL, 'tamu', '2025-07-15 01:28:11', '2025-07-15 01:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan_keluars`
--

CREATE TABLE `kendaraan_keluars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_kendaraan_masuk` bigint(20) UNSIGNED NOT NULL,
  `waktu_keluar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_kondisi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kendaraan_keluars`
--

INSERT INTO `kendaraan_keluars` (`id`, `id_kendaraan_masuk`, `waktu_keluar`, `status_kondisi`, `created_at`, `updated_at`) VALUES
(2, 3, '2025-07-13 15:43:00', 'baik', '2025-07-13 15:42:49', '2025-07-13 15:42:49'),
(3, 2, '2025-07-13 15:55:00', 'karcis hilang', '2025-07-13 15:54:39', '2025-07-13 15:54:39'),
(4, 4, '2025-07-14 08:22:00', 'karcis hilang', '2025-07-14 08:21:57', '2025-07-14 08:21:57'),
(5, 6, '2025-07-14 13:59:00', 'kerusakan', '2025-07-14 13:58:43', '2025-07-14 13:58:43'),
(6, 5, '2025-07-14 14:00:00', 'kehilangan', '2025-07-14 13:59:24', '2025-07-14 13:59:24'),
(7, 7, '2025-07-14 14:04:00', 'kehilangan', '2025-07-14 14:03:44', '2025-07-14 14:03:44'),
(8, 8, '2025-07-14 14:08:00', 'kerusakan', '2025-07-14 14:07:53', '2025-07-14 14:07:53'),
(9, 9, '2025-07-15 01:29:00', 'karcis hilang', '2025-07-15 01:28:44', '2025-07-15 01:28:44'),
(10, 9, '2025-07-15 01:29:00', 'baik', '2025-07-15 01:28:55', '2025-07-15 01:28:55'),
(11, 9, '2025-07-15 01:29:00', 'kerusakan', '2025-07-15 01:29:05', '2025-07-15 01:29:05'),
(12, 10, '2025-07-15 08:40:00', 'karcis hilang', '2025-07-15 01:34:07', '2025-07-15 01:34:07'),
(13, 11, '2025-07-15 07:42:00', 'karcis hilang', '2025-07-15 06:41:58', '2025-07-15 06:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan_masuks`
--

CREATE TABLE `kendaraan_masuks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `waktu_masuk` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_parkir` enum('sedang parkir','sudah keluar') NOT NULL DEFAULT 'sedang parkir',
  `id_kendaraan` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kendaraan_masuks`
--

INSERT INTO `kendaraan_masuks` (`id`, `waktu_masuk`, `status_parkir`, `id_kendaraan`, `created_at`, `updated_at`) VALUES
(2, '2025-07-13 15:54:39', 'sudah keluar', 2, '2025-07-13 15:42:25', '2025-07-13 15:54:39'),
(3, '2025-07-13 15:42:49', 'sudah keluar', 1, '2025-07-13 15:42:32', '2025-07-13 15:42:49'),
(4, '2025-07-14 08:21:57', 'sudah keluar', 2, '2025-07-14 05:48:05', '2025-07-14 08:21:57'),
(5, '2025-07-14 13:59:24', 'sudah keluar', 1, '2025-07-14 13:58:09', '2025-07-14 13:59:24'),
(6, '2025-07-14 13:58:43', 'sudah keluar', 4, '2025-07-14 13:58:23', '2025-07-14 13:58:43'),
(7, '2025-07-14 14:03:44', 'sudah keluar', 1, '2025-07-14 14:03:30', '2025-07-14 14:03:44'),
(8, '2025-07-14 14:07:53', 'sudah keluar', 5, '2025-07-14 14:07:39', '2025-07-14 14:07:53'),
(9, '2025-07-15 01:28:44', 'sudah keluar', 7, '2025-07-15 01:28:11', '2025-07-15 01:28:44'),
(10, '2025-07-15 01:34:07', 'sudah keluar', 2, '2025-07-15 01:33:43', '2025-07-15 01:34:07'),
(11, '2025-07-15 06:41:58', 'sudah keluar', 1, '2025-07-15 06:41:27', '2025-07-15 06:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `keuangans`
--

CREATE TABLE `keuangans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jenis_transaksi` varchar(255) NOT NULL,
  `waktu_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keuangans`
--

INSERT INTO `keuangans` (`id`, `jumlah`, `id_pembayaran`, `keterangan`, `jenis_transaksi`, `waktu_transaksi`, `created_at`, `updated_at`) VALUES
(1, 2000.00, 1, 'biaya_parkir', 'pemasukan', '2025-07-13 15:43:32', '2025-07-13 15:43:32', '2025-07-13 15:43:32'),
(2, 12000.00, 2, 'tiket_hilang', 'pemasukan', '2025-07-13 15:58:56', '2025-07-13 15:58:56', '2025-07-13 15:58:56');

-- --------------------------------------------------------

--
-- Table structure for table `kompensasi`
--

CREATE TABLE `kompensasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_kendaraan_masuk` bigint(20) UNSIGNED NOT NULL,
  `jumlah` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `nama_pemilik` varchar(255) DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `diajukan_pada` timestamp NULL DEFAULT NULL,
  `diproses_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kompensasi`
--

INSERT INTO `kompensasi` (`id`, `id_kendaraan_masuk`, `jumlah`, `status`, `nama_pemilik`, `bukti_foto`, `keterangan`, `diajukan_pada`, `diproses_pada`, `created_at`, `updated_at`) VALUES
(1, 6, 350000, 'pending', 'Mr.Bean', 'kompensasi_foto/zxrtN8rEMaSVFFcLW2cxTE2kxqiY9gVsJOexaJQR.png', '-', '2025-07-14 13:59:00', NULL, '2025-07-14 13:59:00', '2025-07-14 13:59:00'),
(2, 5, 6000000, 'pending', 'Mr.Bean', 'kompensasi_foto/XJhipRbzngHzTOGjwWs6q01KD7uwtJlEd62NyZwP.png', 'motor hilang', '2025-07-14 13:59:42', NULL, '2025-07-14 13:59:42', '2025-07-14 13:59:42'),
(3, 7, 450000, 'disetujui', 'Mr.Bean', 'kompensasi_foto/eal84KMM73TL7ZUBD85KdBk6pDzgEYxDhIItOUGr.png', '-', '2025-07-14 14:04:03', '2025-07-14 14:41:44', '2025-07-14 14:04:03', '2025-07-14 14:41:44'),
(4, 8, 2000, 'pending', 'Tamu', 'kompensasi_foto/twOnY9lvfa1q0Au4Kym3NrkmuDI9tLvNU4tC3U3d.png', 'scdd', '2025-07-14 14:08:08', NULL, '2025-07-14 14:08:08', '2025-07-14 14:08:08'),
(5, 9, 200000, 'disetujui', 'Tamu', 'kompensasi_foto/3D4Ql9UNxnPMRitQBBjTJ1oqbT5U9T5LCWW5IVLA.png', 'kaca', '2025-07-15 01:29:43', '2025-07-15 01:29:57', '2025-07-15 01:29:43', '2025-07-15 01:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_07_065821_create_data_kendaraans_table', 1),
(5, '2025_07_07_065831_create_kendaraan_masuks_table', 1),
(6, '2025_07_07_065854_create_kompensasis_table', 1),
(7, '2025_07_07_065916_create_kendaraan_keluars_table', 1),
(8, '2025_07_07_065934_create_pembayarans_table', 1),
(9, '2025_07_07_070052_create_keuangans_table', 1),
(10, '2025_07_10_130530_add_jenis_pemasukan_to_keuangans_table', 1),
(11, '2025_07_10_222305_create_stok_lahans_table', 1),
(12, '2025_07_13_195044_add_foto_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_kendaraan_masuk` bigint(20) UNSIGNED NOT NULL,
  `id_kendaraan_keluar` bigint(20) UNSIGNED NOT NULL,
  `id_kompensasi` bigint(20) UNSIGNED DEFAULT NULL,
  `id_petugas` bigint(20) UNSIGNED DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `pembayaran` enum('tunai','qris','gratis','kompensasi') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `id_kendaraan_masuk`, `id_kendaraan_keluar`, `id_kompensasi`, `id_petugas`, `total`, `pembayaran`, `created_at`, `updated_at`) VALUES
(1, 3, 2, NULL, NULL, 2000.00, 'qris', '2025-07-13 15:43:32', '2025-07-13 15:43:32'),
(2, 2, 3, NULL, 1, 12000.00, 'qris', '2025-07-13 15:58:55', '2025-07-13 15:58:55'),
(3, 4, 4, NULL, 1, 12000.00, 'tunai', '2025-07-14 08:22:04', '2025-07-14 08:22:04'),
(4, 7, 7, 3, NULL, 450000.00, 'kompensasi', '2025-07-14 14:41:44', '2025-07-14 14:41:44'),
(5, 9, 9, 5, NULL, 200000.00, 'kompensasi', '2025-07-15 01:29:57', '2025-07-15 01:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fVodcbFiBu37CxzjMz23XCLBqTT4jLRbETuLxU19', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNm5XVE5jc3dnYk8yZ2hLanI3U2hyTFZiMnFIS3gxM3A0bEl5YkxBTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wZXR1Z2FzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTI2MjkwNTE7fXM6NToiYWxlcnQiO2E6MDp7fX0=', 1752632174),
('TECjFKCdW3saq38PaeDjzGgxSCLzwUPRIwriHPgn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTDBCRkJNcVY2SXRDQlM4ODBTU0lIa0lOMmRvcWJyaVc0amJsandtQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzUyNjI5MDE0O319', 1752629015);

-- --------------------------------------------------------

--
-- Table structure for table `stok_lahans`
--

CREATE TABLE `stok_lahans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis_kendaraan` enum('motor','mobil') NOT NULL,
  `total_slot` int(11) NOT NULL,
  `terpakai` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_lahans`
--

INSERT INTO `stok_lahans` (`id`, `jenis_kendaraan`, `total_slot`, `terpakai`, `created_at`, `updated_at`) VALUES
(1, 'motor', 90, 0, '2025-07-13 15:35:36', '2025-07-15 01:34:07'),
(2, 'mobil', 20, 1, '2025-07-13 15:35:36', '2025-07-15 06:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `foto`, `email_verified_at`, `password`, `isAdmin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mr.Bean', 'admin01@gmail.com', NULL, NULL, '$2y$12$l2yAd7ZkTIdCDht/Izb7q.TIHnmti19ZQFgsflc0aPmozj7CkpDW.', 1, NULL, '2025-07-13 15:35:35', '2025-07-13 15:35:35'),
(8, 'David', 'petugas01@gmail.com', 'petugas/tZfVhJ8sIxIsghju6DWLgvnQV5E6mdXFForxzNoF.jpg', NULL, '$2y$12$wf4jl.zI3SjhF7pEcg3RiuDI/Zg1kWY7SxkswEFv/48P5tJRcw1V.', 0, NULL, '2025-07-16 02:01:21', '2025-07-16 02:01:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `data_kendaraans`
--
ALTER TABLE `data_kendaraans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_kendaraans_no_polisi_unique` (`no_polisi`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kendaraan_keluars`
--
ALTER TABLE `kendaraan_keluars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kendaraan_keluars_id_kendaraan_masuk_foreign` (`id_kendaraan_masuk`);

--
-- Indexes for table `kendaraan_masuks`
--
ALTER TABLE `kendaraan_masuks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kendaraan_masuks_id_kendaraan_foreign` (`id_kendaraan`);

--
-- Indexes for table `keuangans`
--
ALTER TABLE `keuangans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keuangans_id_pembayaran_foreign` (`id_pembayaran`);

--
-- Indexes for table `kompensasi`
--
ALTER TABLE `kompensasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kompensasi_id_kendaraan_masuk_foreign` (`id_kendaraan_masuk`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayarans_id_kendaraan_masuk_foreign` (`id_kendaraan_masuk`),
  ADD KEY `pembayarans_id_kendaraan_keluar_foreign` (`id_kendaraan_keluar`),
  ADD KEY `pembayarans_id_kompensasi_foreign` (`id_kompensasi`),
  ADD KEY `pembayarans_id_petugas_foreign` (`id_petugas`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stok_lahans`
--
ALTER TABLE `stok_lahans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_kendaraans`
--
ALTER TABLE `data_kendaraans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kendaraan_keluars`
--
ALTER TABLE `kendaraan_keluars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kendaraan_masuks`
--
ALTER TABLE `kendaraan_masuks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `keuangans`
--
ALTER TABLE `keuangans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kompensasi`
--
ALTER TABLE `kompensasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stok_lahans`
--
ALTER TABLE `stok_lahans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kendaraan_keluars`
--
ALTER TABLE `kendaraan_keluars`
  ADD CONSTRAINT `kendaraan_keluars_id_kendaraan_masuk_foreign` FOREIGN KEY (`id_kendaraan_masuk`) REFERENCES `kendaraan_masuks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kendaraan_masuks`
--
ALTER TABLE `kendaraan_masuks`
  ADD CONSTRAINT `kendaraan_masuks_id_kendaraan_foreign` FOREIGN KEY (`id_kendaraan`) REFERENCES `data_kendaraans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `keuangans`
--
ALTER TABLE `keuangans`
  ADD CONSTRAINT `keuangans_id_pembayaran_foreign` FOREIGN KEY (`id_pembayaran`) REFERENCES `pembayarans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kompensasi`
--
ALTER TABLE `kompensasi`
  ADD CONSTRAINT `kompensasi_id_kendaraan_masuk_foreign` FOREIGN KEY (`id_kendaraan_masuk`) REFERENCES `kendaraan_masuks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_id_kendaraan_keluar_foreign` FOREIGN KEY (`id_kendaraan_keluar`) REFERENCES `kendaraan_keluars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_id_kendaraan_masuk_foreign` FOREIGN KEY (`id_kendaraan_masuk`) REFERENCES `kendaraan_masuks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_id_kompensasi_foreign` FOREIGN KEY (`id_kompensasi`) REFERENCES `kompensasi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pembayarans_id_petugas_foreign` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
