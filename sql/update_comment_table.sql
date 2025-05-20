
-- Add the new columns to the comment table for ticket status management

ALTER TABLE `comment` 
ADD COLUMN `is_in_progress` TINYINT(1) DEFAULT 0 AFTER `is_moderated`,
ADD COLUMN `is_completed` TINYINT(1) DEFAULT 0 AFTER `is_in_progress`;