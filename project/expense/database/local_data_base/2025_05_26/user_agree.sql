SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `user_agree`
-- ----------------------------
DROP TABLE IF EXISTS `user_agree`;
CREATE TABLE `user_agree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `agree_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending,1=approved,2=rejected',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_agree_user` (`user_id`),
  CONSTRAINT `fk_user_agree_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;