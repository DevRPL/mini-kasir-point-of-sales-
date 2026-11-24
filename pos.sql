/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : pos

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 24/11/2026 16:15:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2024_01_01_000001_create_products_table', 1);
INSERT INTO `migrations` VALUES (6, '2024_01_01_000002_create_shifts_table', 1);
INSERT INTO `migrations` VALUES (7, '2024_01_01_000003_create_transactions_table', 1);
INSERT INTO `migrations` VALUES (8, '2024_01_01_000004_add_role_and_user_links', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_price` bigint UNSIGNED NOT NULL DEFAULT 0,
  `price` bigint UNSIGNED NOT NULL,
  `stock` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `products_code_unique`(`code` ASC) USING BTREE,
  INDEX `products_category_index`(`category` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, '8999999001', 'Indomie Goreng', 'Makanan', 2800, 3500, 49, '2026-11-23 09:35:51', '2026-11-24 15:16:26');
INSERT INTO `products` VALUES (2, '8999999002', 'Beras Premium 5kg', 'Makanan', 60000, 68000, 11, '2026-11-23 09:35:51', '2026-11-24 14:54:02');
INSERT INTO `products` VALUES (3, '8999999003', 'Roti Tawar Sari Roti', 'Makanan', 13000, 16000, 6, '2026-11-23 09:35:51', '2026-11-24 14:10:42');
INSERT INTO `products` VALUES (4, '8999999004', 'Aqua 600ml', 'Minuman', 2500, 3500, 114, '2026-11-23 09:35:51', '2026-11-24 14:53:54');
INSERT INTO `products` VALUES (5, '8999999005', 'Teh Pucuk 350ml', 'Minuman', 3200, 4500, 58, '2026-11-23 09:35:51', '2026-11-24 14:10:42');
INSERT INTO `products` VALUES (6, '8999999006', 'Kopi Kapal Api Sachet', 'Minuman', 1200, 1800, 5, '2026-11-23 09:35:51', '2026-11-24 14:10:42');
INSERT INTO `products` VALUES (7, '8999999007', 'Minyak Goreng 1L', 'Kebutuhan Harian', 16000, 19500, 21, '2026-11-23 09:35:51', '2026-11-24 14:11:51');
INSERT INTO `products` VALUES (8, '8999999008', 'Gula Pasir 1kg', 'Kebutuhan Harian', 14500, 17000, 3, '2026-11-23 09:35:51', '2026-11-24 15:17:22');
INSERT INTO `products` VALUES (9, '8999999009', 'Tisu Paseo', 'Kebutuhan Harian', 9000, 12000, 27, '2026-11-23 09:35:51', '2026-11-24 14:11:51');
INSERT INTO `products` VALUES (10, '8999999010', 'Sabun Lifebuoy', 'Perlengkapan Mandi', 3000, 4500, 37, '2026-11-23 09:35:51', '2026-11-24 14:10:42');
INSERT INTO `products` VALUES (11, '8999999011', 'Shampoo Clear 70ml', 'Perlengkapan Mandi', 8500, 11000, 19, '2026-11-23 09:35:51', '2026-11-24 14:10:42');
INSERT INTO `products` VALUES (12, '8999999012', 'Pasta Gigi Pepsodent', 'Perlengkapan Mandi', 7000, 9500, 1, '2026-11-23 09:35:51', '2026-11-24 15:16:26');
INSERT INTO `products` VALUES (13, '8999999013', 'Chitato 68g', 'Snack', 8500, 11500, 38, '2026-11-23 09:35:51', '2026-11-24 14:10:42');
INSERT INTO `products` VALUES (14, '8999999014', 'Oreo Original', 'Snack', 6000, 8500, 31, '2026-11-23 09:35:51', '2026-11-24 15:16:26');
INSERT INTO `products` VALUES (15, '8999999015', 'Silverqueen 58g', 'Snack', 11000, 15000, 15, '2026-11-23 09:35:51', '2026-11-24 14:11:51');
INSERT INTO `products` VALUES (16, '9328437456', 'Taro', 'Makanan', 20000, 30000, 14, '2026-11-23 17:16:39', '2026-11-24 15:16:26');
INSERT INTO `products` VALUES (17, '3764573457', 'teh pucuk', 'Minuman', 2000, 3000, 8, '2026-11-23 17:17:32', '2026-11-24 14:50:54');
INSERT INTO `products` VALUES (18, '4573456', 'Teh Pucuk Besar 1 Liter', 'Minuman', 9000, 10000, 13, '2026-11-24 15:15:02', '2026-11-24 15:15:11');

-- ----------------------------
-- Table structure for shifts
-- ----------------------------
DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `cashier` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_cash` bigint UNSIGNED NOT NULL DEFAULT 0,
  `started_at` timestamp NOT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `cash_total` bigint UNSIGNED NOT NULL DEFAULT 0,
  `noncash_total` bigint UNSIGNED NOT NULL DEFAULT 0,
  `trx_count` int UNSIGNED NOT NULL DEFAULT 0,
  `expected_cash` bigint NULL DEFAULT NULL,
  `closing_cash` bigint NULL DEFAULT NULL,
  `difference` bigint NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `shifts_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of shifts
-- ----------------------------
INSERT INTO `shifts` VALUES (15, 1, 'Administrator', 200000, '2026-11-24 14:52:15', NULL, 0, 0, 0, NULL, NULL, NULL, '2026-11-24 14:52:15', '2026-11-24 14:52:15');
INSERT INTO `shifts` VALUES (16, 2, 'Kasir 01', 200000, '2026-11-24 15:16:26', '2026-11-24 15:16:55', 97000, 0, 1, 297000, 300000, 3000, '2026-11-24 15:16:26', '2026-11-24 15:16:55');
INSERT INTO `shifts` VALUES (17, 2, 'Kasir 01', 200000, '2026-11-24 15:16:55', NULL, 0, 0, 0, NULL, NULL, NULL, '2026-11-24 15:16:55', '2026-11-24 15:16:55');

-- ----------------------------
-- Table structure for transaction_items
-- ----------------------------
DROP TABLE IF EXISTS `transaction_items`;
CREATE TABLE `transaction_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint UNSIGNED NOT NULL,
  `cost` bigint UNSIGNED NOT NULL DEFAULT 0,
  `qty` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `transaction_items_transaction_id_foreign`(`transaction_id` ASC) USING BTREE,
  INDEX `transaction_items_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transaction_items
-- ----------------------------
INSERT INTO `transaction_items` VALUES (51, 12, 12, 'Pasta Gigi Pepsodent', 9500, 7000, 4);
INSERT INTO `transaction_items` VALUES (52, 12, 8, 'Gula Pasir 1kg', 17000, 14500, 1);
INSERT INTO `transaction_items` VALUES (53, 12, 1, 'Indomie Goreng', 3500, 2800, 1);
INSERT INTO `transaction_items` VALUES (54, 12, 14, 'Oreo Original', 8500, 6000, 1);
INSERT INTO `transaction_items` VALUES (55, 12, 16, 'Taro', 30000, 20000, 1);

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `cashier` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` bigint UNSIGNED NOT NULL,
  `discount` bigint UNSIGNED NOT NULL DEFAULT 0,
  `tax` bigint UNSIGNED NOT NULL DEFAULT 0,
  `total` bigint UNSIGNED NOT NULL,
  `method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid` bigint UNSIGNED NOT NULL,
  `change_amount` bigint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `transactions_invoice_unique`(`invoice` ASC) USING BTREE,
  INDEX `transactions_shift_id_foreign`(`shift_id` ASC) USING BTREE,
  INDEX `transactions_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `transactions_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES (12, 'INV-20261124-0001', 16, 2, 'Kasir 01', 97000, 0, 0, 97000, 'Tunai', 100000, 3000, '2026-11-24 15:16:26', '2026-11-24 15:16:26');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'admin@minimarket.test', NULL, '$2y$10$nQ1IumIwd4dQOfXdBIIVzu6GEm057k5oy.E8O0DzlC0gzzM0cIXZm', 'admin', 1, NULL, '2026-11-23 09:35:51', '2026-11-23 09:35:51');
INSERT INTO `users` VALUES (2, 'Kasir 01', 'kasir@minimarket.test', NULL, '$2y$10$XB/NiKlIt47fIbpbEyychuHxPygXp3ncszheDTBIJ8pxXAnDObkPa', 'kasir', 1, NULL, '2026-11-23 09:35:51', '2026-11-24 14:51:16');
INSERT INTO `users` VALUES (3, 'kasir 02', 'kasir02@test.test', NULL, '$2y$10$NmIHOM8yTd8XM2DQbFFV4epr5.iMhtd9ZSnOP9u7l4Efak6ACCcRK', 'kasir', 1, NULL, '2026-11-24 15:15:42', '2026-11-24 15:15:42');

SET FOREIGN_KEY_CHECKS = 1;
