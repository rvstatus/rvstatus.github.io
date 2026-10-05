/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50505
Source Host           : localhost:3307
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-07-31 16:31:51
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `mst_expense_name`
-- ----------------------------
DROP TABLE IF EXISTS `mst_expense_name`;
CREATE TABLE `mst_expense_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_type_id` int(11) NOT NULL,
  `expense_name` varchar(150) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_flg` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of mst_expense_name
-- ----------------------------
INSERT INTO mst_expense_name VALUES ('1', '1', 'Cement', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('2', '1', 'Steel', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('3', '1', 'Blue Metal', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('4', '1', 'Sand', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('5', '2', 'Lorry Rent', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('6', '2', 'Auto Charges', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('7', '3', 'JCB Rent', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('8', '3', 'Crane Rent', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('9', '4', 'Diesel', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('10', '4', 'Petrol', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('11', '5', 'Breakfast', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('12', '5', 'Lunch', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('13', '6', 'EB Bill', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('14', '7', 'Water Can', 'admin', '2026-07-31 08:52:45', null, null, '0');
INSERT INTO mst_expense_name VALUES ('15', '8', 'Office Expense', 'admin', '2026-07-31 08:52:45', null, null, '0');
