/*
Navicat MySQL Data Transfer

Source Server         : dev
Source Server Version : 50505
Source Host           : localhost:3309
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-05-09 15:20:03
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `m_emp`
-- ----------------------------
DROP TABLE IF EXISTS `m_emp`;
CREATE TABLE `m_emp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_name` varchar(50) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `gender` int(5) NOT NULL,
  `category_id` int(5) DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_emp
-- ----------------------------
INSERT INTO m_emp VALUES ('1', 'A B', 'M00001', '1', '1', 'RAGAV', '2024-05-09 08:45:36', null, null, '0');
INSERT INTO m_emp VALUES ('2', 'C D', 'W00002', '2', '3', 'RAGAV', '2024-05-09 08:46:09', null, null, '0');
