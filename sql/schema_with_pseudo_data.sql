/*
 Navicat Premium Dump SQL

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : pos_db

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 28/08/2026 13:50:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cash_transactions
-- ----------------------------
DROP TABLE IF EXISTS `cash_transactions`;
CREATE TABLE `cash_transactions`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `device_id` int UNSIGNED NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `type` enum('starting_cash','sale','return','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` int NULL DEFAULT NULL COMMENT 'sale_id or return_id',
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `cash_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cash_transactions_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 108 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cash_transactions
-- ----------------------------
INSERT INTO `cash_transactions` VALUES (1, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-27 10:19:25');
INSERT INTO `cash_transactions` VALUES (2, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-27 10:19:41');
INSERT INTO `cash_transactions` VALUES (3, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-27 12:37:48');
INSERT INTO `cash_transactions` VALUES (4, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-27 12:39:52');
INSERT INTO `cash_transactions` VALUES (5, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-27 12:46:54');
INSERT INTO `cash_transactions` VALUES (6, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 500.00', '2026-07-27 12:46:58');
INSERT INTO `cash_transactions` VALUES (7, 1, 1, 20.00, 'sale', 3, 'Sale INV-20260727-5325', '2026-07-27 13:19:58');
INSERT INTO `cash_transactions` VALUES (8, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-27 13:21:16');
INSERT INTO `cash_transactions` VALUES (9, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 620.00', '2026-07-27 13:21:23');
INSERT INTO `cash_transactions` VALUES (10, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 620.00', '2026-07-27 13:21:30');
INSERT INTO `cash_transactions` VALUES (11, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 620.00', '2026-07-27 13:24:07');
INSERT INTO `cash_transactions` VALUES (12, 1, 1, 1.00, 'starting_cash', NULL, 'Shift started with 1', '2026-07-27 13:24:22');
INSERT INTO `cash_transactions` VALUES (13, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 621.00', '2026-07-27 13:24:34');
INSERT INTO `cash_transactions` VALUES (14, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-28 09:32:20');
INSERT INTO `cash_transactions` VALUES (15, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 721.00', '2026-07-28 09:34:09');
INSERT INTO `cash_transactions` VALUES (16, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 721.00', '2026-07-28 09:34:14');
INSERT INTO `cash_transactions` VALUES (17, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-28 09:34:57');
INSERT INTO `cash_transactions` VALUES (18, 1, 1, 10.00, 'sale', 4, 'Sale INV-20260728-3789', '2026-07-28 09:35:18');
INSERT INTO `cash_transactions` VALUES (19, 1, 1, 0.00, 'starting_cash', NULL, 'Shift started with 0', '2026-07-28 09:38:53');
INSERT INTO `cash_transactions` VALUES (20, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 831.00', '2026-07-28 09:38:58');
INSERT INTO `cash_transactions` VALUES (21, 1, 1, 0.00, 'adjustment', NULL, 'Shift closed. Final balance: 831.00', '2026-07-28 09:39:31');
INSERT INTO `cash_transactions` VALUES (22, 1, 1, 0.00, 'starting_cash', NULL, 'Shift started with 0', '2026-07-28 09:40:39');
INSERT INTO `cash_transactions` VALUES (23, 1, 1, 0.00, 'starting_cash', NULL, 'Shift started with 0', '2026-07-28 09:41:50');
INSERT INTO `cash_transactions` VALUES (24, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-28 09:42:08');
INSERT INTO `cash_transactions` VALUES (25, 1, 1, 50.00, 'sale', 5, 'Sale INV-20260728-7038', '2026-07-28 09:54:26');
INSERT INTO `cash_transactions` VALUES (26, 1, 1, -50.00, 'return', 1, 'Return for RET-20260728-7729', '2026-07-28 09:55:54');
INSERT INTO `cash_transactions` VALUES (27, 1, 1, 20.00, 'sale', 6, 'Sale INV-20260728-5645', '2026-07-28 09:57:00');
INSERT INTO `cash_transactions` VALUES (28, 1, 1, -10.00, 'return', 2, 'Return for RET-20260728-5913', '2026-07-28 09:58:18');
INSERT INTO `cash_transactions` VALUES (29, 1, 1, 10.00, 'sale', 7, 'Sale INV-20260728-9444', '2026-07-28 12:57:14');
INSERT INTO `cash_transactions` VALUES (30, 1, 1, 10.00, 'sale', 8, 'Sale INV-20260728-6400', '2026-07-28 14:22:49');
INSERT INTO `cash_transactions` VALUES (31, 1, 1, 10.00, 'sale', 9, 'Sale INV-20260728-1771', '2026-07-28 14:23:21');
INSERT INTO `cash_transactions` VALUES (32, 1, 1, 10.00, 'sale', 10, 'Sale INV-20260728-8314', '2026-07-28 14:24:03');
INSERT INTO `cash_transactions` VALUES (33, 1, 1, 10.00, 'sale', 11, 'Sale INV-20260728-5115', '2026-07-28 14:26:45');
INSERT INTO `cash_transactions` VALUES (34, 1, 1, 10.00, 'sale', 12, 'Sale INV-20260728-8312', '2026-07-28 14:54:58');
INSERT INTO `cash_transactions` VALUES (35, 1, 1, 10.00, 'sale', 13, 'Sale INV-20260728-8828', '2026-07-28 14:56:25');
INSERT INTO `cash_transactions` VALUES (36, 1, 1, 10.00, 'sale', 14, 'Sale INV-20260728-5226', '2026-07-28 14:56:44');
INSERT INTO `cash_transactions` VALUES (37, 1, 1, 10.00, 'sale', 15, 'Sale INV-20260728-4962', '2026-07-28 14:56:58');
INSERT INTO `cash_transactions` VALUES (38, 1, 1, 10.00, 'sale', 16, 'Sale INV-20260728-8764', '2026-07-28 14:57:23');
INSERT INTO `cash_transactions` VALUES (39, 1, 1, 10.00, 'sale', 17, 'Sale INV-20260728-6953', '2026-07-28 15:02:12');
INSERT INTO `cash_transactions` VALUES (40, 1, 1, 10.00, 'sale', 18, 'Sale INV-20260728-8426', '2026-07-28 15:02:29');
INSERT INTO `cash_transactions` VALUES (41, 1, 1, 10.00, 'sale', 19, 'Sale INV-20260728-6576', '2026-07-28 15:03:42');
INSERT INTO `cash_transactions` VALUES (42, 1, 1, 10.00, 'sale', 20, 'Sale INV-20260728-2575', '2026-07-28 15:04:12');
INSERT INTO `cash_transactions` VALUES (43, 1, 1, 10.00, 'sale', 21, 'Sale INV-20260728-8335', '2026-07-28 15:04:39');
INSERT INTO `cash_transactions` VALUES (44, 1, 1, 10.00, 'sale', 22, 'Sale INV-20260728-7588', '2026-07-28 15:13:51');
INSERT INTO `cash_transactions` VALUES (45, 1, 1, 10.00, 'sale', 23, 'Sale INV-20260728-3025', '2026-07-28 15:18:37');
INSERT INTO `cash_transactions` VALUES (46, 1, 1, 10.00, 'sale', 24, 'Sale INV-20260728-4929', '2026-07-28 15:23:01');
INSERT INTO `cash_transactions` VALUES (47, 1, 1, 10.00, 'sale', 25, 'Sale INV-20260728-5051', '2026-07-28 15:23:36');
INSERT INTO `cash_transactions` VALUES (48, 1, 1, 10.00, 'sale', 26, 'Sale INV-20260728-1263', '2026-07-28 15:28:53');
INSERT INTO `cash_transactions` VALUES (49, 1, 1, 10.00, 'sale', 27, 'Sale INV-20260728-5518', '2026-07-28 15:35:02');
INSERT INTO `cash_transactions` VALUES (50, 1, 1, 10.00, 'sale', 28, 'Sale INV-20260728-9769', '2026-07-28 15:44:08');
INSERT INTO `cash_transactions` VALUES (51, 1, 1, 10.00, 'sale', 29, 'Sale INV-20260728-2955', '2026-07-28 15:44:23');
INSERT INTO `cash_transactions` VALUES (52, 1, 1, 10.00, 'sale', 30, 'Sale INV-20260728-7340', '2026-07-28 15:45:06');
INSERT INTO `cash_transactions` VALUES (53, 1, 1, 10.00, 'sale', 31, 'Sale INV-20260728-1235', '2026-07-28 15:45:32');
INSERT INTO `cash_transactions` VALUES (54, 1, 1, 10.00, 'sale', 32, 'Sale INV-20260728-9700', '2026-07-28 15:46:20');
INSERT INTO `cash_transactions` VALUES (55, 1, 1, 10.00, 'sale', 33, 'Sale INV-20260728-4448', '2026-07-28 15:47:29');
INSERT INTO `cash_transactions` VALUES (56, 1, 1, 10.00, 'sale', 34, 'Sale INV-20260728-2168', '2026-07-28 15:48:30');
INSERT INTO `cash_transactions` VALUES (57, 1, 1, 10.00, 'sale', 35, 'Sale INV-20260728-4252', '2026-07-28 15:48:41');
INSERT INTO `cash_transactions` VALUES (58, 1, 1, 10.00, 'sale', 36, 'Sale INV-20260728-7729', '2026-07-28 15:54:00');
INSERT INTO `cash_transactions` VALUES (59, 1, 1, 10.00, 'sale', 37, 'Sale INV-20260728-2996', '2026-07-28 15:56:27');
INSERT INTO `cash_transactions` VALUES (60, 1, 1, 10.00, 'sale', 38, 'Sale INV-20260728-8147', '2026-07-28 17:51:45');
INSERT INTO `cash_transactions` VALUES (61, 1, 1, 10.00, 'sale', 39, 'Sale INV-20260728-3861', '2026-07-28 17:53:13');
INSERT INTO `cash_transactions` VALUES (62, 1, 1, 10.00, 'sale', 40, 'Sale INV-20260728-7344', '2026-07-28 18:04:01');
INSERT INTO `cash_transactions` VALUES (63, 1, 1, 10.00, 'sale', 41, 'Sale INV-20260728-2460', '2026-07-28 18:07:43');
INSERT INTO `cash_transactions` VALUES (64, 1, 1, 10.00, 'sale', 42, 'Sale INV-20260728-6713', '2026-07-28 18:08:15');
INSERT INTO `cash_transactions` VALUES (65, 1, 1, 10.00, 'sale', 43, 'Sale INV-20260728-5955', '2026-07-28 18:08:41');
INSERT INTO `cash_transactions` VALUES (66, 1, 1, 10.00, 'sale', 44, 'Sale INV-20260728-7011', '2026-07-28 18:15:38');
INSERT INTO `cash_transactions` VALUES (67, 1, 1, 10.00, 'sale', 45, 'Sale INV-20260728-3504', '2026-07-28 18:16:16');
INSERT INTO `cash_transactions` VALUES (68, 1, 1, 10.00, 'sale', 46, 'Sale INV-20260728-2776', '2026-07-28 18:23:34');
INSERT INTO `cash_transactions` VALUES (69, 1, 1, 10.00, 'sale', 47, 'Sale INV-20260728-7517', '2026-07-28 18:24:21');
INSERT INTO `cash_transactions` VALUES (70, 1, 1, 10.00, 'sale', 48, 'Sale INV-20260728-5541', '2026-07-28 18:31:51');
INSERT INTO `cash_transactions` VALUES (71, 1, 1, 10.00, 'sale', 49, 'Sale INV-20260728-7530', '2026-07-28 18:35:56');
INSERT INTO `cash_transactions` VALUES (72, 1, 1, 10.00, 'sale', 50, 'Sale INV-20260728-6925', '2026-07-28 18:49:38');
INSERT INTO `cash_transactions` VALUES (73, 1, 1, 10.00, 'sale', 51, 'Sale INV-20260728-2794', '2026-07-28 18:52:00');
INSERT INTO `cash_transactions` VALUES (74, 1, 1, 10.00, 'sale', 52, 'Sale INV-20260728-9876', '2026-07-28 18:53:09');
INSERT INTO `cash_transactions` VALUES (75, 1, 1, 10.00, 'sale', 53, 'Sale INV-20260728-1874', '2026-07-28 18:54:23');
INSERT INTO `cash_transactions` VALUES (76, 1, 1, 10.00, 'sale', 54, 'Sale INV-20260728-7350', '2026-07-28 19:13:15');
INSERT INTO `cash_transactions` VALUES (77, 1, 1, 10.00, 'sale', 55, 'Sale INV-20260728-6983', '2026-07-28 19:13:36');
INSERT INTO `cash_transactions` VALUES (78, 1, 1, 330.00, 'sale', 56, 'Sale INV-20260728-7794', '2026-07-28 21:58:19');
INSERT INTO `cash_transactions` VALUES (79, 1, 1, 0.00, 'return', 3, 'Return for RET-20260728-3724', '2026-07-28 21:59:26');
INSERT INTO `cash_transactions` VALUES (80, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-28 21:59:55');
INSERT INTO `cash_transactions` VALUES (81, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-07-29 10:21:13');
INSERT INTO `cash_transactions` VALUES (82, 1, 1, 30.00, 'sale', 57, 'Sale INV-20260729-4917', '2026-07-29 10:21:43');
INSERT INTO `cash_transactions` VALUES (83, 1, 1, -10.00, 'return', 4, 'Return for RET-20260729-9208', '2026-07-29 10:23:25');
INSERT INTO `cash_transactions` VALUES (84, 1, 1, 10.00, 'sale', 58, 'Sale INV-20260806-3417', '2026-08-06 17:23:09');
INSERT INTO `cash_transactions` VALUES (85, 1, 1, 0.00, 'sale', 59, 'Sale INV-20260808-5081', '2026-08-08 02:07:55');
INSERT INTO `cash_transactions` VALUES (86, 1, 1, 30.00, 'sale', 60, 'Sale INV-20260827-9655', '2026-08-27 14:13:30');
INSERT INTO `cash_transactions` VALUES (87, 1, 1, 30.00, 'sale', 61, 'Sale INV-20260827-8626', '2026-08-27 14:14:43');
INSERT INTO `cash_transactions` VALUES (88, 1, 1, 10.00, 'sale', 62, 'Sale INV-20260828-8719', '2026-08-28 01:11:08');
INSERT INTO `cash_transactions` VALUES (89, 1, 1, 440.00, 'sale', 63, 'Sale INV-20260828-7050', '2026-08-28 01:12:03');
INSERT INTO `cash_transactions` VALUES (90, 1, 1, 0.00, 'sale', 64, 'Sale INV-20260828-6453', '2026-08-28 01:13:32');
INSERT INTO `cash_transactions` VALUES (91, 1, 1, 0.00, 'sale', 65, 'Sale INV-20260828-2770', '2026-08-28 01:13:59');
INSERT INTO `cash_transactions` VALUES (92, 1, 1, 30.00, 'sale', 66, 'Sale INV-20260828-7608', '2026-08-28 01:18:24');
INSERT INTO `cash_transactions` VALUES (93, 1, 1, 10.00, 'sale', 67, 'Sale INV-20260828-9974', '2026-08-28 01:19:05');
INSERT INTO `cash_transactions` VALUES (94, 1, 1, 10.00, 'sale', 68, 'Sale INV-20260828-9941', '2026-08-28 01:20:32');
INSERT INTO `cash_transactions` VALUES (95, 1, 1, 10.00, 'sale', 69, 'Sale INV-20260828-2682', '2026-08-28 01:27:48');
INSERT INTO `cash_transactions` VALUES (96, 1, 1, 10.00, 'sale', 70, 'Sale INV-20260828-9470', '2026-08-28 02:04:45');
INSERT INTO `cash_transactions` VALUES (97, 1, 1, 10.00, 'sale', 71, 'Sale INV-20260828-9888', '2026-08-28 02:06:12');
INSERT INTO `cash_transactions` VALUES (98, 1, 1, 10.00, 'sale', 72, 'Sale INV-20260828-5429', '2026-08-28 02:13:55');
INSERT INTO `cash_transactions` VALUES (99, 1, 1, 10.00, 'sale', 73, 'Sale INV-20260828-4729', '2026-08-28 02:14:51');
INSERT INTO `cash_transactions` VALUES (100, 1, 1, 110.00, 'sale', 74, 'Sale INV-20260828-1504', '2026-08-28 03:38:41');
INSERT INTO `cash_transactions` VALUES (101, 1, 1, 110.00, 'sale', 75, 'Sale INV-20260828-3847', '2026-08-28 13:28:03');
INSERT INTO `cash_transactions` VALUES (102, 1, 1, 110.00, 'sale', 76, 'Sale INV-20260828-5640', '2026-08-28 13:32:00');
INSERT INTO `cash_transactions` VALUES (103, 1, 1, 440.00, 'sale', 77, 'Sale INV-20260828-1883', '2026-08-28 13:32:55');
INSERT INTO `cash_transactions` VALUES (104, 1, 1, 110.00, 'sale', 78, 'Sale INV-20260828-5215', '2026-08-28 13:43:22');
INSERT INTO `cash_transactions` VALUES (105, 1, 1, 110.00, 'sale', 79, 'Sale INV-20260828-6464', '2026-08-28 13:46:20');
INSERT INTO `cash_transactions` VALUES (106, 1, 1, 440.00, 'sale', 80, 'Sale INV-20260828-7227', '2026-08-28 13:47:13');
INSERT INTO `cash_transactions` VALUES (107, 1, 1, 100.00, 'starting_cash', NULL, 'Shift started with 100', '2026-08-28 13:49:53');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `slug`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (1, 1, 'Nagham Makieh', '0937764548', 'nagham.makieh@latakia-univ.edu.sy', 'Syria/Latakia/Latakia City', '', '2026-07-28 21:57:23', '2026-07-28 21:57:23');

-- ----------------------------
-- Table structure for devices
-- ----------------------------
DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `device_code`(`device_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of devices
-- ----------------------------
INSERT INTO `devices` VALUES (1, 'Main POS', 'POS-01', 1, '2026-07-23 14:19:23');
INSERT INTO `devices` VALUES (2, 'Secondary POS', 'POS-02', 1, '2026-07-23 14:19:23');

-- ----------------------------
-- Table structure for expense_categories
-- ----------------------------
DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of expense_categories
-- ----------------------------

-- ----------------------------
-- Table structure for expenses
-- ----------------------------
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int UNSIGNED NULL DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','card','bank') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'cash',
  `receipt_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `created_by`(`created_by` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of expenses
-- ----------------------------
INSERT INTO `expenses` VALUES (1, 1, 'Maintenance', 'Desk Maintenance', 100.00, '2026-07-29', 'cash', NULL, NULL, 1, '2026-07-29 11:20:47', '2026-07-29 11:20:47');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int UNSIGNED NULL DEFAULT NULL,
  `alameen_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alameen_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `coded_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alameen_guid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `unit1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `unit1_spec` int NULL DEFAULT 1,
  `unit2` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `unit2_factor` decimal(10, 2) NULL DEFAULT 1.00,
  `barcode2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `unit3` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `unit3_factor` decimal(10, 2) NULL DEFAULT 1.00,
  `barcode3` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `price` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `price_whole` decimal(10, 2) NULL DEFAULT 0.00,
  `price_half` decimal(10, 2) NULL DEFAULT 0.00,
  `price_retail` decimal(10, 2) NULL DEFAULT 0.00,
  `price_enduser` decimal(10, 2) NULL DEFAULT 0.00,
  `price2_whole` decimal(10, 2) NULL DEFAULT 0.00,
  `price2_half` decimal(10, 2) NULL DEFAULT 0.00,
  `price2_retail` decimal(10, 2) NULL DEFAULT 0.00,
  `price2_enduser` decimal(10, 2) NULL DEFAULT 0.00,
  `price3_whole` decimal(10, 2) NULL DEFAULT 0.00,
  `price3_half` decimal(10, 2) NULL DEFAULT 0.00,
  `price3_retail` decimal(10, 2) NULL DEFAULT 0.00,
  `price3_enduser` decimal(10, 2) NULL DEFAULT 0.00,
  `cost` decimal(10, 2) NULL DEFAULT 0.00,
  `stock` int NULL DEFAULT 0,
  `min_stock` int NULL DEFAULT 5,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_id` int UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category_id`(`category_id` ASC) USING BTREE,
  INDEX `idx_barcode`(`barcode` ASC) USING BTREE,
  INDEX `idx_barcode2`(`barcode2` ASC) USING BTREE,
  INDEX `idx_barcode3`(`barcode3` ASC) USING BTREE,
  INDEX `idx_alameen_code`(`alameen_code` ASC) USING BTREE,
  INDEX `idx_coded_code`(`coded_code` ASC) USING BTREE,
  INDEX `idx_alameen_number`(`alameen_number` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 586 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 1, '1', NULL, NULL, NULL, 'ليو عرض (شاي أسود 25 ظرف +5 ظرف مجاني ) 60 غ', '6218650000019', 'Unit: عبوة', 'عبوة', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 7000.00, 0.00, 0.00, 7000.00, 7000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (2, 1, '2', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة المانغو زجاج 290مل', '6218651011014', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (3, 1, '3', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة الكوكتيل زجاج 290مل', '6218651021013', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (4, 1, '4', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة البرتقال زجاج 290مل', '6218651031012', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (5, 1, '5', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة اليتشي زجاج 290مل', '6218651041011', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (6, 1, '6', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة العنب الأبيض زجاج 290مل', '6218651051010', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (7, 1, '7', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة الكيوي زجاج 290مل', '6218651061019', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (8, 1, '8', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة الأناناس زجاج 290مل', '6218651071018', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (9, 1, '9', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة البطيخ زجاج 290مل', '6218651081017', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (10, 1, '10', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة الفريز زجاج 290مل', '6218651091016', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (11, 1, '11', NULL, NULL, NULL, 'شراب بذور الحبق  بنكهة الرمان زجاج 290مل', '6218651101012', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (12, 1, '12', NULL, NULL, NULL, 'فطر حبة كاملة زجاج 375غ', '6218651111028', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (13, 1, '13', NULL, NULL, NULL, 'فطر حبة كاملة زجاج 530غ', '6218651111073', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (14, 1, '14', NULL, NULL, NULL, 'فطر حبة كاملة زجاج 375غ*12', '6218651111301', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (15, 1, '15', NULL, NULL, NULL, 'فطر حبة كاملة  تنك  380غ', '6218651112018', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18000.00, 0.00, 0.00, 18000.00, 18000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (16, 1, '16', NULL, NULL, NULL, 'فطر حبة كاملة  تنك  380غ*12', '6218651112278', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18000.00, 0.00, 0.00, 18000.00, 18000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (17, 1, '17', NULL, NULL, NULL, 'حمص ناعم مع طحينة  تنك  380غ', '6218651122017', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (18, 1, '18', NULL, NULL, NULL, 'حمص ناعم مع طحينة  تنك  400غ', '6218651122147', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (19, 1, '19', NULL, NULL, NULL, 'فول مدمس  تنك  380غ', '6218651132016', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 6000.00, 0.00, 0.00, 6000.00, 6000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (20, 1, '20', NULL, NULL, NULL, 'فول مدمس  تنك  800غ', '6218651132023', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (21, 1, '21', NULL, NULL, NULL, 'فول مدمس  تنك  400غ', '6218651132146', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (22, 1, '22', NULL, NULL, NULL, 'بازلاء خضراء زجاج 635غ', '6218651141049', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (23, 1, '23', NULL, NULL, NULL, 'بازلاء خضراء  تنك  380غ', '6218651142015', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (24, 1, '24', NULL, NULL, NULL, 'بازلاء خضراء  تنك  800غ', '6218651142022', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (25, 1, '25', NULL, NULL, NULL, 'ذرة حلوة زجاج 1300غ', '6218651151055', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (26, 1, '26', NULL, NULL, NULL, 'ذرة حلوة زجاج 360غ', '6218651151062', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 11000.00, 0.00, 0.00, 11000.00, 11000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (27, 1, '27', NULL, NULL, NULL, 'ذرة حلوة زجاج 645غ', '6218651151369', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (28, 1, '28', NULL, NULL, NULL, 'ذرة حلوة  تنك  380غ', '6218651152014', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (29, 1, '29', NULL, NULL, NULL, 'ذرة حلوة  تنك  340غ', '6218651152199', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (30, 1, '30', NULL, NULL, NULL, 'ذرة حلوة تنك 3000غ', '6218651154018', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 46000.00, 0.00, 0.00, 46000.00, 46000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (31, 1, '31', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 300غ', '6218651161030', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (32, 1, '32', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 635غ', '6218651161047', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15500.00, 0.00, 0.00, 15500.00, 15500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (33, 1, '33', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 1300غ', '6218651161054', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 29000.00, 0.00, 0.00, 29000.00, 29000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (34, 1, '34', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 360غ', '6218651161061', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (35, 1, '35', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 3000غ', '6218651161139', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (36, 1, '36', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 635غ*12', '6218651161191', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15500.00, 0.00, 0.00, 15500.00, 15500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (37, 1, '37', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 300غ*12', '6218651161269', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (38, 1, '38', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 360غ*12', '6218651161276', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (39, 1, '39', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% زجاج 710غ', '6218651161443', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (40, 1, '40', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30%  تنك  800غ', '6218651162020', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (41, 1, '41', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30%  تنك  800غ*12', '6218651162228', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (42, 1, '42', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% تنك 3000غ', '6218651164017', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (43, 1, '43', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% تنك 4500غ', '6218651164048', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (44, 1, '44', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% تنك 4500غ*3', '6218651164055', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (45, 1, '45', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% بلاستيك 1800مل', '6218651165120', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (46, 1, '46', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% بلاستيك 2000غ', '6218651165168', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (47, 1, '47', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% بلاستيك 1000غ', '6218651165175', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 22000.00, 0.00, 0.00, 22000.00, 22000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (48, 1, '48', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% بلاستيك 3600مل', '6218651165212', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (49, 1, '49', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% بلاستيك 4000غ', '6218651165229', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 65000.00, 0.00, 0.00, 65000.00, 65000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (50, 1, '50', NULL, NULL, NULL, 'حمص حب مسلوق زجاج 360غ', '6218651171060', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (51, 1, '51', NULL, NULL, NULL, 'حمص حب مسلوق زجاج 650غ', '6218651171374', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (52, 1, '52', NULL, NULL, NULL, 'حمص حب مسلوق  تنك  380غ', '6218651172012', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (53, 1, '53', NULL, NULL, NULL, 'حمص حب مسلوق  تنك  400غ', '6218651172142', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (54, 1, '54', NULL, NULL, NULL, 'عصير مانغو مركز  تنك  800غ', '6218651182028', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (55, 1, '55', NULL, NULL, NULL, 'عصير مانغو مركز  تنك  3000غ', '6218651182035', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 55000.00, 0.00, 0.00, 55000.00, 55000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (56, 1, '56', NULL, NULL, NULL, 'قهوة سوبر سوبريم عربية سادة بدون هال سلفان 400غ', '6218651193017', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (57, 1, '57', NULL, NULL, NULL, 'قهوة سوبر سوبريم عربية سادة بدون هال سلفان 100غ', '6218651193024', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (58, 1, '58', NULL, NULL, NULL, 'قهوة سوبر سوبريم عربية مع هال اكسترا سلفان 400غ', '6218651203013', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (59, 1, '59', NULL, NULL, NULL, 'قهوة سوبر سوبريم عربية مع هال اكسترا سلفان 100غ', '6218651203020', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (60, 1, '60', NULL, NULL, NULL, 'قهوة سوبر سوبريم عربية مع هال وسط سلفان 400غ', '6218651213012', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (61, 1, '61', NULL, NULL, NULL, 'قهوة سوبر سوبريم عربية مع هال وسط سلفان 100غ', '6218651213029', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (62, 1, '62', NULL, NULL, NULL, 'بندورة مقطعة زجاج 635غ', '6218651221048', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (63, 1, '63', NULL, NULL, NULL, 'بندورة مقطعة زجاج 360غ', '6218651221062', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (64, 1, '64', NULL, NULL, NULL, 'تونا سكيب جاك قطع حلو  تنك  160غ', '6218651232044', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (65, 1, '65', NULL, NULL, NULL, 'تونا سكيب جاك قطع حلو  تنك  160غ*48', '6218651232051', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (66, 1, '66', NULL, NULL, NULL, 'تونا سكيب جاك قطع حلو  تنك  140غ', '6218651232068', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (67, 1, '67', NULL, NULL, NULL, 'تونا سكيب جاك قطع حلو  تنك  140غ*48', '6218651232075', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (68, 1, '68', NULL, NULL, NULL, 'تونا سكيب جاك قطع حار  تنك  160غ', '6218651242043', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (69, 1, '69', NULL, NULL, NULL, 'تونا سكيب جاك قطع حار  تنك  160غ*48', '6218651242050', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (70, 1, '70', NULL, NULL, NULL, 'تونا سكيب جاك قطع حار  تنك  140غ', '6218651242067', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (71, 1, '71', NULL, NULL, NULL, 'تونا سكيب جاك قطع حار  تنك  140غ*48', '6218651242074', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (72, 1, '72', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 5000مل', '6218651255012', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (73, 1, '73', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 1000مل', '6218651255029', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (74, 1, '74', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 2000مل', '6218651255036', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (75, 1, '75', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 3000مل', '6218651255081', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (76, 1, '76', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 4000مل', '6218651255098', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (77, 1, '77', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 1800مل', '6218651255128', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (78, 1, '78', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 700مل', '6218651255135', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (79, 1, '79', NULL, NULL, NULL, 'زيت دوار الشمس بلاستيك 850مل', '6218651255142', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (80, 1, '80', NULL, NULL, NULL, 'شرائح  الأناناس  تنك  570غ', '6218651262089', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (81, 1, '81', NULL, NULL, NULL, 'شرائح  الأناناس  تنك  850غ', '6218651262294', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (82, 1, '82', NULL, NULL, NULL, 'شرائح  الأناناس تنك 3050غ', '6218651264069', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 75000.00, 0.00, 0.00, 75000.00, 75000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (83, 1, '83', NULL, NULL, NULL, 'سردين حلو  تنك  125غ', '6218651272095', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (84, 1, '84', NULL, NULL, NULL, 'سردين حلو  تنك  125غ*50', '6218651272101', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (85, 1, '85', NULL, NULL, NULL, 'سردين حار  تنك  125غ', '6218651282094', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (86, 1, '86', NULL, NULL, NULL, 'سردين حار  تنك  125غ*50', '6218651282100', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (87, 1, '87', NULL, NULL, NULL, 'زيتون أخضر سلقيني زجاج 635غ', '6218651291041', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (88, 1, '88', NULL, NULL, NULL, 'زيتون أخضر سلقيني زجاج 1300غ', '6218651291058', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (89, 1, '89', NULL, NULL, NULL, 'زيتون أخضر سلقيني زجاج 2800غ', '6218651291089', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (90, 1, '90', NULL, NULL, NULL, 'زيتون أخضر سلقيني زجاج 1300غ*6', '6218651291317', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (91, 1, '91', NULL, NULL, NULL, 'زيتون أخضر سلقيني زجاج 2800غ*4', '6218651291324', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (92, 1, '92', NULL, NULL, NULL, 'زيتون أخضر سلقيني بلاستيك 8000غ', '6218651295049', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (93, 1, '93', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول زجاج 750مل', '6218651301092', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (94, 1, '94', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول زجاج 500مل', '6218651301108', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (95, 1, '95', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول زجاج 250مل', '6218651301115', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (96, 1, '96', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول زجاج 1000مل', '6218651301474', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (97, 1, '97', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 5000مل', '6218651305014', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (98, 1, '98', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 1000مل', '6218651305021', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 90000.00, 0.00, 0.00, 90000.00, 90000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (99, 1, '99', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 2000مل', '6218651305038', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (100, 1, '100', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 3000مل', '6218651305083', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (101, 1, '101', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 250مل', '6218651305182', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (102, 1, '102', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 500مل', '6218651305199', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 45000.00, 0.00, 0.00, 45000.00, 45000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (103, 1, '103', NULL, NULL, NULL, 'زيت زيتون بكر نوع أول بلاستيك 750مل', '6218651305205', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 70000.00, 0.00, 0.00, 70000.00, 70000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (104, 1, '104', NULL, NULL, NULL, 'فاصولياء خضراء زجاج 635غ', '6218651311046', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (105, 1, '105', NULL, NULL, NULL, 'فاصولياء خضراء مع صوص بندورة زجاج 635غ', '6218651321045', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (106, 1, '106', NULL, NULL, NULL, 'حلاوة طحينية فاخرة سادة بلاستيك 400غ', '6218651335059', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (107, 1, '107', NULL, NULL, NULL, 'حلاوة طحينية فاخرة سادة بلاستيك 800غ', '6218651335066', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (108, 1, '108', NULL, NULL, NULL, 'حلاوة طحينية فاخرة سادة بلاستيك 400غ*12', '6218651335233', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (109, 1, '109', NULL, NULL, NULL, 'حلاوة طحينية فاخرة سادة بلاستيك 800غ*12', '6218651335240', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (110, 1, '110', NULL, NULL, NULL, 'حلاوة طحينية فاخرة بالشوكولا بلاستيك 400غ', '6218651345058', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (111, 1, '111', NULL, NULL, NULL, 'حلاوة طحينية فاخرة بالشوكولا بلاستيك 800غ', '6218651345065', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (112, 1, '112', NULL, NULL, NULL, 'حلاوة طحينية فاخرة بالفستق بلاستيك 400غ', '6218651355057', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (113, 1, '113', NULL, NULL, NULL, 'حلاوة طحينية فاخرة بالفستق بلاستيك 800غ', '6218651355064', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (114, 1, '114', NULL, NULL, NULL, 'حلاوة طحينية فاخرة بالفستق بلاستيك 400غ*12', '6218651355231', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (115, 1, '115', NULL, NULL, NULL, 'حلاوة طحينية فاخرة بالفستق بلاستيك 800غ*12', '6218651355248', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (116, 1, '116', NULL, NULL, NULL, 'طحينة - منشأ لبناني بلاستيك 400غ', '6218651365056', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (117, 1, '117', NULL, NULL, NULL, 'طحينة - منشأ لبناني بلاستيك 800غ', '6218651365063', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (118, 1, '118', NULL, NULL, NULL, 'طحينة - منشأ لبناني بلاستيك 18000غ', '6218651365070', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (119, 1, '119', NULL, NULL, NULL, 'طحينة - منشأ لبناني بلاستيك 400غ*12', '6218651365230', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (120, 1, '120', NULL, NULL, NULL, 'طحينة - منشأ لبناني بلاستيك 800غ*12', '6218651365247', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (121, 1, '121', NULL, NULL, NULL, 'طحينة - منشأ لبناني بلاستيك 3600غ', '6218651365315', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (122, 1, '122', NULL, NULL, NULL, 'مخلل خيار زجاج 635غ', '6218651371040', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (123, 1, '123', NULL, NULL, NULL, 'مخلل خيار زجاج 1300غ', '6218651371057', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (124, 1, '124', NULL, NULL, NULL, 'مخلل خيار زجاج 2900غ', '6218651371149', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (125, 1, '125', NULL, NULL, NULL, 'مخلل خيار زجاج 635غ*12', '6218651371194', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (126, 1, '126', NULL, NULL, NULL, 'مخلل خيار زجاج 1300غ*12', '6218651371200', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (127, 1, '127', NULL, NULL, NULL, 'مخلل خيار زجاج 2900غ*4', '6218651371217', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (128, 1, '128', NULL, NULL, NULL, 'مخلل خيار بلاستيك 10000غ', '6218651375154', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (129, 1, '129', NULL, NULL, NULL, 'مخلل قتة زجاج 635غ', '6218651381049', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (130, 1, '130', NULL, NULL, NULL, 'مخلل قتة زجاج 1300غ', '6218651381056', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (131, 1, '131', NULL, NULL, NULL, 'مخلل قتة زجاج 2900غ', '6218651381148', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (132, 1, '132', NULL, NULL, NULL, 'مخلل قتة زجاج 950غ', '6218651381179', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (133, 1, '133', NULL, NULL, NULL, 'مخلل قتة زجاج 635غ*12', '6218651381193', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (134, 1, '134', NULL, NULL, NULL, 'مخلل قتة زجاج 2900غ*4', '6218651381216', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (135, 1, '135', NULL, NULL, NULL, 'مخلل قتة زجاج 950غ*12', '6218651381223', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (136, 1, '136', NULL, NULL, NULL, 'ورق عنب زجاج 635غ', '6218651391048', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (137, 1, '137', NULL, NULL, NULL, 'ورق عنب زجاج 1000غ', '6218651391123', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (138, 1, '138', NULL, NULL, NULL, 'ورق عنب زجاج 3000غ', '6218651391130', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (139, 1, '139', NULL, NULL, NULL, 'ورق عنب زجاج 2900غ', '6218651391147', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (140, 1, '140', NULL, NULL, NULL, 'ورق عنب زجاج 635غ*12', '6218651391192', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (141, 1, '141', NULL, NULL, NULL, 'ورق عنب زجاج 3000غ*4', '6218651391246', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (142, 1, '142', NULL, NULL, NULL, 'ورق عنب زجاج 1000غ*6', '6218651391253', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (143, 1, '143', NULL, NULL, NULL, 'حليب مكثف محلى  تنك  380غ', '6218651402010', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (144, 1, '144', NULL, NULL, NULL, 'حليب مكثف محلى  تنك  1000غ', '6218651402119', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (145, 1, '145', NULL, NULL, NULL, 'تونا تونغول دايت  تنك  160غ', '6218651412040', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (146, 1, '146', NULL, NULL, NULL, 'تونا تونغول دايت  تنك  160غ*48', '6218651412057', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (147, 1, '147', NULL, NULL, NULL, 'تونا تونغول دايت  تنك  185غ', '6218651412125', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (148, 1, '148', NULL, NULL, NULL, 'تونا تونغول دايت  تنك  185غ*48', '6218651412200', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (149, 1, '149', NULL, NULL, NULL, 'تونا تونغول زيت زيتون  تنك  160غ', '6218651422049', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (150, 1, '150', NULL, NULL, NULL, 'تونا تونغول زيت زيتون  تنك  160غ*48', '6218651422056', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (151, 1, '151', NULL, NULL, NULL, 'تونا تونغول زيت زيتون  تنك  185غ', '6218651422124', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (152, 1, '152', NULL, NULL, NULL, 'تونا تونغول زيت زيتون  تنك  185غ*48', '6218651422209', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (153, 1, '153', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حلو  تنك  160غ', '6218651432048', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (154, 1, '154', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حلو  تنك  160غ*48', '6218651432055', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (155, 1, '155', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حلو  تنك  185غ', '6218651432123', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (156, 1, '156', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حلو  تنك  185غ*48', '6218651432208', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (157, 1, '157', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حار  تنك  160غ', '6218651442047', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (158, 1, '158', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حار  تنك  160غ*48', '6218651442054', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (159, 1, '159', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حار  تنك  185غ', '6218651442122', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (160, 1, '160', NULL, NULL, NULL, 'تونا تونغول زيت نباتي حار  تنك  185غ*48', '6218651442207', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 17500.00, 0.00, 0.00, 17500.00, 17500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (161, 1, '161', NULL, NULL, NULL, 'مربى الفريز ممروت زجاج 800غ', '6218651451155', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (162, 1, '162', NULL, NULL, NULL, 'مربى الفريز ممروت زجاج 430غ', '6218651451162', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (163, 1, '163', NULL, NULL, NULL, 'مربى الفريز ممروت زجاج 430غ*12', '6218651451339', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (164, 1, '164', NULL, NULL, NULL, 'مربى الفريز ممروت زجاج 800غ*12', '6218651451346', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (165, 1, '165', NULL, NULL, NULL, 'مربى الفريز ممروت بلاستيك 1000غ', '6218651455177', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (166, 1, '166', NULL, NULL, NULL, 'مربى الفريز ممروت بلاستيك 420غ', '6218651455283', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (167, 1, '167', NULL, NULL, NULL, 'مربى الفريز ممروت بلاستيك 750غ', '6218651455290', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (168, 1, '168', NULL, NULL, NULL, 'مربى المشمش ممروت زجاج 800غ', '6218651461154', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (169, 1, '169', NULL, NULL, NULL, 'مربى المشمش ممروت زجاج 430غ', '6218651461161', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (170, 1, '170', NULL, NULL, NULL, 'مربى المشمش ممروت زجاج 430غ*12', '6218651461338', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (171, 1, '171', NULL, NULL, NULL, 'مربى المشمش ممروت زجاج 800غ*12', '6218651461345', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (172, 1, '172', NULL, NULL, NULL, 'مربى المشمش ممروت بلاستيك 1000غ', '6218651465176', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 27000.00, 0.00, 0.00, 27000.00, 27000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (173, 1, '173', NULL, NULL, NULL, 'مربى المشمش ممروت بلاستيك 4000غ', '6218651465220', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (174, 1, '174', NULL, NULL, NULL, 'مربى المشمش ممروت بلاستيك 420غ', '6218651465282', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (175, 1, '175', NULL, NULL, NULL, 'مربى المشمش ممروت بلاستيك 750غ', '6218651465299', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (176, 1, '176', NULL, NULL, NULL, 'مربى التين ممروت زجاج 800غ', '6218651471153', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (177, 1, '177', NULL, NULL, NULL, 'مربى التين ممروت زجاج 430غ', '6218651471160', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (178, 1, '178', NULL, NULL, NULL, 'مربى التين ممروت بلاستيك 1000غ', '6218651475175', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (179, 1, '179', NULL, NULL, NULL, 'مربى التين ممروت بلاستيك 4000غ', '6218651475229', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (180, 1, '180', NULL, NULL, NULL, 'مربى التين ممروت بلاستيك 420غ', '6218651475281', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (181, 1, '181', NULL, NULL, NULL, 'مربى التين ممروت بلاستيك 750غ', '6218651475298', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (182, 1, '182', NULL, NULL, NULL, 'فطر مقطع زجاج 375غ', '6218651481022', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (183, 1, '183', NULL, NULL, NULL, 'فطر مقطع زجاج 635غ', '6218651481046', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (184, 1, '184', NULL, NULL, NULL, 'فطر مقطع زجاج 635غ*12', '6218651481190', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (185, 1, '185', NULL, NULL, NULL, 'فطر مقطع زجاج 375غ*12', '6218651481305', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (186, 1, '186', NULL, NULL, NULL, 'فطر مقطع  تنك  380غ', '6218651482012', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18500.00, 0.00, 0.00, 18500.00, 18500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (187, 1, '187', NULL, NULL, NULL, 'فطر مقطع  تنك  800غ', '6218651482029', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 38000.00, 0.00, 0.00, 38000.00, 38000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (188, 1, '188', NULL, NULL, NULL, 'فطر مقطع  تنك  380غ*12', '6218651482272', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18500.00, 0.00, 0.00, 18500.00, 18500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (189, 1, '189', NULL, NULL, NULL, 'فطر مقطع  تنك  800غ*6', '6218651482289', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 38000.00, 0.00, 0.00, 38000.00, 38000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (190, 1, '190', NULL, NULL, NULL, 'فطر مقطع تنك 3000غ', '6218651484016', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (191, 1, '191', NULL, NULL, NULL, 'فطر مقطع بلاستيك 8000غ', '6218651485044', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (192, 1, '192', NULL, NULL, NULL, 'فطر كامل زجاج 635غ', '6218651491045', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (193, 1, '193', NULL, NULL, NULL, 'فطر كامل زجاج 635غ*12', '6218651491199', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (194, 1, '194', NULL, NULL, NULL, 'فطر كامل  تنك  380غ', '6218651492011', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18500.00, 0.00, 0.00, 18500.00, 18500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (195, 1, '195', NULL, NULL, NULL, 'فطر كامل  تنك  800غ', '6218651492028', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 38000.00, 0.00, 0.00, 38000.00, 38000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (196, 1, '196', NULL, NULL, NULL, 'فطر كامل  تنك  800غ*6', '6218651492288', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 38000.00, 0.00, 0.00, 38000.00, 38000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (197, 1, '197', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض بزيت نباتي حار  تنك  120غ', '6218651502130', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (198, 1, '198', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض بزيت نباتي حار  تنك  120غ*24', '6218651502215', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (199, 1, '199', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض بزيت نباتي حلو  تنك  120غ', '6218651512139', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (200, 1, '200', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض بزيت نباتي حلو  تنك  120غ*24', '6218651512214', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (201, 1, '201', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض بالليمون  تنك  120غ', '6218651522138', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (202, 1, '202', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض بالليمون  تنك  120غ*24', '6218651522213', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (203, 1, '203', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض مدخن  تنك  120غ', '6218651532137', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (204, 1, '204', NULL, NULL, NULL, 'تونا فيليه تونغول أبيض مدخن  تنك  120غ*24', '6218651532212', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (205, 1, '205', NULL, NULL, NULL, 'دبس الرمان زجاج 500مل', '6218651541108', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (206, 1, '206', NULL, NULL, NULL, 'دبس الرمان زجاج 500مل*12', '6218651541290', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (207, 1, '207', NULL, NULL, NULL, 'دبس الرمان بلاستيك 525غ', '6218651545106', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 27000.00, 0.00, 0.00, 27000.00, 27000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (208, 1, '208', NULL, NULL, NULL, 'دبس الرمان بلاستيك 710غ', '6218651545113', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 33000.00, 0.00, 0.00, 33000.00, 33000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (209, 1, '209', NULL, NULL, NULL, 'دبس الرمان بلاستيك 250مل', '6218651545182', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (210, 1, '210', NULL, NULL, NULL, 'دبس الرمان بلاستيك 525غ*12', '6218651545250', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 27000.00, 0.00, 0.00, 27000.00, 27000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (211, 1, '211', NULL, NULL, NULL, 'دبس الرمان بلاستيك 710غ*12', '6218651545267', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 33000.00, 0.00, 0.00, 33000.00, 33000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (212, 1, '212', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حلو  تنك  160غ', '6218651552043', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (213, 1, '213', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حلو  تنك  160غ*48', '6218651552050', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (214, 1, '214', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حلو  تنك  140غ', '6218651552067', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (215, 1, '215', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حار  تنك  160غ', '6218651562042', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (216, 1, '216', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حار  تنك  160غ*48', '6218651562059', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (217, 1, '217', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حار  تنك  140غ', '6218651562066', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (218, 1, '218', NULL, NULL, NULL, 'مخلل لفت شرائح زجاج 635غ', '6218651571044', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (219, 1, '219', NULL, NULL, NULL, 'مخلل لفت شرائح زجاج 3000غ', '6218651571136', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (220, 1, '220', NULL, NULL, NULL, 'مخلل لفت شرائح زجاج 635غ*12', '6218651571198', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (221, 1, '221', NULL, NULL, NULL, 'مخلل لفت شرائح زجاج 3000غ*4', '6218651571242', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (222, 1, '222', NULL, NULL, NULL, 'مخلل لفت أحمر كامل زجاج 3000غ', '6218651581135', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (223, 1, '223', NULL, NULL, NULL, 'مخلل مشكل بلاستيك 10000غ', '6218651595156', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (224, 1, '224', NULL, NULL, NULL, 'مخلل خيار مقطع بلاستيك 10000غ', '6218651605152', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (225, 1, '225', NULL, NULL, NULL, 'باذنجان مشوي زجاج 635غ', '6218651611047', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (226, 1, '226', NULL, NULL, NULL, 'مخلل فليفلة يونانية زجاج 1000غ', '6218651621121', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (227, 1, '227', NULL, NULL, NULL, 'فول مدمس مع كمون  تنك  380غ', '6218651632011', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (228, 1, '228', NULL, NULL, NULL, 'فول مدمس مع كمون  تنك  800غ', '6218651632028', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (229, 1, '229', NULL, NULL, NULL, 'فول مدمس مع كمون  تنك  400غ', '6218651632141', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (230, 1, '230', NULL, NULL, NULL, 'فول مدمس مع حار  تنك  380غ', '6218651642010', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (231, 1, '231', NULL, NULL, NULL, 'فول مدمس مع حار  تنك  800غ', '6218651642027', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (232, 1, '232', NULL, NULL, NULL, 'فول مدمس مع حار  تنك  400غ', '6218651642140', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (233, 1, '233', NULL, NULL, NULL, 'فول مدمس مع حمص حب  تنك  400غ', '6218651652149', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (234, 1, '234', NULL, NULL, NULL, 'فول مدمس عريض (باجيلا )  تنك  400غ', '6218651662148', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (235, 1, '235', NULL, NULL, NULL, 'دبس فليفلة حمرا حارة مطحونة زجاج 360غ', '6218651671065', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (236, 1, '236', NULL, NULL, NULL, 'دبس فليفلة حمرا حارة مطحونة زجاج 360غ*12', '6218651671270', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (237, 1, '237', NULL, NULL, NULL, 'شراب بذور الحبق فاكهة الماراكويا (passion fruit ) زجاج 290مل', '6218651681019', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (238, 1, '238', NULL, NULL, NULL, 'شراب بذور الحبق بنكهة توت الأزرق (blue berry ) زجاج 290مل', '6218651691018', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (239, 1, '239', NULL, NULL, NULL, 'شراب بذور الحبق بنكهة العنب الاحمر زجاج 290مل', '6218651701014', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (240, 1, '240', NULL, NULL, NULL, 'أرضي شوكي زجاج 1300غ', '6218651711051', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 49000.00, 0.00, 0.00, 49000.00, 49000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (241, 1, '241', NULL, NULL, NULL, 'سمن نباتي  تنك  10000غ', '6218651722156', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (242, 1, '242', NULL, NULL, NULL, 'سمن نباتي  تنك  15000غ', '6218651722163', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (243, 1, '243', NULL, NULL, NULL, 'سمن نباتي  تنك  16000مل', '6218651722170', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (244, 1, '244', NULL, NULL, NULL, 'سمن نباتي  تنك  2000مل', '6218651722187', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (245, 1, '245', NULL, NULL, NULL, 'سمن نباتي تنك 2000غ', '6218651724020', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (246, 1, '246', NULL, NULL, NULL, 'سمن نباتي تنك 1000غ', '6218651724037', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (247, 1, '247', NULL, NULL, NULL, 'سمن نباتي بلاستيك 2000غ', '6218651725164', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (248, 1, '248', NULL, NULL, NULL, 'سمن نباتي بلاستيك 1000غ', '6218651725171', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (249, 1, '249', NULL, NULL, NULL, 'صلصة فليفلة حارة زجاج 635غ', '6218651731042', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (250, 1, '250', NULL, NULL, NULL, 'صلصة فليفلة حارة زجاج 365غ', '6218651731189', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (251, 1, '251', NULL, NULL, NULL, 'صلصة فليفلة حارة زجاج 635غ*12', '6218651731196', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (252, 1, '252', NULL, NULL, NULL, 'ملوخية بلدية كرتون 200غ', '6218651746015', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (253, 1, '253', NULL, NULL, NULL, 'مرتديلا لحم دجاج سادة  تنك  380غ', '6218651752016', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (254, 1, '254', NULL, NULL, NULL, 'مرتديلا لحم دجاج سادة  تنك  800غ', '6218651752023', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (255, 1, '255', NULL, NULL, NULL, 'مرتديلا لحم دجاج حارة  تنك  380غ', '6218651762015', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (256, 1, '256', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حلو بزيت دوار الشمس  تنك  160غ', '6218651772045', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (257, 1, '257', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حلو بزيت دوار الشمس  تنك  185غ', '6218651772120', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (258, 1, '258', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حلو بزيت دوار الشمس  تنك  80غ', '6218651772304', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (259, 1, '259', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حار بزيت دوار الشمس  تنك  160غ', '6218651782044', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (260, 1, '260', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حار بزيت دوار الشمس  تنك  185غ', '6218651782129', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (261, 1, '261', NULL, NULL, NULL, 'تونا سكيب جاك رقائق حار بزيت دوار الشمس  تنك  80غ', '6218651782303', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (262, 1, '262', NULL, NULL, NULL, 'سردين حار سمك أصفر  تنك  125غ', '6218651792098', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (263, 1, '263', NULL, NULL, NULL, 'سردين حلو سمك أصفر  تنك  125غ', '6218651802094', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (264, 1, '264', NULL, NULL, NULL, 'تونا سكيب جاك قطع بالزيت النباتي حلو  تنك  140غ', '6218651812062', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (265, 1, '265', NULL, NULL, NULL, 'تونا سكيب جاك قطع بالزيت النباتي حلو  تنك  185غ', '6218651812123', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (266, 1, '266', NULL, NULL, NULL, 'تونا سكيب جاك قطع بالزيت النباتي حلو  تنك  400غ', '6218651812147', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 30, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (267, 1, '267', NULL, NULL, NULL, 'تونا سكيب جاك قطع بالزيت النباتي حار  تنك  140غ', '6218651822061', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (268, 1, '268', NULL, NULL, NULL, 'تونا سكيب جاك قطع بالزيت النباتي حار  تنك  185غ', '6218651822122', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (269, 1, '269', NULL, NULL, NULL, 'تونا سكيب جاك قطع بالزيت النباتي حار  تنك  400غ', '6218651822146', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (270, 1, '270', NULL, NULL, NULL, 'تونا سكيب جاك قطع دايت  تنك  185غ', '6218651832121', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (271, 1, '271', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت الزيتون  تنك  160غ', '6218651842045', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (272, 1, '272', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت الزيتون  تنك  160غ*48', '6218651842052', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (273, 1, '273', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت الزيتون  تنك  185غ', '6218651842120', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (274, 1, '274', NULL, NULL, NULL, 'تونا سكيب جاك رقائق بالزيت النباتي حلو  تنك  185غ', '6218651852129', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (275, 1, '275', NULL, NULL, NULL, 'تونا سكيب جاك رقائق بالزيت النباتي حار  تنك  185غ', '6218651862128', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (276, 1, '276', NULL, NULL, NULL, 'تونا سكيب جاك رقائق دايت  تنك  185غ', '6218651872127', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (277, 1, '277', NULL, NULL, NULL, 'تونا سكيب جاك رقائق بزيت زيتون  تنك  185غ', '6218651882126', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 19000.00, 0.00, 0.00, 19000.00, 19000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (278, 1, '278', NULL, NULL, NULL, 'مخلل لفت مع شوندر زجاج 1000غ', '6218651891128', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (279, 1, '279', NULL, NULL, NULL, 'مخلل لفت مع شوندر زجاج 1000غ*12', '6218651891234', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (280, 1, '280', NULL, NULL, NULL, 'قمر الدين كرتون 400غ', '6218651906020', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (281, 1, '281', NULL, NULL, NULL, 'قمر الدين نايلون 400غ', '6218651907010', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (282, 1, '282', NULL, NULL, NULL, 'قمر الدين نايلون 400غ*20', '6218651907027', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (283, 1, '283', NULL, NULL, NULL, 'مربى التوت ممروت زجاج 360غ', '6218651911062', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (284, 1, '284', NULL, NULL, NULL, 'مربى التوت ممروت زجاج 360غ*12', '6218651911277', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (285, 1, '285', NULL, NULL, NULL, 'مربى التوت ممروت بلاستيك 1000غ', '6218651915176', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (286, 1, '286', NULL, NULL, NULL, 'مربى التوت ممروت بلاستيك 420غ', '6218651915282', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (287, 1, '287', NULL, NULL, NULL, 'مربى التوت ممروت بلاستيك 750غ', '6218651915299', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (288, 1, '288', NULL, NULL, NULL, 'رب البندورة كثافة  28 - 30% - عرض خاص  تنك  800غ', '6218651922020', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (289, 1, '289', NULL, NULL, NULL, 'حمص ناعم مع زعتر  تنك  400غ', '6218651932142', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (290, 1, '290', NULL, NULL, NULL, 'كمأة سمراء زجاج 635غ', '6218651941045', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 140000.00, 0.00, 0.00, 140000.00, 140000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (291, 1, '291', NULL, NULL, NULL, 'كمأة سمراء  تنك  800غ', '6218651942028', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 150000.00, 0.00, 0.00, 150000.00, 150000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (292, 1, '292', NULL, NULL, NULL, 'مخلل فليفلة زجاج 635غ', '6218651951044', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (293, 1, '293', NULL, NULL, NULL, 'مخلل فليفلة زجاج 3000غ', '6218651951136', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (294, 1, '294', NULL, NULL, NULL, 'تونا تونغول قطع فيتنامي حلو بزيت دوار الشمس  تنك  160غ', '6218651962040', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (295, 1, '295', NULL, NULL, NULL, 'تونا تونغول قطع فيتنامي حلو بزيت دوار الشمس  تنك  160غ*48', '6218651962057', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (296, 1, '296', NULL, NULL, NULL, 'تونا تونغول قطع فيتنامي حار بزيت دوار الشمس  تنك  160غ', '6218651972049', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (297, 1, '297', NULL, NULL, NULL, 'تونا تونغول قطع فيتنامي حار بزيت دوار الشمس  تنك  160غ*48', '6218651972056', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (298, 1, '298', NULL, NULL, NULL, 'حمص ناعم مع طحينة اكسترا  تنك  400غ', '6218651982147', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (299, 1, '299', NULL, NULL, NULL, 'زيت ذرة نقي بلاستيك 1800مل', '6218651995123', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (300, 1, '300', NULL, NULL, NULL, 'فول مدمس  تنك  380غ', '6218652012010', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 6000.00, 0.00, 0.00, 6000.00, 6000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (301, 1, '301', NULL, NULL, NULL, 'حمص ناعم مع طحينة  تنك  380غ', '6218652022019', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (302, 1, '302', NULL, NULL, NULL, 'ذرة حلوة  تنك  380غ', '6218652032018', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (303, 1, '303', NULL, NULL, NULL, 'تونا ناعم حار  تنك  160غ', '6218652042048', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (304, 1, '304', NULL, NULL, NULL, 'تونا ناعم حار  تنك  160غ*48', '6218652042055', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (305, 1, '305', NULL, NULL, NULL, 'تونا ناعم حار  تنك  140غ', '6218652042062', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (306, 1, '306', NULL, NULL, NULL, 'تونا ناعم حلو  تنك  160غ', '6218652052047', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (307, 1, '307', NULL, NULL, NULL, 'تونا ناعم حلو  تنك  160غ*48', '6218652052054', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (308, 1, '308', NULL, NULL, NULL, 'تونا ناعم حلو  تنك  140غ', '6218652052061', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (309, 1, '309', NULL, NULL, NULL, 'تونا رقائق بالزيت النباتي الحلو  تنك  140غ', '6218652062060', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (310, 1, '310', NULL, NULL, NULL, 'تونا رقائق بالزيت النباتي الحار  تنك  140غ', '6218652072069', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (311, 1, '311', NULL, NULL, NULL, 'تونا قطع بالزيت النباتي  تنك  140غ', '6218652082068', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (312, 1, '312', NULL, NULL, NULL, 'تونا قطع بالزيت النباتي الحار  تنك  140غ', '6218652092067', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (313, 1, '313', NULL, NULL, NULL, 'حمص حب مسلوق  تنك  380غ', '6218652102018', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (314, 1, '314', NULL, NULL, NULL, 'سردين بالزيت النباتي حلو  تنك  125غ', '6218652112093', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (315, 1, '315', NULL, NULL, NULL, 'سردين بالزيت النباتي حار  تنك  125غ', '6218652122092', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (316, 1, '316', NULL, NULL, NULL, 'فليفلة حلوة مطحونة زجاج 635غ', '6218653011043', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (317, 1, '317', NULL, NULL, NULL, 'فليفلة حلوة مطحونة زجاج 360غ', '6218653011067', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (318, 1, '318', NULL, NULL, NULL, 'فليفلة حلوة مطحونة زجاج 635غ*12', '6218653011197', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (319, 1, '319', NULL, NULL, NULL, 'معجون الفليفلة الحلو زجاج 365غ', '6218653021189', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (320, 1, '320', NULL, NULL, NULL, 'معجون الفليفلة الحلو زجاج 365غ*12', '6218653021288', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (321, 1, '321', NULL, NULL, NULL, 'معجون الفليفلة الحار زجاج 365غ', '6218653031188', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 15000.00, 0.00, 0.00, 15000.00, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (322, 1, '322', NULL, NULL, NULL, 'طحينية - منشأ تركي بلاستيك 400غ', '6218653045055', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (323, 1, '323', NULL, NULL, NULL, 'طحينية - منشأ تركي بلاستيك 800غ', '6218653045062', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (324, 1, '324', NULL, NULL, NULL, 'طحينية - منشأ تركي بلاستيك 4000غ', '6218653045222', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (325, 1, '325', NULL, NULL, NULL, 'زيتون أخضر سلقيني جامبو زجاج 635غ', '6218653051049', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (326, 1, '326', NULL, NULL, NULL, 'زيتون أخضر سلقيني جامبو زجاج 1300غ', '6218653051056', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (327, 1, '327', NULL, NULL, NULL, 'زيتون أخضر سلقيني جامبو زجاج 1300غ*6', '6218653051315', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (328, 1, '328', NULL, NULL, NULL, 'مخللات مشكلة - منشأ لبناني زجاج 635غ', '6218653061048', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (329, 1, '329', NULL, NULL, NULL, 'مخللات مشكلة - منشأ لبناني زجاج 1000غ', '6218653061123', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (330, 1, '330', NULL, NULL, NULL, 'مخللات مشكلة - منشأ لبناني زجاج 3000غ', '6218653061130', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (331, 1, '331', NULL, NULL, NULL, 'مخللات مشكلة - منشأ لبناني زجاج 635غ*12', '6218653061192', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (332, 1, '332', NULL, NULL, NULL, 'مخللات مشكلة - منشأ لبناني زجاج 1000غ*12', '6218653061239', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (333, 1, '333', NULL, NULL, NULL, 'مخللات مشكلة - منشأ لبناني زجاج 3000غ*4', '6218653061246', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (334, 1, '334', NULL, NULL, NULL, 'فول مدمس - منشأ أردني  تنك  800غ', '6218653072020', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (335, 1, '335', NULL, NULL, NULL, 'فول مدمس - منشأ أردني  تنك  400غ', '6218653072143', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (336, 1, '336', NULL, NULL, NULL, 'فول مدمس - منشأ أردني  تنك  400غ*24', '6218653072242', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (337, 1, '337', NULL, NULL, NULL, 'فول مدمس باجيلا - منشأ أردني  تنك  400غ', '6218653082142', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (338, 1, '338', NULL, NULL, NULL, 'فول مدمس باجيلا - منشأ أردني  تنك  400غ*24', '6218653082241', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (339, 1, '339', NULL, NULL, NULL, 'فول مدمس مصري - منشأ أردني  تنك  400غ', '6218653092141', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (340, 1, '340', NULL, NULL, NULL, 'فول مدمس مصري - منشأ أردني  تنك  400غ*24', '6218653092240', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (341, 1, '341', NULL, NULL, NULL, 'فول مدمس لبناني - منشأ أردني  تنك  400غ', '6218653102147', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (342, 1, '342', NULL, NULL, NULL, 'فول مدمس لبناني - منشأ أردني  تنك  400غ*24', '6218653102246', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (343, 1, '343', NULL, NULL, NULL, 'فول مدمس فلسطيني - منشأ أردني  تنك  400غ', '6218653112146', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (344, 1, '344', NULL, NULL, NULL, 'فول مدمس فلسطيني - منشأ أردني  تنك  400غ*24', '6218653112245', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (345, 1, '345', NULL, NULL, NULL, 'بازلاء - منشأ أردني  تنك  400غ', '6218653122145', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (346, 1, '346', NULL, NULL, NULL, 'بازلاء - منشأ أردني  تنك  400غ*24', '6218653122244', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (347, 1, '347', NULL, NULL, NULL, 'بازلاء مع جزر - منشأ أردني  تنك  400غ', '6218653132144', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (348, 1, '348', NULL, NULL, NULL, 'بازلاء مع جزر - منشأ أردني  تنك  400غ*24', '6218653132243', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (349, 1, '349', NULL, NULL, NULL, 'فاصولياء بيضاء - منشأ أردني  تنك  400غ', '6218653142143', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (350, 1, '350', NULL, NULL, NULL, 'فاصولياء بيضاء - منشأ أردني  تنك  400غ*24', '6218653142242', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (351, 1, '351', NULL, NULL, NULL, 'فاصولياء ييضاء مع صوص بندورة - منشأ أردني  تنك  400غ', '6218653152142', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (352, 1, '352', NULL, NULL, NULL, 'فاصولياء ييضاء مع صوص بندورة - منشأ أردني  تنك  400غ*24', '6218653152241', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (353, 1, '353', NULL, NULL, NULL, 'فاصولياء حمراء - منشأ أردني  تنك  400غ', '6218653162141', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (354, 1, '354', NULL, NULL, NULL, 'فاصولياء حمراء - منشأ أردني  تنك  400غ*24', '6218653162240', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (355, 1, '355', NULL, NULL, NULL, 'حمص حب - منشأ أردني  تنك  400غ', '6218653172140', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (356, 1, '356', NULL, NULL, NULL, 'حمص حب - منشأ أردني  تنك  400غ*24', '6218653172249', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (357, 1, '357', NULL, NULL, NULL, 'حمص ناعم مع طحينة - منشأ أردني  تنك  380غ', '6218653182019', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (358, 1, '358', NULL, NULL, NULL, 'حمص ناعم مع طحينة - منشأ أردني  تنك  220غ', '6218653182231', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (359, 1, '359', NULL, NULL, NULL, 'حمص ناعم مع طحينة - منشأ أردني  تنك  220غ*24', '6218653182255', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (360, 1, '360', NULL, NULL, NULL, 'حمص ناعم مع طحينة - منشأ أردني  تنك  380غ*24', '6218653182262', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (361, 1, '361', NULL, NULL, NULL, 'فليفلة حارة مطحونة بلاستيك 2000غ', '6218653195163', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (362, 1, '362', NULL, NULL, NULL, 'فليفلة حارة مطحونة بلاستيك 4000غ', '6218653195224', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (363, 1, '363', NULL, NULL, NULL, 'حلاوة بالشوكولا اكسترا - منشأ تركي بلاستيك 5000غ', '6218653205275', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (364, 1, '364', NULL, NULL, NULL, 'حلاوة بالفستق اكسترا - منشأ تركي بلاستيك 5000غ', '6218653215274', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (365, 1, '365', NULL, NULL, NULL, 'حلاوة سادة اكسترا - منشأ تركي بلاستيك 5000غ', '6218653225273', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (366, 1, '366', NULL, NULL, NULL, 'عصير بذور الريحان طعم الكوكتيل زجاج 290مل', '6218653231014', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (367, 1, '367', NULL, NULL, NULL, 'عصير بذور الريحان طعم الكوكتيل زجاج 290مل*24', '6218653231359', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (368, 1, '368', NULL, NULL, NULL, 'عصير بذور الريحان طعم الرمان زجاج 290مل', '6218653241013', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (369, 1, '369', NULL, NULL, NULL, 'عصير بذور الريحان طعم الرمان زجاج 290مل*24', '6218653241358', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (370, 1, '370', NULL, NULL, NULL, 'عصير بذور الريحان طعم الفريز زجاج 290مل', '6218653251012', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (371, 1, '371', NULL, NULL, NULL, 'عصير بذور الريحان طعم الفريز زجاج 290مل*24', '6218653251357', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (372, 1, '372', NULL, NULL, NULL, 'عصير بذور الريحان طعم الجبس زجاج 290مل', '6218653261011', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (373, 1, '373', NULL, NULL, NULL, 'عصير بذور الريحان طعم الجبس زجاج 290مل*24', '6218653261356', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (374, 1, '374', NULL, NULL, NULL, 'مكدوس زجاج 635غ', '6218653271041', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (375, 1, '375', NULL, NULL, NULL, 'ذرة حلوة - منشأ أردني  تنك  800غ', '6218653282023', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (376, 1, '376', NULL, NULL, NULL, 'حمص حب مسلوق - منشأ أردني  تنك  800غ', '6218653292022', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (377, 1, '377', NULL, NULL, NULL, 'بازلاء مطبوخة - منشأ أردني  تنك  800غ', '6218653302028', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (378, 1, '378', NULL, NULL, NULL, 'فاصولياء بيضاء مطبوخة - منشأ أردني  تنك  800غ', '6218653312027', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (379, 1, '379', NULL, NULL, NULL, 'فول باجيلا - منشأ أردني  تنك  800غ', '6218653322026', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (380, 1, '380', NULL, NULL, NULL, 'عصير بذور الريحان طعم الكيوي زجاج 290مل', '6218653331011', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (381, 1, '381', NULL, NULL, NULL, 'عصير بذور الريحان طعم الكيوي زجاج 290مل*24', '6218653331356', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (382, 1, '382', NULL, NULL, NULL, 'تونا تونغول قطع بالفلفل والليمون  تنك  160غ', '6218653342048', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18000.00, 0.00, 0.00, 18000.00, 18000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (383, 1, '383', NULL, NULL, NULL, 'تونا تونغول قطع بالفلفل والليمون  تنك  185غ', '6218653342123', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18000.00, 0.00, 0.00, 18000.00, 18000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (384, 1, '384', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحلو  تنك  160غ', '6218653352047', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (385, 1, '385', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحلو  تنك  140غ', '6218653352061', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (386, 1, '386', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحلو  تنك  185غ', '6218653352122', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (387, 1, '387', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحلو  تنك  80غ', '6218653352306', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (388, 1, '388', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحار  تنك  160غ', '6218653362046', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (389, 1, '389', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحار  تنك  140غ', '6218653362060', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (390, 1, '390', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحار  تنك  185غ', '6218653362121', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (391, 1, '391', NULL, NULL, NULL, 'تونا سكيب جاك قطع بزيت دوار الشمس الحار  تنك  80غ', '6218653362305', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (392, 1, '392', NULL, NULL, NULL, 'تونا ناعم بزيت دوار الشمس حلو  تنك  160غ', '6218653372045', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (393, 1, '393', NULL, NULL, NULL, 'تونا ناعم بزيت دوار الشمس حلو  تنك  185غ', '6218653372120', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (394, 1, '394', NULL, NULL, NULL, 'تونا ناعم بزيت دوار الشمس حلو  تنك  80غ', '6218653372304', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (395, 1, '395', NULL, NULL, NULL, 'تونا ناعم بزيت دوار الشمس حار  تنك  160غ', '6218653382044', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (396, 1, '396', NULL, NULL, NULL, 'تونا ناعم بزيت دوار الشمس حار  تنك  185غ', '6218653382129', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (397, 1, '397', NULL, NULL, NULL, 'تونا ناعم بزيت دوار الشمس حار  تنك  80غ', '6218653382303', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (398, 1, '398', NULL, NULL, NULL, 'تونا تونغول قطع مدخن  تنك  185غ', '6218653392128', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18000.00, 0.00, 0.00, 18000.00, 18000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (399, 1, '399', NULL, NULL, NULL, 'عرض خاص (تونا حلو) - تغليف شرينك  تنك  140غ*3', '6218653402315', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (400, 1, '400', NULL, NULL, NULL, 'عرض خاص (تونا حلو) - تغليف شرينك  تنك  160غ*3', '6218653402322', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (401, 1, '401', NULL, NULL, NULL, 'عرض خاص (تونا حار) - تغليف شرينك  تنك  140غ*3', '6218653412314', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (402, 1, '402', NULL, NULL, NULL, 'عرض خاص (تونا حار) - تغليف شرينك  تنك  160غ*3', '6218653412321', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (403, 1, '403', NULL, NULL, NULL, 'مربى الكرز ممروت زجاج 800غ', '6218653421156', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 24000.00, 0.00, 0.00, 24000.00, 24000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (404, 1, '404', NULL, NULL, NULL, 'مربى الكرز ممروت زجاج 430غ', '6218653421163', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (405, 1, '405', NULL, NULL, NULL, 'مربى الكرز ممروت بلاستيك 1000غ', '6218653425178', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (406, 1, '406', NULL, NULL, NULL, 'مربى الكرز ممروت بلاستيك 4000غ', '6218653425222', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (407, 1, '407', NULL, NULL, NULL, 'مربى الكرز ممروت بلاستيك 420غ', '6218653425284', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (408, 1, '408', NULL, NULL, NULL, 'مربى الكرز ممروت بلاستيك 750غ', '6218653425291', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (409, 1, '409', NULL, NULL, NULL, 'عرض خاص (تونا سكيب جاك قطع حلو)  تنك  140غ*16', '6218653432336', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (410, 1, '410', NULL, NULL, NULL, 'عرض خاص (تونا سكيب جاك قطع حار)  تنك  140غ*16', '6218653442335', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (411, 1, '411', NULL, NULL, NULL, 'خل أبيض بلاستيك 1000مل', '6218653455021', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 9000.00, 0.00, 0.00, 9000.00, 9000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (412, 1, '412', NULL, NULL, NULL, 'لانشون دجاج  تنك  340غ', '6218653462197', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 14000.00, 0.00, 0.00, 14000.00, 14000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (413, 1, '413', NULL, NULL, NULL, 'لانشون دجاج  تنك  200غ', '6218653462340', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (414, 1, '414', NULL, NULL, NULL, 'رب البندورة كثافة 22-24%  تنك  800غ', '6218653472028', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (415, 1, '415', NULL, NULL, NULL, 'تمر سعودي فاخر خضري بلاستيك 800غ', '6218653485066', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (416, 1, '416', NULL, NULL, NULL, 'تمر سعودي فاخر خضري كرتون 5000غ', '6218653486032', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (417, 1, '417', NULL, NULL, NULL, 'تمر سعودي فاخر خضري كرتون 3000غ', '6218653486056', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (418, 1, '418', NULL, NULL, NULL, 'تمر سعودي فاخر سري كرتون 5000غ', '6218653496031', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (419, 1, '419', NULL, NULL, NULL, 'تمر سعودي فاخر سري كرتون 3000غ', '6218653496055', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (420, 1, '420', NULL, NULL, NULL, 'تمر سعودي فاخر سري فاكيوم 900غ', '6218653498011', 'Unit: فاكيوم', 'فاكيوم', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (421, 1, '421', NULL, NULL, NULL, 'تمر رطب كرتون 5000غ', '6218653506037', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (422, 1, '422', NULL, NULL, NULL, 'تمر رطب كرتون 2000غ', '6218653506044', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (423, 1, '423', NULL, NULL, NULL, 'عرض خاص (مربى الكرز ممروت 430غ 2عبوة+1عبوة مجاناً) - تغليف شرينك زجاج 430غ*3', '6218653511383', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (424, 1, '424', NULL, NULL, NULL, 'عرض خاص (مربى المشمش ممروت 430غ 2عبوة+1عبوة مجاناً) - تغليف شرينك زجاج 430غ*3', '6218653521382', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (425, 1, '425', NULL, NULL, NULL, 'عرض خاص (مربى التين ممروت 430غ 2عبوة+1عبوة مجاناً) - تغليف شرينك زجاج 430غ*3', '6218653531381', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (426, 1, '426', NULL, NULL, NULL, 'عرض خاص (مربى الكرز ممروت) - تغليف شرينك زجاج 800غ*2', '6218653541397', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (427, 1, '427', NULL, NULL, NULL, 'عرض خاص (مربى التين ممروت) - تغليف شرينك زجاج 800غ*2', '6218653551396', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (428, 1, '428', NULL, NULL, NULL, 'عرض خاص (ذرة حلوة 360غ 2عبوة+1عبوة مجاناً) - تغليف شرينك زجاج 360غ*3', '6218653561401', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (429, 1, '429', NULL, NULL, NULL, 'عرض خاص (ذرة حلوة) - تغليف شرينك زجاج 645غ*2', '6218653571417', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (430, 1, '430', NULL, NULL, NULL, 'عرض خاص (مربى المشمش ممروت) - تغليف شرينك زجاج 800غ*2', '6218653581393', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (431, 1, '431', NULL, NULL, NULL, 'عرض خاص (رب البندورة كثافة  28 - 30%) - تغليف شرينك زجاج 635غ*2', '6218653591422', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (432, 1, '432', NULL, NULL, NULL, 'عرض خاص (فطر مقطع) - تغليف شرينك زجاج 375غ*2', '6218653601435', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (433, 1, '433', NULL, NULL, NULL, 'عرض خاص (مربى الفريز ممروت 430غ 2عبوة+1عبوة مجاناً) - تغليف شرينك زجاج 430غ*3', '6218653611380', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (434, 1, '434', NULL, NULL, NULL, 'عرض خاص (مربى التوت ممروت 360غ 2عبوة+1عبوة مجاناً) - تغليف شرينك زجاج 360غ*3', '6218653621402', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (435, 1, '435', NULL, NULL, NULL, 'تونا سالاد الخلطة المكسيكية حلو  تنك  160غ', '6218653632040', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (436, 1, '436', NULL, NULL, NULL, 'تونا سالاد الخلطة المكسيكية حار  تنك  160غ', '6218653642049', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (437, 1, '437', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة الكشمش الأسود بلاستيك 320مل', '6218653655308', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (438, 1, '438', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة ليتشي بلاستيك 320مل', '6218653665307', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (439, 1, '439', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة البطيخ بلاستيك 320مل', '6218653675306', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (440, 1, '440', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة البرتقال بلاستيك 320مل', '6218653685305', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (441, 1, '441', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة المانغو بلاستيك 320مل', '6218653695304', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (442, 1, '442', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة العنب الأحمر بلاستيك 320مل', '6218653705300', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (443, 1, '443', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة الفريز بلاستيك 320مل', '6218653715309', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (444, 1, '444', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة الدراق بلاستيك 320مل', '6218653725308', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (445, 1, '445', NULL, NULL, NULL, 'عصير جوز الهند ناتا دي كوكو بنكهة جوز الهند بلاستيك 320مل', '6218653735307', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (446, 1, '446', NULL, NULL, NULL, 'أناناس مقطع  تنك  850غ', '6218653742299', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (447, 1, '447', NULL, NULL, NULL, 'شراب الفواكه الاستوائية  تنك  565غ', '6218653752359', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (448, 1, '448', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة الرمان زجاج 290مل', '6218653761016', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (449, 1, '449', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة المانغو زجاج 290مل', '6218653771015', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (450, 1, '450', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة الكوكتيل زجاج 290مل', '6218653781014', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (451, 1, '451', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة ليتشي زجاج 290مل', '6218653791013', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (452, 1, '452', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة الدراق زجاج 290مل', '6218653801019', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (453, 1, '453', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة الفريز زجاج 290مل', '6218653811018', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (454, 1, '454', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة باشن فروت زجاج 290مل', '6218653821017', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (455, 1, '455', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة جوز الهند زجاج 290مل', '6218653831016', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (456, 1, '456', NULL, NULL, NULL, 'شراب بذور الشيا بنكهة الأناناس زجاج 290مل', '6218653841015', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (457, 1, '457', NULL, NULL, NULL, 'عرض خاص (رب البندورة كثافة  28 - 30%) حسم 12% مجاناً زجاج 710غ', '6218653851441', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (458, 1, '458', NULL, NULL, NULL, 'قبار زجاج 300غ', '6218653861037', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (459, 1, '459', NULL, NULL, NULL, 'طحينة - منشأ سوري بلاستيك 800غ', '6218653875065', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (460, 1, '460', NULL, NULL, NULL, 'طحينة - منشأ سوري بلاستيك 4000غ', '6218653875225', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (461, 1, '461', NULL, NULL, NULL, 'حلاوة مع فستق اكسترا - منشأ سوري بلاستيك 400غ', '6218653885057', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (462, 1, '462', NULL, NULL, NULL, 'عرض خاص (ذرة حلوة) زجاج 645غ', '6218653891362', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (463, 1, '463', NULL, NULL, NULL, 'عرض خاص (مربى الفريز ممروت 430غ*2) - تغليف شرينك زجاج 430غ*2', '6218653901450', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (464, 1, '464', NULL, NULL, NULL, 'عرض خاص (مربى الكرز ممروت 430غ*2) - تغليف شرينك زجاج 430غ*2', '6218653911459', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (465, 1, '465', NULL, NULL, NULL, 'عرض خاص (مربى التوت ممروت 430غ*2) - تغليف شرينك زجاج 430غ*2', '6218653921458', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (466, 1, '466', NULL, NULL, NULL, 'عرض خاص (مربى التين ممروت 430غ*2) - تغليف شرينك زجاج 430غ*2', '6218653931457', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (467, 1, '467', NULL, NULL, NULL, 'عرض خاص (مربى المشمش ممروت 430غ*2) - تغليف شرينك زجاج 430غ*2', '6218653941456', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (468, 1, '468', NULL, NULL, NULL, 'عرض خاص (ذرة حلوة 360غ*3) زجاج 360غ*3', '6218653951400', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (469, 1, '469', NULL, NULL, NULL, 'عرض خاص (فطر مقطع 360غ*2) زجاج 360غ*2', '6218653961461', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (470, 1, '470', NULL, NULL, NULL, 'فطر مقطع كينك اويستر تنك 3000غ', '6218653974010', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (471, 1, '471', NULL, NULL, NULL, 'فطر مقطع كينك اويستر تنك 400غ', '6218653974072', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (472, 1, '472', NULL, NULL, NULL, 'عرض خاص (تونا سكيب جاك رقائق حلو) - تغليف شرينك  تنك  140غ*3', '6218653982312', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (473, 1, '473', NULL, NULL, NULL, 'عرض خاص (تونا سكيب جاك رقائق حار) - تغليف شرينك  تنك  140غ*3', '6218653992311', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 28000.00, 0.00, 0.00, 28000.00, 28000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (474, 1, '474', NULL, NULL, NULL, 'حلاوة سادة بدون سكر - منشأ لبناني بلاستيك 400غ', '6218654015057', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (475, 1, '475', NULL, NULL, NULL, 'حلاوة بالفستق بدون سكر - منشأ لبناني بلاستيك 400غ', '6218654025056', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (476, 1, '476', NULL, NULL, NULL, 'زيت زيتون بكر منشأ الإمارات بلاستيك 5000مل', '6218654035017', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:15');
INSERT INTO `products` VALUES (477, 1, '477', NULL, NULL, NULL, 'زيت زيتون بكر منشأ الإمارات بلاستيك 1000مل', '6218654035024', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (478, 1, '478', NULL, NULL, NULL, 'زيت زيتون بكر منشأ الإمارات بلاستيك 250مل', '6218654035185', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (479, 1, '479', NULL, NULL, NULL, 'زيت زيتون بكر منشأ الإمارات بلاستيك 500مل', '6218654035192', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (480, 1, '480', NULL, NULL, NULL, 'عرض خاص (تونا سكيب جاك قطع بالزيت النباتي حلو 140غ+20غ)  تنك  160غ', '6218654042046', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (481, 1, '481', NULL, NULL, NULL, 'عرض خاص (تونا سكيب جاك قطع بالزيت النباتي حار 140غ+20غ)  تنك  160غ', '6218654052045', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (482, 1, '482', NULL, NULL, NULL, 'لب المانغا تنك 3000غ', '6218654064017', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (483, 1, '483', NULL, NULL, NULL, 'لانشون دجاج - علبة طويلة  تنك  340غ', '6218654072197', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 18500.00, 0.00, 0.00, 18500.00, 18500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (484, 1, '484', NULL, NULL, NULL, 'تونا سكيب جاك حلو سولد  تنك  160غ', '6218654082042', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (485, 1, '485', NULL, NULL, NULL, 'تونا سكيب جاك حار سولد  تنك  160غ', '6218654092041', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (486, 1, '486', NULL, NULL, NULL, 'ترمس حب مسلوق زجاج 360غ', '6218654101064', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (487, 1, '487', NULL, NULL, NULL, 'ترمس حب مسلوق زجاج 330غ', '6218654101484', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (488, 1, '488', NULL, NULL, NULL, 'أصابع الأناناس في شراب خفيف مع ماء جوز الهند (منشأ تايلاند) زجاج 450غ', '6218654111490', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (489, 1, '489', NULL, NULL, NULL, 'أصابع الأناناس في شراب خفيف مع ماء جوز الهند (منشأ تايلاند) زجاج 680غ', '6218654111506', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (490, 1, '490', NULL, NULL, NULL, 'بيبي كورن في محلول ملحي (منشأ تايلاند) زجاج 370غ', '6218654121512', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (491, 1, '491', NULL, NULL, NULL, 'بيبي كورن محفوظة في خل (منشأ تايلاند) زجاج 370غ', '6218654131511', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (492, 1, '492', NULL, NULL, NULL, 'بيبي كورن محفوظة في خل مع فليفلة حارة (منشأ تايلاند) زجاج 370غ', '6218654141510', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (493, 1, '493', NULL, NULL, NULL, 'سردين مغربي بزيت الزيتون حلو  تنك  125غ', '6218654152097', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (494, 1, '494', NULL, NULL, NULL, 'سردين مغربي بزيت الزيتون حار  تنك  125غ', '6218654162096', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10500.00, 0.00, 0.00, 10500.00, 10500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (495, 1, '495', NULL, NULL, NULL, 'شرائح الأناناس غولدن  تنك  565غ', '6218654172354', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 13000.00, 0.00, 0.00, 13000.00, 13000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (496, 1, '496', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو اوريجينال زجاج 290مل', '6218654181011', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (497, 1, '497', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة الأناناس زجاج 290مل', '6218654191010', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (498, 1, '498', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة الموز زجاج 290مل', '6218654201016', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (499, 1, '499', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة بطيخ أصفر زجاج 290مل', '6218654211015', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (500, 1, '500', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة موكا زجاج 290مل', '6218654221014', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (501, 1, '501', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة شوكولا زجاج 290مل', '6218654231013', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (502, 1, '502', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة لب جوز الهند زجاج 290مل', '6218654241012', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (503, 1, '503', NULL, NULL, NULL, 'شراب حليب جوز الهند مع ناتا دي كوكو بنكهة فريز زجاج 290مل', '6218654251011', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (504, 1, '504', NULL, NULL, NULL, 'لانشون (مرتديلا) حبش - علبة طويلة  تنك  340غ', '6218654262192', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (505, 1, '505', NULL, NULL, NULL, 'حلاوة دايت بالفستق - منشأ لبناني بلاستيك 400غ', '6218654275055', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (506, 1, '506', NULL, NULL, NULL, 'حلاوة دايت بالشوكولا والبندق - منشأ لبناني بلاستيك 400غ', '6218654285054', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (507, 1, '507', NULL, NULL, NULL, 'ليو عرض ( شاي 100ظرف +12 ظرف مجاناً )', '6219240000013', 'Unit: عبوة', 'عبوة', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (508, 1, '508', NULL, NULL, NULL, 'ليو عرض ( شاي أسود عادي 100+20 ظرف )', '6219240000037', 'Unit: عبوة', 'عبوة', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (509, 1, '509', NULL, NULL, NULL, 'ليو عرض ( شاي سوبر سوبريم 400غ +100غ هدية )', '6219240000020', 'Unit: عبوة', 'عبوة', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (510, 1, '510', NULL, NULL, NULL, 'ليو عرض ( شاي سوبر سوبريم 100+20 ظرف )', '6219240000044', 'Unit: عبوة', 'عبوة', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 30000.00, 0.00, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (511, 1, '511', NULL, NULL, NULL, 'ليو عرض (  شاي أسود 400غ مع 50غ مجاناً)', '6219240000051', 'Unit: عبوة', 'عبوة', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (512, 1, '512', NULL, NULL, NULL, 'أرز بسمتي سيلا غولدن نايلون 1000غ', '6219241011018', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (513, 1, '513', NULL, NULL, NULL, 'أرز بسمتي سيلا غولدن نايلون 2000غ', '6219241011025', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (514, 1, '514', NULL, NULL, NULL, 'أرز بسمتي سيلا غولدن نايلون 25000غ', '6219241011032', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (515, 1, '515', NULL, NULL, NULL, 'أرز بسمتي سيلا غولدن خيش 2000غ', '6219241015016', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (516, 1, '516', NULL, NULL, NULL, 'أرز بسمتي سيلا غولدن خيش 5000غ', '6219241015023', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (517, 1, '517', NULL, NULL, NULL, 'أرز بسمتي سيلا غولدن خيش 1000غ', '6219241015030', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (518, 1, '518', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي نايلون 1000غ', '6219241021017', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (519, 1, '519', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي نايلون 2000غ', '6219241021024', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (520, 1, '520', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي نايلون 25000غ', '6219241021031', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (521, 1, '521', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي نايلون 900غ', '6219241021093', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (522, 1, '522', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي نايلون 4500غ', '6219241021109', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (523, 1, '523', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي خيش 2000غ', '6219241025015', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (524, 1, '524', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي خيش 5000غ', '6219241025022', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (525, 1, '525', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي خيش 1000غ', '6219241025039', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (526, 1, '526', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي خيش 900غ', '6219241025046', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (527, 1, '527', NULL, NULL, NULL, 'أرز بسمتي سيلا كريمي خيش 4500غ', '6219241025053', 'Unit: خيش', 'خيش', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (528, 1, '528', NULL, NULL, NULL, 'شاي أسود نايلون 100ظرف', '6219241031078', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (529, 1, '529', NULL, NULL, NULL, 'شاي أسود كرتون 180غ', '6219241032013', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (530, 1, '530', NULL, NULL, NULL, 'شاي أسود كرتون 400غ', '6219241032020', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 56000.00, 0.00, 0.00, 56000.00, 56000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (531, 1, '531', NULL, NULL, NULL, 'شاي أسود كرتون 25ظرف', '6219241032037', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 7000.00, 0.00, 0.00, 7000.00, 7000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (532, 1, '532', NULL, NULL, NULL, 'شاي أسود كرتون 200غ', '6219241032051', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (533, 1, '533', NULL, NULL, NULL, 'شاي أسود كرتون أصفر 100ظرف', '6219241032068', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (534, 1, '534', NULL, NULL, NULL, 'شاي أسود كرتون 112ظرف', '6219241032075', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (535, 1, '535', NULL, NULL, NULL, 'شاي سوبر سوبريم كرتون 180غ', '6219241042012', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (536, 1, '536', NULL, NULL, NULL, 'شاي سوبر سوبريم كرتون 400غ', '6219241042029', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 56000.00, 0.00, 0.00, 56000.00, 56000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (537, 1, '537', NULL, NULL, NULL, 'شاي سوبر سوبريم كرتون 25ظرف', '6219241042036', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 9000.00, 0.00, 0.00, 9000.00, 9000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (538, 1, '538', NULL, NULL, NULL, 'شاي سوبر سوبريم كرتون 100ظرف', '6219241042043', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (539, 1, '539', NULL, NULL, NULL, 'شاي سوبر سوبريم كرتون 500غ', '6219241042104', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (540, 1, '540', NULL, NULL, NULL, 'شاي سوبر سوبريم سلفان 400غ', '6219241043033', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 49000.00, 0.00, 0.00, 49000.00, 49000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (541, 1, '541', NULL, NULL, NULL, 'شاي سوبر سوبريم سلفان 180غ', '6219241043064', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 25000.00, 0.00, 0.00, 25000.00, 25000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (542, 1, '542', NULL, NULL, NULL, 'شاي سوبر سوبريم تنك 500غ', '6219241044023', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (543, 1, '543', NULL, NULL, NULL, 'زهرة الشاي سلفان 150غ', '6219241053049', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (544, 1, '544', NULL, NULL, NULL, 'زهرة الشاي تنك 150غ', '6219241054015', 'Unit: تنك', 'تنك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 40000.00, 0.00, 0.00, 40000.00, 40000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (545, 1, '545', NULL, NULL, NULL, 'شاي أخضر كرتون 25ظرف', '6219241062034', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (546, 1, '546', NULL, NULL, NULL, 'شاي أخضر سلفان 150غ', '6219241063048', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (547, 1, '547', NULL, NULL, NULL, 'قهوة سوبر سوبريم سادة بدون هال سلفان 200غ', '6219241073023', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (548, 1, '548', NULL, NULL, NULL, 'قهوة سوبر سوبريم سادة بدون هال سلفان 400غ', '6219241073030', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (549, 1, '549', NULL, NULL, NULL, 'قهوة سوبر سوبريم سادة بدون هال سلفان 100غ', '6219241073054', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (550, 1, '550', NULL, NULL, NULL, 'قهوة سوبر سوبريم مع هال وسط سلفان 200غ', '6219241083022', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (551, 1, '551', NULL, NULL, NULL, 'قهوة سوبر سوبريم مع هال وسط سلفان 400غ', '6219241083039', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (552, 1, '552', NULL, NULL, NULL, 'قهوة سوبر سوبريم مع هال وسط سلفان 100غ', '6219241083053', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (553, 1, '553', NULL, NULL, NULL, 'قهوة سوبر سوبريم مع هال اكسترا سلفان 200غ', '6219241093021', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (554, 1, '554', NULL, NULL, NULL, 'قهوة سوبر سوبريم مع هال اكسترا سلفان 400غ', '6219241093038', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (555, 1, '555', NULL, NULL, NULL, 'قهوة سوبر سوبريم مع هال اكسترا سلفان 100غ', '6219241093052', 'Unit: سلفان', 'سلفان', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (556, 1, '556', NULL, NULL, NULL, 'أرز كامولينو فاخر نايلون 1000غ', '6219241101016', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (557, 1, '557', NULL, NULL, NULL, 'أرز كامولينو فاخر نايلون 10000غ', '6219241101047', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (558, 1, '558', NULL, NULL, NULL, 'أرز كريمي بسمتي نايلون 900غ', '6219241111091', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (559, 1, '559', NULL, NULL, NULL, 'أرز كريمي بسمتي نايلون 4500غ', '6219241111107', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (560, 1, '560', NULL, NULL, NULL, 'حمص حب زجاج 360غ', '6219241126019', 'Unit: زجاج', 'زجاج', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (561, 1, '561', NULL, NULL, NULL, 'زعتر أخضر الخلطة الفلسطينية بلاستيك 400غ', '6219241137015', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (562, 1, '562', NULL, NULL, NULL, 'زعتر أحمر بلاستيك 400غ', '6219241147014', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (563, 1, '563', NULL, NULL, NULL, 'قهوة سريعة الذوبان كلاسيك بلاستيك 160غ', '6219241157020', 'Unit: بلاستيك', 'بلاستيك', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (564, 1, '564', NULL, NULL, NULL, 'أرز كامولينو منشاً ايطالي نايلون 1000غ', '6219241161010', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10000.00, 0.00, 0.00, 10000.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (565, 1, '565', NULL, NULL, NULL, 'أرز كامولينو منشاً ايطالي نايلون 5000غ', '6219241161119', 'Unit: نايلون', 'نايلون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (566, 1, '566', NULL, NULL, NULL, 'عرض (شاي أسود 100ظرف أصفر + 12ظرف هدية) كرتون 112ظرف', '6219241172078', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (567, 1, '567', NULL, NULL, NULL, 'شاي أسود ظرف أصفر مع هيل كرتون 25ظرف', '6219241182039', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (568, 1, '568', NULL, NULL, NULL, 'شاي أسود ظرف أصفر مع هيل كرتون 100ظرف', '6219241182046', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (569, 1, '569', NULL, NULL, NULL, 'شاي أسود ظرف أصفر مع نعنع كرتون 25ظرف', '6219241192038', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (570, 1, '570', NULL, NULL, NULL, 'شاي أسود ظرف أصفر مع نعنع كرتون 100ظرف', '6219241192045', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (571, 1, '571', NULL, NULL, NULL, 'شاي أسود ظرف أصفر ايرل غراي كرتون 25ظرف', '6219241202034', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 8000.00, 0.00, 0.00, 8000.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (572, 1, '572', NULL, NULL, NULL, 'شاي أسود ظرف أصفر ايرل غراي كرتون 100ظرف', '6219241202041', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (573, 1, '573', NULL, NULL, NULL, 'عرض (شاي أسود 25 ظرف أصفر + 5 ظرف شاي أسود مع هيل هدية) كرتون 30ظرف', '6219241212088', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (574, 1, '574', NULL, NULL, NULL, 'عرض (شاي أسود 25 ظرف أصفر + 5 ظرف شاي أسود مع قرفة هدية) كرتون 30ظرف', '6219241222087', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (575, 1, '575', NULL, NULL, NULL, 'عرض (شاي أسود 25 ظرف أصفر + 5 ظرف شاي أسود مع ايرل غراي هدية) كرتون 30ظرف', '6219241232086', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (576, 1, '576', NULL, NULL, NULL, 'عرض (شاي أسود 25 ظرف أصفر + 5 ظرف أصفر شاي أسود هدية) كرتون 30ظرف', '6219241242085', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 7000.00, 0.00, 0.00, 7000.00, 7000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (577, 1, '577', NULL, NULL, NULL, 'عرض امسح واربح (شاي سوبر سوبريم) كرتون 400غ', '6219241252022', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 50000.00, 0.00, 0.00, 50000.00, 50000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (578, 1, '578', NULL, NULL, NULL, 'عرض امسح واربح (شاي أسود - ظرف أصفر) كرتون 25ظرف', '6219241262038', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (579, 1, '579', NULL, NULL, NULL, 'عرض (شاي سوبر سوبريم 400غ*2 + ابريق مجاناً) كرتون 400غ*2', '6219241272099', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (580, 1, '580', NULL, NULL, NULL, 'عرض (شاي سوبر سوبريم 500غ*2 + ابريق مجاناً) كرتون 500غ*2', '6219241282111', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (581, 1, '581', NULL, NULL, NULL, 'شاي أسود كرتون 25ظرف', '6219242012038', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (582, 1, '582', NULL, NULL, NULL, 'شاي أسود كرتون 100ظرف', '6219242012045', 'Unit: كرتون', 'كرتون', 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 5, NULL, NULL, 1, '2026-07-23 16:44:16');
INSERT INTO `products` VALUES (583, 1, NULL, NULL, NULL, NULL, 'البستان قمر الدين', '6210393039011', NULL, NULL, 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 26, 5, NULL, NULL, 1, '2026-07-24 23:23:52');
INSERT INTO `products` VALUES (584, 1, NULL, NULL, NULL, NULL, 'اوكتان بايرو', '8974654986', '', NULL, 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 110.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 7, 5, NULL, NULL, 1, '2026-07-28 21:57:04');
INSERT INTO `products` VALUES (585, 1, NULL, NULL, NULL, NULL, 'ميشلين شامبو', '324236234234234', NULL, NULL, 1, NULL, 1.00, NULL, NULL, 1.00, NULL, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 9.00, 91, 5, NULL, NULL, 1, '2026-07-28 22:01:41');

-- ----------------------------
-- Table structure for purchase_order_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `po_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `total` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `received_quantity` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `po_id`(`po_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `purchase_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_order_items
-- ----------------------------
INSERT INTO `purchase_order_items` VALUES (1, 1, 582, 1, 0.00, 0.00, 1);
INSERT INTO `purchase_order_items` VALUES (2, 2, 582, 1, 0.00, 0.00, 1);
INSERT INTO `purchase_order_items` VALUES (3, 2, 581, 1, 0.00, 0.00, 1);
INSERT INTO `purchase_order_items` VALUES (4, 2, 579, 1, 0.00, 0.00, 1);
INSERT INTO `purchase_order_items` VALUES (7, 4, 41, 1, 15000.00, 15000.00, 1);
INSERT INTO `purchase_order_items` VALUES (8, 4, 41, 1, 15000.00, 15000.00, 1);
INSERT INTO `purchase_order_items` VALUES (9, 5, 266, 2147483647, 30000.00, 99999999.99, 2147483647);
INSERT INTO `purchase_order_items` VALUES (10, 6, 266, 100, 30000.00, 3000000.00, 100);
INSERT INTO `purchase_order_items` VALUES (11, 6, 583, 100, 10.00, 1000.00, 100);
INSERT INTO `purchase_order_items` VALUES (12, 7, 585, 100, 10.00, 1000.00, 100);
INSERT INTO `purchase_order_items` VALUES (14, 8, 1, 1, 7000.00, 7000.00, 1);

-- ----------------------------
-- Table structure for purchase_orders
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `po_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `device_id` int UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `expected_delivery` date NULL DEFAULT NULL,
  `status` enum('pending','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending',
  `total_amount` decimal(10, 2) NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `po_no`(`po_no` ASC) USING BTREE,
  INDEX `supplier_id`(`supplier_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `purchase_orders_ibfk_3` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_orders
-- ----------------------------
INSERT INTO `purchase_orders` VALUES (1, 'PO-20260723-7071', 1, 1, 1, '2026-07-23', NULL, 'received', 0.00, '', '2026-07-23 16:45:01');
INSERT INTO `purchase_orders` VALUES (2, 'PO-20260723-8471', 1, 1, 1, '2026-07-23', NULL, 'received', 0.00, '', '2026-07-23 16:46:03');
INSERT INTO `purchase_orders` VALUES (4, 'PO-20260724-5436', 1, 1, 1, '2026-07-24', NULL, 'received', 30000.00, '', '2026-07-24 12:54:55');
INSERT INTO `purchase_orders` VALUES (5, 'PO-20260724-4945', 1, 1, 1, '2026-07-24', NULL, 'received', 99999999.99, '', '2026-07-24 23:22:56');
INSERT INTO `purchase_orders` VALUES (6, 'PO-20260724-7635', 1, 1, 1, '2026-07-24', NULL, 'received', 3001000.00, '', '2026-07-24 23:24:03');
INSERT INTO `purchase_orders` VALUES (7, 'PO-20260728-3207', 1, 1, 1, '2026-07-28', NULL, 'received', 1000.00, '', '2026-07-28 22:01:51');
INSERT INTO `purchase_orders` VALUES (8, 'PO-20260827-2660', 1, 1, 1, '2026-08-27', '2026-08-27', 'received', 7000.00, '', '2026-08-27 11:35:14');

-- ----------------------------
-- Table structure for receipt_templates
-- ----------------------------
DROP TABLE IF EXISTS `receipt_templates`;
CREATE TABLE `receipt_templates`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NULL DEFAULT 0,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of receipt_templates
-- ----------------------------
INSERT INTO `receipt_templates` VALUES (1, 'Default Thermal', 1, '{\r\n    \"store_name\": {\"enabled\": true, \"font_size\": 16, \"font_weight\": \"bold\", \"align\": \"center\"},\r\n    \"store_address\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"center\"},\r\n    \"store_phone\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"center\"},\r\n    \"invoice_no\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"bold\", \"align\": \"left\"},\r\n    \"date\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"left\"},\r\n    \"cashier\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"left\"},\r\n    \"customer\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"left\"},\r\n    \"items_table\": {\r\n        \"enabled\": true,\r\n        \"columns\": {\"item\": 20, \"qty\": 5, \"price\": 10, \"total\": 10},\r\n        \"border_style\": \"box\",\r\n        \"header_font_weight\": \"bold\"\r\n    },\r\n    \"subtotal\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"left\"},\r\n    \"discount\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"left\"},\r\n    \"tax\": {\"enabled\": true, \"font_size\": 12, \"font_weight\": \"normal\", \"align\": \"left\"},\r\n    \"total\": {\"enabled\": true, \"font_size\": 16, \"font_weight\": \"bold\", \"align\": \"left\"},\r\n    \"footer\": {\"enabled\": true, \"font_size\": 11, \"font_weight\": \"normal\", \"align\": \"center\"},\r\n    \"direction\": \"ltr\",\r\n    \"paper_width\": 40,\r\n    \"logo_enabled\": false,\r\n    \"logo_path\": \"\"\r\n}', '2026-07-28 11:34:20', '2026-07-28 11:34:20');
INSERT INTO `receipt_templates` VALUES (2, 'Untitled', 0, '{\"direction\":\"ltr\",\"paper_width\":40,\"font_size\":12,\"font_weight\":\"normal\",\"footer_text\":\"Thank you for your business!\",\"store_name\":{\"enabled\":false},\"store_address\":{\"enabled\":false},\"store_phone\":{\"enabled\":false},\"invoice_no\":{\"enabled\":false},\"date\":{\"enabled\":false},\"cashier\":{\"enabled\":false},\"customer\":{\"enabled\":false},\"items_table\":{\"enabled\":false,\"border_style\":\"box\"},\"subtotal\":{\"enabled\":false},\"discount\":{\"enabled\":false},\"tax\":{\"enabled\":false},\"total\":{\"enabled\":false},\"footer\":{\"enabled\":false}}', '2026-07-28 12:11:19', '2026-07-28 12:11:19');
INSERT INTO `receipt_templates` VALUES (3, 'Untitled', 0, '{\"direction\":\"ltr\",\"paper_width\":40,\"font_size\":12,\"font_weight\":\"normal\",\"footer_text\":\"Thank you for your business!\",\"store_name\":{\"enabled\":false},\"store_address\":{\"enabled\":false},\"store_phone\":{\"enabled\":false},\"invoice_no\":{\"enabled\":false},\"date\":{\"enabled\":false},\"cashier\":{\"enabled\":false},\"customer\":{\"enabled\":false},\"items_table\":{\"enabled\":false,\"border_style\":\"box\"},\"subtotal\":{\"enabled\":false},\"discount\":{\"enabled\":false},\"tax\":{\"enabled\":false},\"total\":{\"enabled\":false},\"footer\":{\"enabled\":false}}', '2026-07-28 12:51:36', '2026-07-28 12:51:36');

-- ----------------------------
-- Table structure for return_items
-- ----------------------------
DROP TABLE IF EXISTS `return_items`;
CREATE TABLE `return_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_id` int UNSIGNED NOT NULL,
  `sale_item_id` int UNSIGNED NULL DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `refund_amount` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `return_id`(`return_id` ASC) USING BTREE,
  INDEX `sale_item_id`(`sale_item_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `return_items_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `return_items_ibfk_2` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `return_items_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of return_items
-- ----------------------------
INSERT INTO `return_items` VALUES (1, 1, 6, 583, 5, 50.00, 'customer_request');
INSERT INTO `return_items` VALUES (2, 2, NULL, 583, 1, 10.00, 'walkin');
INSERT INTO `return_items` VALUES (3, 3, NULL, 584, 2, 220.00, 'walkin');
INSERT INTO `return_items` VALUES (4, 4, NULL, 585, 1, 10.00, 'walkin');

-- ----------------------------
-- Table structure for returns
-- ----------------------------
DROP TABLE IF EXISTS `returns`;
CREATE TABLE `returns`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int UNSIGNED NULL DEFAULT NULL,
  `sale_id` int UNSIGNED NULL DEFAULT NULL,
  `return_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_type` enum('invoice','walkin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'invoice',
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `refund_method` enum('cash','card','mobile','store_credit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'cash',
  `total_refund` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `return_no`(`return_no` ASC) USING BTREE,
  INDEX `sale_id`(`sale_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `returns_ibfk_3` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of returns
-- ----------------------------
INSERT INTO `returns` VALUES (1, 1, 5, 'RET-20260728-7729', 'invoice', NULL, NULL, 1, '2026-07-28 09:55:54', 'customer_request', 'cash', 50.00, '');
INSERT INTO `returns` VALUES (2, 1, NULL, 'RET-20260728-5913', 'walkin', NULL, NULL, 1, '2026-07-28 09:58:18', 'customer_request', 'cash', 10.00, '');
INSERT INTO `returns` VALUES (3, 1, NULL, 'RET-20260728-3724', 'walkin', NULL, NULL, 1, '2026-07-28 21:59:26', 'customer_request', 'cash', 0.00, '');
INSERT INTO `returns` VALUES (4, 1, NULL, 'RET-20260729-9208', 'walkin', NULL, NULL, 1, '2026-07-29 10:23:25', 'customer_request', 'cash', 10.00, '');

-- ----------------------------
-- Table structure for sale_items
-- ----------------------------
DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE `sale_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10, 2) NOT NULL,
  `discount` decimal(10, 2) NULL DEFAULT 0.00,
  `total` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sale_id`(`sale_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 82 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sale_items
-- ----------------------------
INSERT INTO `sale_items` VALUES (1, 1, 266, 10, 30000.00, 0.00, 300000.00);
INSERT INTO `sale_items` VALUES (2, 1, 583, 6, 10.00, 0.00, 60.00);
INSERT INTO `sale_items` VALUES (3, 2, 583, 4, 10.00, 0.00, 40.00);
INSERT INTO `sale_items` VALUES (4, 3, 583, 2, 10.00, 0.00, 20.00);
INSERT INTO `sale_items` VALUES (5, 4, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (6, 5, 583, 5, 10.00, 0.00, 50.00);
INSERT INTO `sale_items` VALUES (7, 6, 583, 2, 10.00, 0.00, 20.00);
INSERT INTO `sale_items` VALUES (8, 7, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (9, 8, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (10, 9, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (11, 10, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (12, 11, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (13, 12, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (14, 13, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (15, 14, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (16, 15, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (17, 16, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (18, 17, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (19, 18, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (20, 19, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (21, 20, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (22, 21, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (23, 22, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (24, 23, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (25, 24, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (26, 25, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (27, 26, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (28, 27, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (29, 28, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (30, 29, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (31, 30, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (32, 31, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (33, 32, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (34, 33, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (35, 34, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (36, 35, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (37, 36, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (38, 37, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (39, 38, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (40, 39, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (41, 40, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (42, 41, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (43, 42, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (44, 43, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (45, 44, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (46, 45, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (47, 46, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (48, 47, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (49, 48, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (50, 49, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (51, 50, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (52, 51, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (53, 52, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (54, 53, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (55, 54, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (56, 55, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (57, 56, 584, 3, 110.00, 0.00, 330.00);
INSERT INTO `sale_items` VALUES (58, 57, 585, 3, 10.00, 0.00, 30.00);
INSERT INTO `sale_items` VALUES (59, 58, 585, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (60, 59, 582, 2, 0.00, 0.00, 0.00);
INSERT INTO `sale_items` VALUES (61, 60, 585, 3, 10.00, 0.00, 30.00);
INSERT INTO `sale_items` VALUES (62, 61, 585, 3, 10.00, 0.00, 30.00);
INSERT INTO `sale_items` VALUES (63, 62, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (64, 63, 584, 4, 110.00, 0.00, 440.00);
INSERT INTO `sale_items` VALUES (65, 64, 581, 1, 0.00, 0.00, 0.00);
INSERT INTO `sale_items` VALUES (66, 65, 579, 1, 0.00, 0.00, 0.00);
INSERT INTO `sale_items` VALUES (67, 66, 583, 3, 10.00, 0.00, 30.00);
INSERT INTO `sale_items` VALUES (68, 67, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (69, 68, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (70, 69, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (71, 70, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (72, 71, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (73, 72, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (74, 73, 583, 1, 10.00, 0.00, 10.00);
INSERT INTO `sale_items` VALUES (75, 74, 584, 1, 110.00, 0.00, 110.00);
INSERT INTO `sale_items` VALUES (76, 75, 584, 1, 110.00, 0.00, 110.00);
INSERT INTO `sale_items` VALUES (77, 76, 584, 1, 110.00, 0.00, 110.00);
INSERT INTO `sale_items` VALUES (78, 77, 584, 4, 110.00, 0.00, 440.00);
INSERT INTO `sale_items` VALUES (79, 78, 584, 1, 110.00, 0.00, 110.00);
INSERT INTO `sale_items` VALUES (80, 79, 584, 1, 110.00, 0.00, 110.00);
INSERT INTO `sale_items` VALUES (81, 80, 584, 4, 110.00, 0.00, 440.00);

-- ----------------------------
-- Table structure for sales
-- ----------------------------
DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` int UNSIGNED NULL DEFAULT NULL,
  `invoice_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `customer_id` int UNSIGNED NULL DEFAULT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subtotal` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10, 2) NULL DEFAULT 0.00,
  `tax` decimal(10, 2) NULL DEFAULT 0.00,
  `total` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','mobile') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'cash',
  `payment_status` enum('paid','pending','partially') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'paid',
  `return_status` enum('none','partial','full') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'none',
  `return_total` decimal(10, 2) NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `invoice_no`(`invoice_no` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `customer_id`(`customer_id` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sales
-- ----------------------------
INSERT INTO `sales` VALUES (1, 1, 'INV-20260724-7725', 1, NULL, NULL, NULL, 300060.00, 0.00, 0.00, 300060.00, 'cash', 'paid', 'none', 0.00, '2026-07-24 23:26:03');
INSERT INTO `sales` VALUES (2, 1, 'INV-20260726-4276', 1, NULL, NULL, NULL, 40.00, 0.00, 0.00, 40.00, 'cash', 'paid', 'none', 0.00, '2026-07-26 13:05:36');
INSERT INTO `sales` VALUES (3, 1, 'INV-20260727-5325', 1, NULL, NULL, NULL, 20.00, 0.00, 0.00, 20.00, 'cash', 'paid', 'none', 0.00, '2026-07-27 13:19:58');
INSERT INTO `sales` VALUES (4, 1, 'INV-20260728-3789', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 09:35:18');
INSERT INTO `sales` VALUES (5, 1, 'INV-20260728-7038', 1, NULL, NULL, NULL, 50.00, 0.00, 0.00, 50.00, 'cash', 'paid', 'full', 50.00, '2026-07-28 09:54:26');
INSERT INTO `sales` VALUES (6, 1, 'INV-20260728-5645', 1, NULL, NULL, NULL, 20.00, 0.00, 0.00, 20.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 09:57:00');
INSERT INTO `sales` VALUES (7, 1, 'INV-20260728-9444', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 12:57:14');
INSERT INTO `sales` VALUES (8, 1, 'INV-20260728-6400', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:22:49');
INSERT INTO `sales` VALUES (9, 1, 'INV-20260728-1771', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:23:21');
INSERT INTO `sales` VALUES (10, 1, 'INV-20260728-8314', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:24:03');
INSERT INTO `sales` VALUES (11, 1, 'INV-20260728-5115', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:26:45');
INSERT INTO `sales` VALUES (12, 1, 'INV-20260728-8312', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:54:58');
INSERT INTO `sales` VALUES (13, 1, 'INV-20260728-8828', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:56:25');
INSERT INTO `sales` VALUES (14, 1, 'INV-20260728-5226', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:56:44');
INSERT INTO `sales` VALUES (15, 1, 'INV-20260728-4962', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:56:58');
INSERT INTO `sales` VALUES (16, 1, 'INV-20260728-8764', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 14:57:23');
INSERT INTO `sales` VALUES (17, 1, 'INV-20260728-6953', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:02:12');
INSERT INTO `sales` VALUES (18, 1, 'INV-20260728-8426', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:02:29');
INSERT INTO `sales` VALUES (19, 1, 'INV-20260728-6576', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:03:41');
INSERT INTO `sales` VALUES (20, 1, 'INV-20260728-2575', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:04:12');
INSERT INTO `sales` VALUES (21, 1, 'INV-20260728-8335', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:04:39');
INSERT INTO `sales` VALUES (22, 1, 'INV-20260728-7588', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:13:51');
INSERT INTO `sales` VALUES (23, 1, 'INV-20260728-3025', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:18:37');
INSERT INTO `sales` VALUES (24, 1, 'INV-20260728-4929', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:23:01');
INSERT INTO `sales` VALUES (25, 1, 'INV-20260728-5051', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:23:36');
INSERT INTO `sales` VALUES (26, 1, 'INV-20260728-1263', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:28:53');
INSERT INTO `sales` VALUES (27, 1, 'INV-20260728-5518', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:35:02');
INSERT INTO `sales` VALUES (28, 1, 'INV-20260728-9769', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:44:08');
INSERT INTO `sales` VALUES (29, 1, 'INV-20260728-2955', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:44:23');
INSERT INTO `sales` VALUES (30, 1, 'INV-20260728-7340', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:45:06');
INSERT INTO `sales` VALUES (31, 1, 'INV-20260728-1235', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:45:32');
INSERT INTO `sales` VALUES (32, 1, 'INV-20260728-9700', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:46:20');
INSERT INTO `sales` VALUES (33, 1, 'INV-20260728-4448', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:47:29');
INSERT INTO `sales` VALUES (34, 1, 'INV-20260728-2168', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:48:30');
INSERT INTO `sales` VALUES (35, 1, 'INV-20260728-4252', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:48:41');
INSERT INTO `sales` VALUES (36, 1, 'INV-20260728-7729', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:54:00');
INSERT INTO `sales` VALUES (37, 1, 'INV-20260728-2996', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 15:56:27');
INSERT INTO `sales` VALUES (38, 1, 'INV-20260728-8147', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 17:51:45');
INSERT INTO `sales` VALUES (39, 1, 'INV-20260728-3861', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 17:53:13');
INSERT INTO `sales` VALUES (40, 1, 'INV-20260728-7344', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:04:01');
INSERT INTO `sales` VALUES (41, 1, 'INV-20260728-2460', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:07:43');
INSERT INTO `sales` VALUES (42, 1, 'INV-20260728-6713', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:08:15');
INSERT INTO `sales` VALUES (43, 1, 'INV-20260728-5955', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:08:41');
INSERT INTO `sales` VALUES (44, 1, 'INV-20260728-7011', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:15:38');
INSERT INTO `sales` VALUES (45, 1, 'INV-20260728-3504', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:16:16');
INSERT INTO `sales` VALUES (46, 1, 'INV-20260728-2776', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:23:34');
INSERT INTO `sales` VALUES (47, 1, 'INV-20260728-7517', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:24:21');
INSERT INTO `sales` VALUES (48, 1, 'INV-20260728-5541', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:31:51');
INSERT INTO `sales` VALUES (49, 1, 'INV-20260728-7530', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:35:56');
INSERT INTO `sales` VALUES (50, 1, 'INV-20260728-6925', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:49:38');
INSERT INTO `sales` VALUES (51, 1, 'INV-20260728-2794', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:52:00');
INSERT INTO `sales` VALUES (52, 1, 'INV-20260728-9876', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:53:09');
INSERT INTO `sales` VALUES (53, 1, 'INV-20260728-1874', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 18:54:23');
INSERT INTO `sales` VALUES (54, 1, 'INV-20260728-7350', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 19:13:15');
INSERT INTO `sales` VALUES (55, 1, 'INV-20260728-6983', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 19:13:36');
INSERT INTO `sales` VALUES (56, 1, 'INV-20260728-7794', 1, 1, NULL, NULL, 330.00, 0.00, 0.00, 330.00, 'cash', 'paid', 'none', 0.00, '2026-07-28 21:58:19');
INSERT INTO `sales` VALUES (57, 1, 'INV-20260729-4917', 1, NULL, NULL, NULL, 30.00, 0.00, 0.00, 30.00, 'cash', 'paid', 'none', 0.00, '2026-07-29 10:21:42');
INSERT INTO `sales` VALUES (58, 1, 'INV-20260806-3417', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-06 17:23:09');
INSERT INTO `sales` VALUES (59, 1, 'INV-20260808-5081', 1, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'cash', 'paid', 'none', 0.00, '2026-08-08 02:07:55');
INSERT INTO `sales` VALUES (60, 1, 'INV-20260827-9655', 1, NULL, NULL, NULL, 30.00, 0.00, 0.00, 30.00, 'cash', 'paid', 'none', 0.00, '2026-08-27 14:13:30');
INSERT INTO `sales` VALUES (61, 1, 'INV-20260827-8626', 1, NULL, NULL, NULL, 30.00, 0.00, 0.00, 30.00, 'cash', 'paid', 'none', 0.00, '2026-08-27 14:14:43');
INSERT INTO `sales` VALUES (62, 1, 'INV-20260828-8719', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:11:08');
INSERT INTO `sales` VALUES (63, 1, 'INV-20260828-7050', 1, NULL, NULL, NULL, 440.00, 0.00, 0.00, 440.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:12:03');
INSERT INTO `sales` VALUES (64, 1, 'INV-20260828-6453', 1, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:13:32');
INSERT INTO `sales` VALUES (65, 1, 'INV-20260828-2770', 1, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:13:59');
INSERT INTO `sales` VALUES (66, 1, 'INV-20260828-7608', 1, NULL, NULL, NULL, 30.00, 0.00, 0.00, 30.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:18:24');
INSERT INTO `sales` VALUES (67, 1, 'INV-20260828-9974', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:19:05');
INSERT INTO `sales` VALUES (68, 1, 'INV-20260828-9941', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:20:32');
INSERT INTO `sales` VALUES (69, 1, 'INV-20260828-2682', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 01:27:48');
INSERT INTO `sales` VALUES (70, 1, 'INV-20260828-9470', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 02:04:45');
INSERT INTO `sales` VALUES (71, 1, 'INV-20260828-9888', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 02:06:12');
INSERT INTO `sales` VALUES (72, 1, 'INV-20260828-5429', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 02:13:55');
INSERT INTO `sales` VALUES (73, 1, 'INV-20260828-4729', 1, NULL, NULL, NULL, 10.00, 0.00, 0.00, 10.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 02:14:51');
INSERT INTO `sales` VALUES (74, 1, 'INV-20260828-1504', 1, NULL, NULL, NULL, 110.00, 0.00, 0.00, 110.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 03:38:41');
INSERT INTO `sales` VALUES (75, 1, 'INV-20260828-3847', 1, NULL, NULL, NULL, 110.00, 0.00, 0.00, 110.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 13:28:03');
INSERT INTO `sales` VALUES (76, 1, 'INV-20260828-5640', 1, NULL, NULL, NULL, 110.00, 0.00, 0.00, 110.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 13:32:00');
INSERT INTO `sales` VALUES (77, 1, 'INV-20260828-1883', 1, NULL, NULL, NULL, 440.00, 0.00, 0.00, 440.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 13:32:55');
INSERT INTO `sales` VALUES (78, 1, 'INV-20260828-5215', 1, NULL, NULL, NULL, 110.00, 0.00, 0.00, 110.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 13:43:22');
INSERT INTO `sales` VALUES (79, 1, 'INV-20260828-6464', 1, NULL, NULL, NULL, 110.00, 0.00, 0.00, 110.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 13:46:20');
INSERT INTO `sales` VALUES (80, 1, 'INV-20260828-7227', 1, NULL, NULL, NULL, 440.00, 0.00, 0.00, 440.00, 'cash', 'paid', 'none', 0.00, '2026-08-28 13:47:13');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES (1, 'store_name', 'LIO CO', '2026-07-23 14:19:23', '2026-08-28 13:45:47');
INSERT INTO `settings` VALUES (2, 'store_address', '123 Main Street, City, Country', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (3, 'store_phone', '+1234567890', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (4, 'store_email', 'info@mystore.com', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (5, 'currency_symbol', 'ل.س', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (6, 'tax_rate', '0.00', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (7, 'receipt_footer', 'Thank you for your business!', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (8, 'default_language', 'en', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (9, 'printer_type', 'network', '2026-07-23 14:19:23', '2026-07-26 13:06:02');
INSERT INTO `settings` VALUES (10, 'printer_connection', 'usb', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (11, 'printer_ip', '192.168.1.100', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (12, 'printer_port', '9100', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (13, 'printer_name', 'XP-80C', '2026-07-23 14:19:23', '2026-08-28 13:27:42');
INSERT INTO `settings` VALUES (14, 'receipt_copies', '1', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (15, 'expense_categories', 'Rent,Utilities,Salaries,Supplies,Maintenance,Marketing,Transportation,Insurance,Other', '2026-07-23 14:19:23', '2026-07-23 14:19:23');
INSERT INTO `settings` VALUES (16, 'auto_print', '1', '2026-07-23 14:19:23', '2026-07-28 15:04:30');
INSERT INTO `settings` VALUES (17, 'printer_bridge_path', 'C:\\POS\\SumatraPDF\\SumatraPDF.exe', '2026-07-23 14:19:23', '2026-08-28 13:43:06');
INSERT INTO `settings` VALUES (18, 'printer_method', 'pdf', '2026-07-28 13:59:29', '2026-08-28 13:27:42');

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of suppliers
-- ----------------------------
INSERT INTO `suppliers` VALUES (1, 'Fady Alchaar', '937764548', 'fady.alchaar@outlook.com', 'Syria/Latakia/Latakia City', 'Fady Alchaar', '', '2026-07-23 16:44:41');

-- ----------------------------
-- Table structure for transfer_items
-- ----------------------------
DROP TABLE IF EXISTS `transfer_items`;
CREATE TABLE `transfer_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `transfer_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10, 2) NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `transfer_id`(`transfer_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `transfer_items_ibfk_1` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `transfer_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transfer_items
-- ----------------------------

-- ----------------------------
-- Table structure for transfers
-- ----------------------------
DROP TABLE IF EXISTS `transfers`;
CREATE TABLE `transfers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `transfer_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_device_id` int UNSIGNED NOT NULL,
  `to_device_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `transfer_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'completed',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `transfer_no`(`transfer_no` ASC) USING BTREE,
  INDEX `from_device_id`(`from_device_id` ASC) USING BTREE,
  INDEX `to_device_id`(`to_device_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`from_device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `transfers_ibfk_2` FOREIGN KEY (`to_device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `transfers_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transfers
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','manager','cashier') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'cashier',
  `device_id` int UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `preferred_language` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'en',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  INDEX `device_id`(`device_id` ASC) USING BTREE,
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'admin', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1, '2026-07-23 14:19:23', 'ar');
INSERT INTO `users` VALUES (3, 'Administrator', '', 'admin@admin.com', '$2y$10$5JDAB.CBGQtkj9pmHKYbEeTXRMYQwug56.Wih2Ug/7lQ5NcoX8jz6', 'admin', NULL, 1, '2026-08-27 10:32:08', 'en');

SET FOREIGN_KEY_CHECKS = 1;
