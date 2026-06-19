-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for kanker_serviks
CREATE DATABASE IF NOT EXISTS `kanker_serviks` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kanker_serviks`;

-- Dumping structure for table kanker_serviks.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table kanker_serviks.gejala
CREATE TABLE IF NOT EXISTS `gejala` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_gejala` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_gejala` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gejala_kode_gejala_unique` (`kode_gejala`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.gejala: ~34 rows (approximately)
INSERT INTO `gejala` (`id`, `kode_gejala`, `nama_gejala`, `created_at`, `updated_at`) VALUES
	(41, 'G01', 'Nyeri pada tulang punggung', '2026-06-16 06:58:21', '2026-06-16 06:58:21'),
	(42, 'G02', 'Ada riwayat keluarga menderita kanker serviks', '2026-06-16 06:58:31', '2026-06-16 06:58:31'),
	(43, 'G03', 'Merokok', '2026-06-16 06:58:44', '2026-06-16 06:58:44'),
	(44, 'G04', 'Tidak pernah melakukan skrining pap smear/IVA', '2026-06-16 06:58:48', '2026-06-16 06:58:48'),
	(45, 'G05', 'Keluar darah yang tidak wajar dari vagina', '2026-06-16 06:58:56', '2026-06-16 06:58:56'),
	(46, 'G06', 'Nyeri pada pinggang', '2026-06-16 06:59:04', '2026-06-16 06:59:04'),
	(47, 'G07', 'Nyeri pada panggul', '2026-06-16 06:59:13', '2026-06-16 06:59:13'),
	(48, 'G08', 'Keputihan yang berlebihan', '2026-06-16 06:59:33', '2026-06-16 06:59:33'),
	(49, 'G09', 'Pendarahan setelah melakukan hubungan seks', '2026-06-16 06:59:45', '2026-06-16 06:59:45'),
	(50, 'G10', 'Pendarahan pasca menopause', '2026-06-16 07:00:02', '2026-06-16 07:00:02'),
	(51, 'G11', 'Sakit perut yang hebat saat haid maupun tidak haid', '2026-06-16 07:00:12', '2026-06-16 07:00:12'),
	(52, 'G12', 'Keputihan berbau busuk', '2026-06-16 07:00:44', '2026-06-16 07:00:44'),
	(53, 'G13', 'Berganti pasangan seksual lebih dari 1 (satu) kali', '2026-06-16 07:00:51', '2026-06-16 07:00:51'),
	(54, 'G14', 'Melakukan hubungan seksual sebelum usia 20 tahun', '2026-06-16 07:00:59', '2026-06-16 07:00:59'),
	(55, 'G15', 'Jumlah paritas/melahirkan lebih dari 3 (tiga) kali', '2026-06-16 07:01:10', '2026-06-16 07:01:10'),
	(56, 'G16', 'Siklus menstruasi yang tidak teratur', '2026-06-16 07:01:23', '2026-06-16 07:01:23'),
	(57, 'G17', 'Penggunaan pil kb dalam jangka panjang atau lebih dari 5 (lima) tahun', '2026-06-16 07:01:38', '2026-06-16 07:01:38'),
	(58, 'G18', 'Keluar darah bercampur nanah dari vagina', '2026-06-16 07:01:48', '2026-06-16 07:01:48'),
	(59, 'G19', 'Rasa nyeri saat berhubungan seks', '2026-06-16 07:01:59', '2026-06-16 07:01:59'),
	(60, 'G20', 'Pendarahan menstruasi lebih banyak dan lebih lama', '2026-06-16 07:02:16', '2026-06-16 07:02:16'),
	(61, 'G21', 'Pembengkakan pada salah satu kaki', '2026-06-16 07:02:24', '2026-06-16 07:02:24'),
	(62, 'G22', 'Buang air kecil lebih dari 4-8 kali dalam sehari', '2026-06-16 07:02:32', '2026-06-16 07:02:32'),
	(63, 'G23', 'Sulit buang air besar', '2026-06-16 07:02:39', '2026-06-16 07:02:39'),
	(64, 'G24', 'Badan lemas', '2026-06-16 07:02:45', '2026-06-16 07:02:45'),
	(65, 'G25', 'Hilangnya nafsu makan', '2026-06-16 07:02:52', '2026-06-16 07:02:52'),
	(66, 'G26', 'Berat badan menurun', '2026-06-16 07:03:00', '2026-06-16 07:03:00'),
	(67, 'G27', 'Muntah dalam jumlah banyak', '2026-06-16 07:03:06', '2026-06-16 07:03:06'),
	(68, 'G28', 'Sesak nafas', '2026-06-16 07:03:13', '2026-06-16 07:03:13'),
	(69, 'G29', 'Rasa sakit di sisi kanan perut', '2026-06-16 07:03:24', '2026-06-16 07:03:24'),
	(70, 'G30', 'Wajah terlihat pucat akibat perdarahan yang tidak normal dari vagina', '2026-06-16 07:03:30', '2026-06-16 07:03:30'),
	(71, 'G31', 'Pembengkakan kelenjar getah bening', '2026-06-16 07:03:45', '2026-06-16 07:03:45'),
	(72, 'G32', 'Nyeri saat buang air kecil', '2026-06-16 07:03:53', '2026-06-16 07:03:53'),
	(73, 'G33', 'Darah dalam urine', '2026-06-16 07:03:59', '2026-06-16 07:03:59'),
	(74, 'G34', 'Nyeri saat buang air besar', '2026-06-16 07:04:11', '2026-06-16 07:04:11'),
	(75, 'G35', 'Pembengkakan perut', '2026-06-16 07:04:21', '2026-06-16 07:04:21'),
	(76, 'G36', 'Perut terasa penuh atau kembung', '2026-06-16 07:04:29', '2026-06-16 07:04:29'),
	(77, 'G37', 'Kelelahan Kronis', '2026-06-16 07:04:37', '2026-06-16 07:04:37');

-- Dumping structure for table kanker_serviks.hasil_konsultasi
CREATE TABLE IF NOT EXISTS `hasil_konsultasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `jenis_id` bigint unsigned NOT NULL,
  `total_cf` double NOT NULL,
  `tgl_konsultasi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hasil_konsultasi_user_id_foreign` (`user_id`),
  KEY `hasil_konsultasi_jenis_id_foreign` (`jenis_id`),
  CONSTRAINT `hasil_konsultasi_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `jenis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_konsultasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.hasil_konsultasi: ~2 rows (approximately)
INSERT INTO `hasil_konsultasi` (`id`, `user_id`, `jenis_id`, `total_cf`, `tgl_konsultasi`, `created_at`, `updated_at`) VALUES
	(18, 1, 1, 0.705088, '2026-06-16 07:40:04', '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(19, 1, 1, 0.75424, '2026-06-16 07:49:13', '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(20, 2, 5, 0.967232, '2026-06-17 18:58:06', '2026-06-17 18:58:06', '2026-06-17 18:58:06');

-- Dumping structure for table kanker_serviks.jenis
CREATE TABLE IF NOT EXISTS `jenis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `solusi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_kode_jenis_unique` (`kode_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.jenis: ~4 rows (approximately)
INSERT INTO `jenis` (`id`, `kode_jenis`, `nama_jenis`, `solusi`, `created_at`, `updated_at`) VALUES
	(1, 'J01', 'Karsinoma Sel Skuamosa', 'Disarankan untuk segera melakukan pemeriksaan ke dokter spesialis Obstetri dan Ginekologi (Obgyn) guna mendapatkan evaluasi lebih lanjut.  \r\nPasien juga dianjurkan untuk menghentikan kebiasaan merokok, menerapkan pola hidup sehat, serta melakukan pemeriksaan serviks secara berkala.', '2026-05-08 05:16:50', '2026-06-17 20:47:34'),
	(3, 'J02', 'Adenokarsinoma', 'Disarankan untuk berkonsultasi dengan dokter spesialis Obgyn guna mendapatkan pemeriksaan lebih lanjut. Karena jenis ini sering berkembang pada bagian dalam serviks dan lebih sulit terdeteksi pada tahap awal. \r\nPasien juga disarankan untuk rutin melakukan skrining serviks dan mengontrol penggunaan kontrasepsi hormonal jangka panjang sesuai anjuran tenaga medis.', '2026-05-08 06:03:02', '2026-06-17 20:54:36'),
	(4, 'J03', 'Karsinoma Adenoskuamosa', 'Disarankan untuk segera melakukan pemeriksaan lanjutan kepada dokter spesialis Obgyn guna memastikan kondisi serviks melalui pemeriksaan fisik. \r\nPasien juga dianjurkan untuk menghindari faktor risiko yang dapat meningkatkan perkembangan penyakit, seperti merokok, berganti pasangan seksual, dan tidak melakukan skrining serviks secara rutin.', '2026-06-11 07:49:10', '2026-06-17 20:55:20'),
	(5, 'J04', 'Kanker Sel Kecil', 'Jenis ini dikenal sebagai kanker serviks yang relatif agresif dan dapat berkembang lebih cepat dibandingkan jenis lainnya. Oleh karena itu, pengguna disarankan untuk segera berkonsultasi dengan dokter spesialis Obgyn guna menjalani pemeriksaan menyeluruh.', '2026-06-11 07:50:04', '2026-06-17 21:01:10');

-- Dumping structure for table kanker_serviks.konsultasi
CREATE TABLE IF NOT EXISTS `konsultasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `hasil_konsultasi_id` bigint unsigned DEFAULT NULL,
  `gejala_id` bigint unsigned NOT NULL,
  `nilai_cf_user` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `konsultasi_user_id_foreign` (`user_id`),
  KEY `konsultasi_gejala_id_foreign` (`gejala_id`),
  KEY `konsultasi_hasil_konsultasi_id_foreign` (`hasil_konsultasi_id`),
  CONSTRAINT `konsultasi_gejala_id_foreign` FOREIGN KEY (`gejala_id`) REFERENCES `gejala` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konsultasi_hasil_konsultasi_id_foreign` FOREIGN KEY (`hasil_konsultasi_id`) REFERENCES `hasil_konsultasi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konsultasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.konsultasi: ~21 rows (approximately)
INSERT INTO `konsultasi` (`id`, `user_id`, `hasil_konsultasi_id`, `gejala_id`, `nilai_cf_user`, `created_at`, `updated_at`) VALUES
	(52, 1, 18, 41, 1, '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(53, 1, 18, 42, 1, '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(54, 1, 18, 43, 1, '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(55, 1, 18, 44, 1, '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(56, 1, 18, 60, 1, '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(57, 1, 18, 61, 1, '2026-06-16 07:40:04', '2026-06-16 07:40:04'),
	(58, 1, 19, 41, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(59, 1, 19, 42, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(60, 1, 19, 43, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(61, 1, 19, 44, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(62, 1, 19, 60, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(63, 1, 19, 56, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(64, 1, 19, 57, 1, '2026-06-16 07:49:13', '2026-06-16 07:49:13'),
	(65, 2, 20, 41, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(66, 2, 20, 42, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(67, 2, 20, 53, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(68, 2, 20, 60, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(69, 2, 20, 64, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(70, 2, 20, 65, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(71, 2, 20, 66, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06'),
	(72, 2, 20, 72, 1, '2026-06-17 18:58:06', '2026-06-17 18:58:06');

-- Dumping structure for table kanker_serviks.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.migrations: ~6 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2026_04_23_211306_create_expert_system_tables', 2),
	(6, '2026_04_23_211721_create_diagnostic_tables', 2),
	(7, '2026_06_12_162916_add_hasil_konsultasi_id_to_konsultasi_table', 3);

-- Dumping structure for table kanker_serviks.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.password_resets: ~0 rows (approximately)

-- Dumping structure for table kanker_serviks.rules
CREATE TABLE IF NOT EXISTS `rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_id` bigint unsigned NOT NULL,
  `gejala_id` bigint unsigned NOT NULL,
  `mb` double NOT NULL,
  `md` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rules_jenis_id_foreign` (`jenis_id`),
  KEY `rules_gejala_id_foreign` (`gejala_id`),
  CONSTRAINT `rules_gejala_id_foreign` FOREIGN KEY (`gejala_id`) REFERENCES `gejala` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rules_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `jenis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.rules: ~77 rows (approximately)
INSERT INTO `rules` (`id`, `jenis_id`, `gejala_id`, `mb`, `md`, `created_at`, `updated_at`) VALUES
	(1, 1, 41, 0.4, 0.6, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(2, 1, 42, 0.2, 0.8, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(3, 1, 43, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(4, 1, 44, 0.6, 0.4, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(5, 1, 45, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(6, 1, 46, 0.4, 0.6, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(7, 1, 47, 0.6, 0.4, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(8, 1, 48, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(9, 1, 49, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(10, 1, 50, 0.6, 0.4, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(11, 1, 51, 0.4, 0.6, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(12, 1, 52, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(13, 1, 53, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(14, 1, 54, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(15, 1, 55, 0.6, 0.4, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(16, 1, 58, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(17, 1, 59, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(18, 1, 60, 0.8, 0.2, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(19, 1, 61, 0.4, 0.6, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(20, 1, 62, 0.2, 0.8, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(21, 1, 63, 0.2, 0.8, '2026-06-16 07:13:47', '2026-06-16 07:13:47'),
	(22, 3, 41, 0.2, 0.8, '2026-06-16 07:17:07', '2026-06-16 07:17:07'),
	(23, 3, 42, 0.2, 0.8, '2026-06-16 07:17:07', '2026-06-16 07:17:07'),
	(24, 3, 43, 0.2, 0.8, '2026-06-16 07:17:07', '2026-06-16 07:17:07'),
	(25, 3, 44, 0.6, 0.4, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(26, 3, 45, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(27, 3, 46, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(28, 3, 47, 0.6, 0.4, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(29, 3, 48, 0.6, 0.4, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(30, 3, 49, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(31, 3, 50, 0.8, 0.2, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(32, 3, 51, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(33, 3, 52, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(34, 3, 53, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(35, 3, 54, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(36, 3, 55, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(37, 3, 56, 0.8, 0.2, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(38, 3, 57, 0.8, 0.2, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(39, 3, 60, 0.4, 0.6, '2026-06-16 07:17:08', '2026-06-16 07:17:08'),
	(40, 4, 41, 0.4, 0.6, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(41, 4, 42, 0.2, 0.8, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(42, 4, 43, 0.4, 0.6, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(43, 4, 44, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(44, 4, 45, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(45, 4, 46, 0.4, 0.6, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(46, 4, 47, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(47, 4, 48, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(48, 4, 49, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(49, 4, 50, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(50, 4, 51, 0.4, 0.6, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(51, 4, 52, 0.8, 0.2, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(52, 4, 53, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(53, 4, 54, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(54, 4, 55, 0.4, 0.6, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(55, 4, 56, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(56, 4, 57, 0.6, 0.4, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(57, 4, 58, 0.8, 0.2, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(58, 4, 59, 0.8, 0.2, '2026-06-16 07:19:32', '2026-06-16 07:19:32'),
	(59, 5, 41, 0.8, 0.2, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(60, 5, 42, 0.2, 0.8, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(61, 5, 43, 0.2, 0.8, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(62, 5, 44, 0.4, 0.6, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(63, 5, 61, 0.6, 0.4, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(64, 5, 64, 0.8, 0.2, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(65, 5, 65, 0.8, 0.2, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(66, 5, 66, 0.8, 0.2, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(67, 5, 67, 0.8, 0.2, '2026-06-16 07:22:20', '2026-06-16 07:22:20'),
	(68, 5, 68, 0.8, 0.2, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(69, 5, 69, 0.6, 0.4, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(70, 5, 70, 0.4, 0.6, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(71, 5, 71, 0.8, 0.2, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(72, 5, 72, 0.6, 0.4, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(73, 5, 73, 0.6, 0.4, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(74, 5, 74, 0.6, 0.4, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(75, 5, 75, 0.8, 0.2, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(76, 5, 76, 0.6, 0.4, '2026-06-16 07:22:21', '2026-06-16 07:22:21'),
	(77, 5, 77, 0.8, 0.2, '2026-06-16 07:22:21', '2026-06-16 07:22:21');

-- Dumping structure for table kanker_serviks.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dokter',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`phone_number`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kanker_serviks.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `username`, `phone_number`, `role`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', '082293812804', 'admin', NULL, '$2y$10$C0dMD6gnfe61o9sDMdXQPORlOyAhpcrExi1964WbClWaY7litPQ7K', NULL, NULL, NULL, '2026-04-13 03:41:05', '2026-06-10 01:05:59'),
	(2, 'JOJO', '081234567890', 'pasien', NULL, '$2y$10$bQobXUcrmEwdQhOcfPGuZuO/caGRmmE1/rbZsfxk5LluQdlToYYKC', NULL, NULL, NULL, '2026-04-23 13:05:24', '2026-05-16 05:44:41'),
	(10, 'Ayu', '082293812800', 'pasien', NULL, '$2y$10$Sinr9Y0eRh34bfMU89ezJ.BF1jiUVQIofK6ypoL/VURa7OlybxtRO', NULL, NULL, NULL, '2026-05-16 04:35:23', '2026-05-16 04:59:39'),
	(11, 'Reski', '082293812801', 'pasien', NULL, '$2y$10$tTCOzb8.toc3pRptHp.Hnu.b6.6dXlc0onSQjy6pypn6sO0l6R2Sy', NULL, NULL, NULL, '2026-06-17 20:11:47', '2026-06-17 20:11:47');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
