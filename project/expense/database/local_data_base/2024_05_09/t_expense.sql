/*
Navicat MySQL Data Transfer

Source Server         : dev
Source Server Version : 50505
Source Host           : localhost:3309
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-05-09 15:20:23
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `t_expense`
-- ----------------------------
DROP TABLE IF EXISTS `t_expense`;
CREATE TABLE `t_expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mason_name` varchar(50) NOT NULL,
  `working_date` date NOT NULL,
  `working_hours` varchar(10) NOT NULL,
  `working_category` int(11) NOT NULL,
  `working_type` int(11) NOT NULL,
  `salary` varchar(10) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_flg` int(11) NOT NULL DEFAULT '0',
  `deleted_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_expense
-- ----------------------------
