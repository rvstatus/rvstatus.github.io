/*
Navicat MySQL Data Transfer

Source Server         : dev
Source Server Version : 50505
Source Host           : localhost:3309
Source Database       : dev_exp

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-05-09 15:19:26
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `mst_work_category`
-- ----------------------------
DROP TABLE IF EXISTS `mst_work_category`;
CREATE TABLE `mst_work_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_category_name` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mst_work_category
-- ----------------------------
INSERT INTO mst_work_category VALUES ('1', 'Mason', 'RAGAV', '2024-05-09 08:45:36', null, null, '0');
INSERT INTO mst_work_category VALUES ('2', 'Siththal', 'RAGAV', '2024-05-09 08:46:09', null, null, '0');
INSERT INTO mst_work_category VALUES ('3', 'Labur', 'RAGAV', '2024-05-09 11:04:16', null, null, '0');
