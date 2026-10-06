/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50505
Source Host           : localhost:3307
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-06-18 10:51:29
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `pay_payslip_details`
-- ----------------------------
DROP TABLE IF EXISTS `pay_payslip_details`;
CREATE TABLE `pay_payslip_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(8) NOT NULL,
  `salary_id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `to_mail_address` varchar(255) DEFAULT NULL,
  `cc_mail_address` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `mail_send_tatus` tinyint(4) DEFAULT NULL,
  `created_date_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_date_time` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of pay_payslip_details
-- ----------------------------
