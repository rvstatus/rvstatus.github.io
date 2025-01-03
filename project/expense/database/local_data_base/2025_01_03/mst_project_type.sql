/*
Navicat MySQL Data Transfer

Source Server         : dev
Source Server Version : 50505
Source Host           : localhost:3309
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-01-03 09:29:16
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `mst_project_type`
-- ----------------------------
DROP TABLE IF EXISTS `mst_project_type`;
CREATE TABLE `mst_project_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_type_name` varchar(50) NOT NULL,
  `project_type_id` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mst_project_type
-- ----------------------------
INSERT INTO mst_project_type VALUES ('1', 'House One', 'P0001', 'Ragav', '2024-05-09 08:45:36', null, null, '0');
INSERT INTO mst_project_type VALUES ('2', 'House Two', 'P0002', 'Ragav', '2024-05-09 08:46:09', null, null, '0');
INSERT INTO mst_project_type VALUES ('3', 'Complex One', 'P0003', 'Ragav', '2024-05-09 11:04:16', null, null, '0');
INSERT INTO mst_project_type VALUES ('4', 'Hall', 'P0004', 'Ragav', '2024-05-09 11:04:36', null, null, '0');
