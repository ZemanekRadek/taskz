-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `la_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `la_key` varchar(3) COLLATE utf8_czech_ci DEFAULT NULL,
  `la_name` int(10) unsigned NOT NULL,
  PRIMARY KEY (`la_ID`),
  KEY `la_name` (`la_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `localization`;
CREATE TABLE `localization` (
  `lo_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lo_CS` text COLLATE utf8_czech_ci,
  `lo_EN` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`lo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `pr_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pr_name` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`pr_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `st_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `st_title` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`st_ID`),
  KEY `st_title` (`st_title`),
  CONSTRAINT `states_ibfk_1` FOREIGN KEY (`st_title`) REFERENCES `localization` (`lo_ID`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


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


DROP TABLE IF EXISTS `users_group`;
CREATE TABLE `users_group` (
  `ug_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ug_name` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ug_ID`),
  KEY `ug_name` (`ug_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2015-04-30 10:25:17