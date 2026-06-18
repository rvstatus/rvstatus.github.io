/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50505
Source Host           : localhost:3307
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-06-18 10:51:50
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `pay_emp_trn_salary`
-- ----------------------------
DROP TABLE IF EXISTS `pay_emp_trn_salary`;
CREATE TABLE `pay_emp_trn_salary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(8) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `insentive` decimal(10,2) DEFAULT NULL,
  `PF` decimal(10,2) DEFAULT NULL,
  `ESI` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `NET_salary` decimal(10,2) DEFAULT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `created_date_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_date_time` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of pay_emp_trn_salary
-- ----------------------------
