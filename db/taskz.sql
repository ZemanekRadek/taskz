-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `la_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `la_key` varchar(3) COLLATE utf8_czech_ci DEFAULT NULL,
  `la_name` int(10) unsigned NOT NULL,
  PRIMARY KEY (`la_ID`),
  KEY `la_name` (`la_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `languages` (`la_ID`, `la_key`, `la_name`) VALUES
(1,	'cs',	0),
(2,	'en',	0);

DROP TABLE IF EXISTS `localization`;
CREATE TABLE `localization` (
  `lo_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lo_CS` text COLLATE utf8_czech_ci,
  `lo_EN` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`lo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `localization` (`lo_ID`, `lo_CS`, `lo_EN`) VALUES
(1,	'Nový',	'New'),
(2,	'Přečtený',	'Read'),
(3,	'Rozpracovaný',	'In progress'),
(4,	'Hotový',	'Done'),
(14,	'test ke smazani',	'test ke smazani'),
(17,	'test 2',	'test 2'),
(18,	'muj stitek blablabala',	'muj stitek blablabala');

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `pr_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pr_name` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`pr_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `projects` (`pr_ID`, `pr_name`) VALUES
(1,	'test'),
(2,	'test2');

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `st_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `st_title` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`st_ID`),
  KEY `st_title` (`st_title`),
  CONSTRAINT `states_ibfk_1` FOREIGN KEY (`st_title`) REFERENCES `localization` (`lo_ID`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `states` (`st_ID`, `st_title`) VALUES
(1,	1),
(2,	2),
(3,	3),
(4,	4);

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tg_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tg_isSystem` tinyint(1) NOT NULL DEFAULT '0',
  `tg_business` int(10) unsigned DEFAULT NULL,
  `tg_title` int(10) unsigned DEFAULT NULL,
  `tg_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tg_ID`),
  KEY `tg_title` (`tg_title`),
  CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`tg_title`) REFERENCES `localization` (`lo_ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `tags` (`tg_ID`, `tg_isSystem`, `tg_business`, `tg_title`, `tg_created`) VALUES
(6,	0,	NULL,	14,	'2015-04-28 17:54:06'),
(9,	0,	NULL,	17,	'2015-04-28 17:56:19'),
(10,	0,	NULL,	18,	'2015-04-29 13:47:26');

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `ta_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ta_name` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `ta_timeTo` timestamp NULL DEFAULT NULL,
  `ta_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `ta_author` int(10) unsigned DEFAULT NULL,
  `ta_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ta_description` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`ta_ID`),
  KEY `ta_author` (`ta_author`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`ta_author`) REFERENCES `users` (`us_ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `us_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `us_email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_name` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_surname` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_password` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_groupID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`us_ID`),
  KEY `us_groupID` (`us_groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `users` (`us_ID`, `us_email`, `us_name`, `us_surname`, `us_password`, `us_groupID`) VALUES
(42,	'tester@corben.cz',	'František',	NULL,	'dd67c1301ef2fb02b99d4b6fea627644debfae24',	1),
(43,	'info@corben.cz',	'Katka',	NULL,	'dd67c1301ef2fb02b99d4b6fea627644debfae24',	1),
(44,	'info2222@corben.cz',	'xcvzxdvxcd',	NULL,	'dd67c1301ef2fb02b99d4b6fea627644debfae24',	1);

DROP TABLE IF EXISTS `users_group`;
CREATE TABLE `users_group` (
  `ug_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ug_name` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ug_ID`),
  KEY `ug_name` (`ug_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2015-04-30 11:45:04
