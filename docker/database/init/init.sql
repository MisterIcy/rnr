CREATE TABLE `stata` (
                         `id` INT(11) NOT NULL AUTO_INCREMENT,
                         `name` VARCHAR(64) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
                         PRIMARY KEY (`id`) USING BTREE
)
    COLLATE='utf8_unicode_ci'
    ENGINE=InnoDB;


CREATE TABLE `user_types` (
                              `id` INT(11) NOT NULL AUTO_INCREMENT,
                              `type` VARCHAR(64) NOT NULL COLLATE 'utf8_unicode_ci',
                              `is_admin` TINYINT(1) NOT NULL,
                              PRIMARY KEY (`id`) USING BTREE
)
    COLLATE='utf8_unicode_ci'
    ENGINE=InnoDB;

CREATE TABLE `users` (
                         `id` INT(11) NOT NULL AUTO_INCREMENT,
                         `given_name` VARCHAR(64) NOT NULL COLLATE 'utf8_unicode_ci',
                         `family_name` VARCHAR(64) NOT NULL COLLATE 'utf8_unicode_ci',
                         `email` VARCHAR(120) NOT NULL COLLATE 'utf8_unicode_ci',
                         `password` VARCHAR(120) NOT NULL COMMENT 'Argon2' COLLATE 'utf8_unicode_ci',
                         `user_type` INT(11) NULL DEFAULT NULL,
                         PRIMARY KEY (`id`) USING BTREE,
                         UNIQUE INDEX `UK_USER_EMAIL` (`email`) USING BTREE,
                         INDEX `FK_USER_TYPE` (`user_type`) USING BTREE,
                         CONSTRAINT `FK_USER_TYPE` FOREIGN KEY (`user_type`) REFERENCES `rnr`.`user_types` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
    COLLATE='utf8_unicode_ci'
    ENGINE=InnoDB;

CREATE TABLE `leaves` (
                          `id` INT(11) NOT NULL AUTO_INCREMENT,
                          `user_id` INT(11) NOT NULL,
                          `date_start` DATE NOT NULL,
                          `date_end` DATE NOT NULL,
                          `date_created` DATETIME NOT NULL DEFAULT current_timestamp(),
                          `date_updated` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                          `reason` TEXT NOT NULL COLLATE 'utf8_unicode_ci',
                          `status` INT(11) NULL DEFAULT NULL,
                          `approver` INT(11) NULL DEFAULT NULL,
                          PRIMARY KEY (`id`) USING BTREE,
                          INDEX `FK_REQUESTOR` (`user_id`) USING BTREE,
                          INDEX `FK_APPROVER` (`approver`) USING BTREE,
                          INDEX `FK_STATUS` (`status`) USING BTREE,
                          CONSTRAINT `FK_APPROVER` FOREIGN KEY (`approver`) REFERENCES `rnr`.`users` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
                          CONSTRAINT `FK_REQUESTOR` FOREIGN KEY (`user_id`) REFERENCES `rnr`.`users` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
                          CONSTRAINT `FK_STATUS` FOREIGN KEY (`status`) REFERENCES `rnr`.`stata` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
    COLLATE='utf8_unicode_ci'
    ENGINE=InnoDB;
