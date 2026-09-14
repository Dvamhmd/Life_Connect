-- ==========================================================
-- Database SQL Dump for phpMyAdmin / MySQL
-- Project: Life Connect (LifeMedia Prospective Customer System)
-- Generated: 2026-09-11
-- ==========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

CREATE DATABASE IF NOT EXISTS `life_connect` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `life_connect`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sales_id` varchar(50) DEFAULT NULL,
  `role` enum('admin_vas','admin_sales','opj','c_care','sales') NOT NULL DEFAULT 'sales',
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_sales_id_index` (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `sales_id`, `role`, `phone`, `avatar`, `status`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin VAS LifeMedia', 'vas@lifemedia.id', NULL, 'admin_vas', '081122334401', NULL, 'active', '$2y$12$K1Q/0e3fXwIe9qEaIq3lreEepKsp3k19Zg3g7n4e6h2k8l0m9n1o2', NOW(), NOW()),
(2, 'Admin Sales Leader', 'salesadmin@lifemedia.id', NULL, 'admin_sales', '081122334402', NULL, 'active', '$2y$12$K1Q/0e3fXwIe9qEaIq3lreEepKsp3k19Zg3g7n4e6h2k8l0m9n1o2', NOW(), NOW()),
(3, 'Tim OPJ Verifikator', 'opj@lifemedia.id', NULL, 'opj', '081122334403', NULL, 'active', '$2y$12$K1Q/0e3fXwIe9qEaIq3lreEepKsp3k19Zg3g7n4e6h2k8l0m9n1o2', NOW(), NOW()),
(4, 'Customer Care Officer', 'ccare@lifemedia.id', NULL, 'c_care', '081122334404', NULL, 'active', '$2y$12$K1Q/0e3fXwIe9qEaIq3lreEepKsp3k19Zg3g7n4e6h2k8l0m9n1o2', NOW(), NOW()),
(5, 'Budi Pratama (AM)', 'sales01@lifemedia.id', 'AM-101', 'sales', '081234567801', NULL, 'active', '$2y$12$K1Q/0e3fXwIe9qEaIq3lreEepKsp3k19Zg3g7n4e6h2k8l0m9n1o2', NOW(), NOW()),
(6, 'Dewi Lestari (AM)', 'sales02@lifemedia.id', 'AM-102', 'sales', '081234567802', NULL, 'active', '$2y$12$K1Q/0e3fXwIe9qEaIq3lreEepKsp3k19Zg3g7n4e6h2k8l0m9n1o2', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `subscription_packages`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `subscription_packages`;
CREATE TABLE `subscription_packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `speed` varchar(50) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `features` json DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packages_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subscription_packages` (`id`, `code`, `name`, `speed`, `price`, `description`, `features`, `is_popular`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'LM-50M', 'Life Fiber 50 Mbps - Hemat', '50 Mbps', 250000.00, 'Ideal untuk penggunaan browsing harian, meeting Zoom, dan 3-5 perangkat aktif.', '[\"Kecepatan hingga 50 Mbps\", \"100% Fiber Optic murni\", \"Gratis Sewa ONT Wi-Fi Router\", \"Unlimited Tanpa FUP\"]', 0, 1, NOW(), NOW()),
(2, 'LM-100M', 'Life Fiber 100 Mbps - Paling Laris', '100 Mbps', 350000.00, 'Paket favorit keluarga untuk streaming 4K tanpa buffering dan kerja dari rumah multi-perangkat.', '[\"Kecepatan simetris 100 Mbps\", \"Dual Band AC Gigabit Router\", \"Prioritas Dukungan Teknis 24/7\", \"Bisa tambah Add-on IPTV\"]', 1, 1, NOW(), NOW()),
(3, 'LM-200M', 'Life Gamer 200 Mbps - Pro Gamer', '200 Mbps', 550000.00, 'Koneksi ultra-stabil dengan routing low-latency khusus gaming dan content creator.', '[\"Kecepatan simetris 200 Mbps\", \"Gaming Traffic Routing Priority\", \"Dual Band Wi-Fi 6 Router\", \"Free 1 Mesh WiFi Extender\"]', 0, 1, NOW(), NOW()),
(4, 'LM-300M', 'Life Ultra 300 Mbps - Ultimate', '300 Mbps', 850000.00, 'Kecepatan maksimal untuk smart home, kantor, dan kebutuhan bandwidth masif tanpa batas.', '[\"Kecepatan simetris 300 Mbps\", \"Free 4K Android Smart Box (100+ Channels)\", \"Dedicated Bandwidth Support\", \"Free 2 Unit Mesh Wi-Fi 6\"]', 0, 1, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `regions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('provinsi','kabupaten','kecamatan','kelurahan') NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regions_type_index` (`type`),
  KEY `regions_parent_id_foreign` (`parent_id`),
  CONSTRAINT `regions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `regions` (`id`, `type`, `parent_id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, 'provinsi', NULL, 'DIY', 'D.I. Yogyakarta', NOW(), NOW()),
(2, 'provinsi', NULL, 'JTG', 'Jawa Tengah', NOW(), NOW()),
(3, 'kabupaten', 1, 'SLM', 'Kabupaten Sleman', NOW(), NOW()),
(4, 'kabupaten', 1, 'BTL', 'Kabupaten Bantul', NOW(), NOW()),
(5, 'kabupaten', 1, 'YK', 'Kota Yogyakarta', NOW(), NOW()),
(6, 'kecamatan', 3, NULL, 'Kecamatan Depok', NOW(), NOW()),
(7, 'kelurahan', 6, NULL, 'Condongcatur', NOW(), NOW()),
(8, 'kelurahan', 6, NULL, 'Caturtunggal', NOW(), NOW()),
(9, 'kelurahan', 6, NULL, 'Maguwoharjo', NOW(), NOW()),
(10, 'kecamatan', 3, NULL, 'Kecamatan Mlati', NOW(), NOW()),
(11, 'kelurahan', 10, NULL, 'Sinduadi', NOW(), NOW()),
(12, 'kelurahan', 10, NULL, 'Sendangadi', NOW(), NOW()),
(13, 'kelurahan', 10, NULL, 'Sumberadi', NOW(), NOW()),
(14, 'kecamatan', 3, NULL, 'Kecamatan Ngaglik', NOW(), NOW()),
(15, 'kelurahan', 14, NULL, 'Sariharjo', NOW(), NOW()),
(16, 'kelurahan', 14, NULL, 'Minomartani', NOW(), NOW()),
(17, 'kelurahan', 14, NULL, 'Sardonoharjo', NOW(), NOW()),
(18, 'kecamatan', 4, NULL, 'Kecamatan Banguntapan', NOW(), NOW()),
(19, 'kelurahan', 18, NULL, 'Banguntapan', NOW(), NOW()),
(20, 'kelurahan', 18, NULL, 'Baturetno', NOW(), NOW()),
(21, 'kelurahan', 18, NULL, 'Wirokerten', NOW(), NOW()),
(22, 'kecamatan', 4, NULL, 'Kecamatan Kasihan', NOW(), NOW()),
(23, 'kelurahan', 22, NULL, 'Tamantirto', NOW(), NOW()),
(24, 'kelurahan', 22, NULL, 'Ngestiharjo', NOW(), NOW()),
(25, 'kecamatan', 5, NULL, 'Kecamatan Gondokusuman', NOW(), NOW()),
(26, 'kelurahan', 25, NULL, 'Kotabaru', NOW(), NOW()),
(27, 'kelurahan', 25, NULL, 'Demangan', NOW(), NOW()),
(28, 'kelurahan', 25, NULL, 'Klitren', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `customer_registrations`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `customer_registrations`;
CREATE TABLE `customer_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_code` varchar(50) NOT NULL,
  `sales_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_am_id` varchar(50) DEFAULT NULL,
  `sales_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone_wa` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `province` varchar(255) NOT NULL,
  `regency` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `address_detail` text DEFAULT NULL,
  `selfie_sales_path` varchar(255) DEFAULT NULL,
  `status` enum('submitted','verified','filled','approved','revision') NOT NULL DEFAULT 'submitted',
  `token` varchar(64) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(50) DEFAULT NULL,
  `emergency_contact_relation` varchar(100) DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `addons` json DEFAULT NULL,
  `billing_method` varchar(255) DEFAULT NULL,
  `billing_email` varchar(255) DEFAULT NULL,
  `billing_cycle` varchar(100) NOT NULL DEFAULT 'Bulanan (Setiap Tgl 1)',
  `ktp_photo_path` varchar(255) DEFAULT NULL,
  `house_photo_path` varchar(255) DEFAULT NULL,
  `signature_path` longtext DEFAULT NULL,
  `terms_agreed` tinyint(1) NOT NULL DEFAULT 0,
  `rejection_notes` text DEFAULT NULL,
  `rejection_category` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `filled_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `revision_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cust_reg_code_unique` (`registration_code`),
  UNIQUE KEY `cust_reg_token_unique` (`token`),
  KEY `cust_reg_sales_user_id_foreign` (`sales_user_id`),
  KEY `cust_reg_package_id_foreign` (`package_id`),
  CONSTRAINT `cust_reg_sales_user_id_foreign` FOREIGN KEY (`sales_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cust_reg_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `subscription_packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping sample data for customer_registrations
INSERT INTO `customer_registrations` (`id`, `registration_code`, `sales_user_id`, `sales_am_id`, `sales_name`, `customer_name`, `phone_wa`, `email`, `latitude`, `longitude`, `province`, `regency`, `district`, `village`, `address_detail`, `selfie_sales_path`, `status`, `token`, `nik`, `birth_date`, `gender`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `package_id`, `addons`, `billing_method`, `billing_email`, `billing_cycle`, `ktp_photo_path`, `house_photo_path`, `signature_path`, `terms_agreed`, `rejection_notes`, `rejection_category`, `submitted_at`, `verified_at`, `filled_at`, `approved_at`, `revision_at`, `created_at`, `updated_at`) VALUES
(1, 'REG-2026-0001', 5, 'AM-101', 'Budi Pratama (AM)', 'Agus Setiawan, S.T.', '081223344551', 'agus.setiawan@gmail.com', -7.76135200, 110.38541200, 'D.I. Yogyakarta', 'Kabupaten Sleman', 'Kecamatan Depok', 'Condongcatur', 'Jl. Ring Road Utara No. 45, RT 04 / RW 12', '/assets/demo/selfie_sample1.jpg', 'submitted', 'tok_demo1234567890abcdef1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bulanan (Setiap Tgl 1)', NULL, NULL, NULL, 0, NULL, NULL, NOW() - INTERVAL 3 HOUR, NULL, NULL, NULL, NULL, NOW(), NOW()),
(2, 'REG-2026-0002', 5, 'AM-101', 'Budi Pratama (AM)', 'dr. Siti Rahmawati', '081398765432', 'siti.rahmawati@yahoo.com', -7.77254000, 110.37890000, 'D.I. Yogyakarta', 'Kabupaten Sleman', 'Kecamatan Depok', 'Caturtunggal', 'Kompleks Dosen UGM Blok B-14', '/assets/demo/selfie_sample2.jpg', 'verified', 'tok_demo1234567890abcdef2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bulanan (Setiap Tgl 1)', NULL, NULL, NULL, 0, NULL, NULL, NOW() - INTERVAL 6 HOUR, NOW() - INTERVAL 5 HOUR, NULL, NULL, NULL, NOW(), NOW()),
(3, 'REG-2026-0003', 6, 'AM-102', 'Dewi Lestari (AM)', 'Hendro Prasetyo', '081755443322', 'hendro.prasetyo@outlook.com', -7.78421000, 110.36952000, 'D.I. Yogyakarta', 'Kota Yogyakarta', 'Kecamatan Gondokusuman', 'Kotabaru', 'Jl. I Dewa Nyoman Oka No. 8', '/assets/demo/selfie_sample3.jpg', 'filled', 'tok_demo1234567890abcdef3', '3471011504890003', '1989-04-15', 'Laki-laki', 'Rina Wahyuni', '081755443399', 'Istri', 2, '[\"4K IPTV STB\", \"WiFi Extender\"]', 'BCA Virtual Account', 'hendro.prasetyo@outlook.com', 'Bulanan (Setiap Tgl 1)', '/assets/demo/ktp_sample.jpg', '/assets/demo/house_sample.jpg', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMTAwIj48cGF0aCBkPSJNMTAgODAgQyA0MCAxMCwgNjUgMTAwLCA5NSA1MCBTIDE1MCAxMCwgMTgwIDgwIFMgMjMwIDQwLCAyODAgNzAiIHN0cm9rZT0iIzFhM2E1ZiIgc3Ryb2tlLXdpZHRoPSIzIiBmaWxsPSJub25lIi8+PC9zdmc+', 1, NULL, NULL, NOW() - INTERVAL 28 HOUR, NOW() - INTERVAL 27 HOUR, NOW() - INTERVAL 2 HOUR, NULL, NULL, NOW(), NOW()),
(4, 'REG-2026-0004', 5, 'AM-101', 'Budi Pratama (AM)', 'Bambang Trihatmojo', '081299887766', 'bambang.tri@gmail.com', -7.81234000, 110.39870000, 'D.I. Yogyakarta', 'Kabupaten Bantul', 'Kecamatan Banguntapan', 'Banguntapan', 'Jl. Gedongkuning Selatan No. 12', '/assets/demo/selfie_sample4.jpg', 'approved', 'tok_demo1234567890abcdef4', '3402012008770002', '1977-08-20', 'Laki-laki', 'Wulandari', '081299887700', 'Keluarga', 3, '[\"WiFi Extender\"]', 'Mandiri Virtual Account', 'bambang.tri@gmail.com', 'Bulanan (Setiap Tgl 1)', '/assets/demo/ktp_sample.jpg', '/assets/demo/house_sample.jpg', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMTAwIj48cGF0aCBkPSJNMjAgNTAgUSA5MCAyMCAxNDAgODAgVCAyNjAgMzAiIHN0cm9rZT0iIzFhM2E1ZiIgc3Ryb2tlLXdpZHRoPSI0IiBmaWxsPSJub25lIi8+PC9zdmc+', 1, NULL, NULL, NOW() - INTERVAL 56 HOUR, NOW() - INTERVAL 55 HOUR, NOW() - INTERVAL 50 HOUR, NOW() - INTERVAL 29 HOUR, NULL, NOW(), NOW()),
(5, 'REG-2026-0005', 6, 'AM-102', 'Dewi Lestari (AM)', 'Anisa Prameswari', '081811223344', 'anisa.prameswari@gmail.com', -7.75620000, 110.36210000, 'D.I. Yogyakarta', 'Kabupaten Sleman', 'Kecamatan Mlati', 'Sinduadi', 'Kutulon Sinduadi RT 02 / RW 08', '/assets/demo/selfie_sample5.jpg', 'revision', 'tok_demo1234567890abcdef5', '3404016503920001', '1992-03-25', 'Perempuan', 'Bapak Subarjo', '081811223399', 'Orang Tua', 1, NULL, 'QRIS (Gopay/ShopeePay)', 'anisa.prameswari@gmail.com', 'Bulanan (Setiap Tgl 1)', '/assets/demo/ktp_sample.jpg', '/assets/demo/house_sample.jpg', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMTAwIj48cGF0aCBkPSJNMjAgODAgQyA3MCAxMCwgMTAwIDkwLCAxNTAgMzAgUyAyMjAgOTAsIDI4MCA0MCIgc3Ryb2tlPSIjMWEzYTVmIiBzdHJva2Utd2lkdGg9IjMiIGZpbGw9Im5vbmUiLz48L3N2Zz4=', 1, 'Foto KTP buram / tidak terbaca pada bagian NIK dan Nama lengkap. Mohon Sales melakukan follow up ke pelanggan untuk mengunggah ulang foto KTP yang lebih jelas.', 'Dokumen KTP Tidak Terbaca', NOW() - INTERVAL 36 HOUR, NOW() - INTERVAL 34 HOUR, NOW() - INTERVAL 6 HOUR, NULL, NOW() - INTERVAL 1 HOUR, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `registration_progress_logs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `registration_progress_logs`;
CREATE TABLE `registration_progress_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_registration_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `actor_name` varchar(255) NOT NULL,
  `actor_role` varchar(100) NOT NULL,
  `from_status` varchar(50) DEFAULT NULL,
  `to_status` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `prog_log_cust_reg_id_foreign` (`customer_registration_id`),
  KEY `prog_log_user_id_foreign` (`user_id`),
  CONSTRAINT `prog_log_cust_reg_id_foreign` FOREIGN KEY (`customer_registration_id`) REFERENCES `customer_registrations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prog_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `registration_progress_logs` (`customer_registration_id`, `user_id`, `actor_name`, `actor_role`, `from_status`, `to_status`, `notes`, `duration_seconds`, `created_at`) VALUES
(1, 5, 'Budi Pratama (AM)', 'Sales (AM)', NULL, 'submitted', 'Survey awal pelanggan di lokasi Condongcatur. Coverage FAT-04 tersedia.', 0, NOW() - INTERVAL 3 HOUR),
(2, 5, 'Budi Pratama (AM)', 'Sales (AM)', NULL, 'submitted', 'Survey awal di Kompleks Dosen UGM.', 0, NOW() - INTERVAL 6 HOUR),
(2, 3, 'Tim OPJ Verifikator', 'OPJ', 'submitted', 'verified', 'Koordinat valid dan berada dalam jangkauan ODP-LM-CT-09 (jarak 45m). Siap bagikan link pendaftaran.', 3600, NOW() - INTERVAL 5 HOUR),
(3, 6, 'Dewi Lestari (AM)', 'Sales (AM)', NULL, 'submitted', 'Survey lokasi Kotabaru.', 0, NOW() - INTERVAL 28 HOUR),
(3, 3, 'Tim OPJ Verifikator', 'OPJ', 'submitted', 'verified', 'Tervalidasi di ODP Kotabaru-03.', 3600, NOW() - INTERVAL 27 HOUR),
(3, NULL, 'Hendro Prasetyo (Pelanggan)', 'Pelanggan', 'verified', 'filled', 'Pelanggan telah mengisi data NIK, memilih paket Life Fiber 100 Mbps, upload dokumen KTP & TTD digital.', 90000, NOW() - INTERVAL 2 HOUR),
(4, 5, 'Budi Pratama (AM)', 'Sales (AM)', NULL, 'submitted', 'Survey awal di Banguntapan Bantul.', 0, NOW() - INTERVAL 56 HOUR),
(4, 3, 'Tim OPJ Verifikator', 'OPJ', 'submitted', 'verified', 'Verifikasi OPJ disetujui.', 3600, NOW() - INTERVAL 55 HOUR),
(4, NULL, 'Bambang Trihatmojo (Pelanggan)', 'Pelanggan', 'verified', 'filled', 'Pelanggan mengisi kelengkapan paket Life Gamer 200M.', 18000, NOW() - INTERVAL 50 HOUR),
(4, 4, 'Customer Care Officer', 'C-Care', 'filled', 'approved', 'Data KTP, Alamat, TTD, dan Billing lengkap valid. Pendaftaran DISETUJUI untuk diterbitkan Work Order instalasi.', 75600, NOW() - INTERVAL 29 HOUR),
(5, 6, 'Dewi Lestari (AM)', 'Sales (AM)', NULL, 'submitted', 'Survey awal Sinduadi Mlati.', 0, NOW() - INTERVAL 36 HOUR),
(5, 3, 'Tim OPJ Verifikator', 'OPJ', 'submitted', 'verified', 'Verifikasi OPJ disetujui.', 7200, NOW() - INTERVAL 34 HOUR),
(5, NULL, 'Anisa Prameswari (Pelanggan)', 'Pelanggan', 'verified', 'filled', 'Pelanggan mengisi data.', 100800, NOW() - INTERVAL 6 HOUR),
(5, 4, 'Customer Care Officer', 'C-Care', 'filled', 'revision', 'Status diubah ke Revision: Foto KTP buram / tidak terbaca pada bagian NIK dan Nama lengkap.', 18000, NOW() - INTERVAL 1 HOUR);

-- --------------------------------------------------------
-- Table structure for table `audit_logs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_role` varchar(100) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL,
  `target_type` varchar(100) DEFAULT NULL,
  `target_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audit_logs` (`user_id`, `user_name`, `user_role`, `action`, `module`, `target_type`, `target_id`, `description`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(5, 'Budi Pratama (AM)', 'sales', 'CREATE_SURVEY', 'Survey', 'CustomerRegistration', 1, 'Sales Budi Pratama (AM-101) mengajukan data survey baru untuk calon pelanggan Agus Setiawan.', NULL, '{\"status\": \"submitted\", \"customer_name\": \"Agus Setiawan, S.T.\", \"registration_code\": \"REG-2026-0001\"}', '127.0.0.1', 'LifeConnectMobileApp/2.4 (Android 14)', NOW() - INTERVAL 3 HOUR),
(3, 'Tim OPJ Verifikator', 'opj', 'VERIFY_SURVEY', 'OPJ', 'CustomerRegistration', 2, 'OPJ memverifikasi lokasi survey dr. Siti Rahmawati (REG-2026-0002) dan mengaktifkan tautan form pelanggan.', '{\"status\": \"submitted\"}', '{\"status\": \"verified\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 5 HOUR),
(4, 'Customer Care Officer', 'c_care', 'APPROVE_CUSTOMER', 'CCare', 'CustomerRegistration', 4, 'C-Care menyetujui pendaftaran Bambang Trihatmojo (REG-2026-0004) dengan paket Life Gamer 200 Mbps.', '{\"status\": \"filled\"}', '{\"status\": \"approved\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 29 HOUR);

-- --------------------------------------------------------
-- Table structure for table `notifications`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_am_id` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'general',
  `customer_registration_id` bigint(20) UNSIGNED DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `notif_user_id_foreign` (`user_id`),
  KEY `notif_cust_reg_id_foreign` (`customer_registration_id`),
  CONSTRAINT `notif_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notif_cust_reg_id_foreign` FOREIGN KEY (`customer_registration_id`) REFERENCES `customer_registrations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifications` (`user_id`, `sales_am_id`, `title`, `message`, `type`, `customer_registration_id`, `link`, `action_type`, `is_read`, `created_at`) VALUES
(5, 'AM-101', 'Survey Terverifikasi OPJ!', 'Pengajuan pelanggan dr. Siti Rahmawati (REG-2026-0002) telah diverifikasi OPJ. Silakan bagikan link WhatsApp ke pelanggan.', 'survey_verified', 2, '/pendaftaran/tok_demo1234567890abcdef2', 'share_whatsapp', 0, NOW() - INTERVAL 5 HOUR),
(5, 'AM-101', 'Pengajuan Pelanggan Disetujui (Approved)!', 'Selamat! Pendaftaran pelanggan Bambang Trihatmojo (REG-2026-0004) telah di-approve oleh C-Care.', 'registration_approved', 4, NULL, 'view_detail', 1, NOW() - INTERVAL 29 HOUR),
(6, 'AM-102', 'Perhatian: Pengajuan Memerlukan Revisi', 'Pendaftaran Anisa Prameswari (REG-2026-0005) ditolak oleh C-Care dengan catatan: Foto KTP buram. Silakan follow up ke pelanggan.', 'registration_revision', 5, '/pendaftaran/tok_demo1234567890abcdef5', 'revise_data', 0, NOW() - INTERVAL 1 HOUR);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
