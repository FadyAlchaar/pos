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

 Date: 26/08/2026 23:51:03
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
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
