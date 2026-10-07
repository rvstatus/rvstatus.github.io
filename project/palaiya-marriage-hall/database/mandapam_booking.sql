/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50505
Source Host           : localhost:3307
Source Database       : mandapam_booking

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-10-07 07:01:23
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT '',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_flg` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of t_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `t_booking`
-- ----------------------------
DROP TABLE IF EXISTS `t_booking`;
CREATE TABLE `t_booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `number_of_guests` int(11) DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `booking_status` int(5) NOT NULL DEFAULT 0,
  `approve_by` varchar(50) DEFAULT NULL,
  `approve_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_flg` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of t_booking
-- ----------------------------

-- ----------------------------
-- Table structure for `t_customer`
-- ----------------------------
DROP TABLE IF EXISTS `t_customer`;
CREATE TABLE `t_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(150) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL DEFAULT '',
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_flg` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of t_customer
-- ----------------------------

-- ----------------------------
-- Table structure for `t_customer_message`
-- ----------------------------
DROP TABLE IF EXISTS `t_customer_message`;
CREATE TABLE `t_customer_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_flg` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of t_customer_message
-- ----------------------------
