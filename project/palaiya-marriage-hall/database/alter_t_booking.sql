ALTER TABLE `t_booking`
  ADD COLUMN `mail_send_details_id` INT(11) DEFAULT NULL
  AFTER `approve_at`;