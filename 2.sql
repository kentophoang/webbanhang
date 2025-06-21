USE my_store;

UPDATE `account` 
SET `role` = 'admin' 
WHERE `username` = 'admin';

ALTER TABLE `orders` 
ADD COLUMN `user_id` INT NULL DEFAULT NULL AFTER `id`,
ADD INDEX `fk_orders_account_idx` (`user_id` ASC);

ALTER TABLE `orders` 
ADD CONSTRAINT `fk_orders_account`
  FOREIGN KEY (`user_id`)
  REFERENCES `account` (`id`)
  ON DELETE SET NULL
  ON UPDATE NO ACTION;
