/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50505
Source Host           : localhost:3307
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-07-31 16:32:04
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `mst_expense_type`
-- ----------------------------
DROP TABLE IF EXISTS `mst_expense_type`;
CREATE TABLE `mst_expense_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_type_name` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_flg` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of mst_expense_type
-- ----------------------------
INSERT INTO mst_expense_type VALUES ('1', 'Material', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('2', 'Transport', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('3', 'Machinery', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('4', 'Fuel', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('5', 'Food', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('6', 'Electricity', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('7', 'Water', 'admin', '2026-07-29 18:15:34', null, null, '0');
INSERT INTO mst_expense_type VALUES ('8', 'Miscellaneous', 'admin', '2026-07-29 18:15:34', null, null, '0');
