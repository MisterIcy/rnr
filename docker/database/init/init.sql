-- Dumping structure for πίνακας rnr.stata
DROP TABLE IF EXISTS `stata`;
CREATE TABLE IF NOT EXISTS `stata`
(
    `id`   int(11)                             NOT NULL AUTO_INCREMENT,
    `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- Dumping data for table rnr.stata: ~3 rows (approximately)
/*!40000 ALTER TABLE `stata`
    DISABLE KEYS */;
INSERT INTO `stata` (`id`, `name`)
VALUES (1, 'Pending'),
       (2, 'Approved'),
       (3, 'Rejected');
/*!40000 ALTER TABLE `stata`
    ENABLE KEYS */;

-- Dumping structure for πίνακας rnr.user_types
DROP TABLE IF EXISTS `user_types`;
CREATE TABLE IF NOT EXISTS `user_types`
(
    `id`       int(11)                             NOT NULL AUTO_INCREMENT,
    `type`     varchar(64) COLLATE utf8_unicode_ci NOT NULL,
    `is_admin` tinyint(1)                          NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- Dumping data for table rnr.user_types: ~2 rows (approximately)
/*!40000 ALTER TABLE `user_types`
    DISABLE KEYS */;
INSERT INTO `user_types` (`id`, `type`, `is_admin`)
VALUES (1, 'User', 0),
       (2, 'Admin', 1);
/*!40000 ALTER TABLE `user_types`
    ENABLE KEYS */;

-- Dumping structure for πίνακας rnr.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`
(
    `id`          int(11)                              NOT NULL AUTO_INCREMENT,
    `given_name`  varchar(64) COLLATE utf8_unicode_ci  NOT NULL,
    `family_name` varchar(64) COLLATE utf8_unicode_ci  NOT NULL,
    `email`       varchar(120) COLLATE utf8_unicode_ci NOT NULL,
    `password`    varchar(120) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Argon2',
    `user_type`   int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `UK_USER_EMAIL` (`email`),
    KEY `FK_USER_TYPE` (`user_type`),
    CONSTRAINT `FK_USER_TYPE` FOREIGN KEY (`user_type`) REFERENCES `user_types` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 12
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- Dumping data for table rnr.users: ~11 rows (approximately)
/*!40000 ALTER TABLE `users`
    DISABLE KEYS */;
INSERT INTO `users` (`id`, `given_name`, `family_name`, `email`, `password`, `user_type`)
VALUES (1, 'Tzitzifiogkos', 'Mpampouras', 't.mpampouras@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$T1cwa2dpWmZDNmlrL2o0MQ$T+mIHrDTg9ySIrRfoR2LoDAMdXcVWZBXWvHJ/rFr0x0', 2),
       (2, 'Nikos', 'Koukos', 'n.koukos@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (3, 'Kostas', 'Kalantas', 'k.kalantas@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (4, 'Kostas', 'Kavourdidis', 'k.kavourdidis@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (5, 'Giorgos', 'Boukas', 'g.boukas@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (6, 'Simaioforos', 'Tripsidis', 's.trispidis@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (7, 'Babis', 'Sougias', 'b.sougias@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (8, 'Nikos', 'Matsablokos', 'n.matsablokos@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (9, 'Petros', 'Petras', 'p.petras@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (10, 'Giagkos', 'Baltas', 'g.baltas@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$U21uc0pQYjBjcGY2QXk1bg$3EJVEWr1WDWVRiwXj8Nsu9J90j2T/zFMsFr1jFirEN8', 1),
       (11, 'Nikos', 'Mpaltas', 'n.mpaltas@gavgav.gr',
        '$argon2id$v=19$m=65536,t=4,p=1$c2VSbzlZaWlWZ1NnRHVzOQ$uepjU9DHiSEQkVJMr8/2VDaTcaHC0l2DKm+fjA2f/Zc', 1);
/*!40000 ALTER TABLE `users`
    ENABLE KEYS */;

-- Dumping structure for πίνακας rnr.leaves
DROP TABLE IF EXISTS `leaves`;
CREATE TABLE IF NOT EXISTS `leaves`
(
    `id`           int(11)                      NOT NULL AUTO_INCREMENT,
    `user_id`      int(11)                      NOT NULL,
    `date_start`   date                         NOT NULL,
    `date_end`     date                         NOT NULL,
    `date_created` datetime DEFAULT NOW(),
    `date_modified` datetime DEFAULT NOW() ON UPDATE NOW(),
    `reason`       text COLLATE utf8_unicode_ci NOT NULL,
    `status`       int(11)  DEFAULT NULL,
    `approver`     int(11)  DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `FK_REQUESTOR` (`user_id`),
    KEY `FK_APPROVER` (`approver`),
    KEY `FK_STATUS` (`status`),
    CONSTRAINT `FK_APPROVER` FOREIGN KEY (`approver`) REFERENCES `users` (`id`),
    CONSTRAINT `FK_REQUESTOR` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
    CONSTRAINT `FK_STATUS` FOREIGN KEY (`status`) REFERENCES `stata` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;
