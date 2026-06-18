/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50505
Source Host           : localhost:3307
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2026-06-18 10:51:38
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `pay_mst_ps_emp`
-- ----------------------------
DROP TABLE IF EXISTS `pay_mst_ps_emp`;
CREATE TABLE `pay_mst_ps_emp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(8) NOT NULL,
  `del_flg` int(1) DEFAULT 0,
  `resign_id` int(1) NOT NULL,
  `title` int(1) DEFAULT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `create_by` varchar(50) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  `update_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of pay_mst_ps_emp
-- ----------------------------
