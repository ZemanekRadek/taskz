-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 10. lis 2015, 09:07
-- Verze serveru: 5.6.21
-- Verze PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `taskz`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
`la_ID` int(10) unsigned NOT NULL,
  `la_key` varchar(3) COLLATE utf8_czech_ci DEFAULT NULL,
  `la_name` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `languages`
--

INSERT INTO `languages` (`la_ID`, `la_key`, `la_name`) VALUES
(1, 'cs', 0),
(2, 'en', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `localization`
--

CREATE TABLE IF NOT EXISTS `localization` (
`lo_ID` int(10) unsigned NOT NULL,
  `lo_CS` text COLLATE utf8_czech_ci,
  `lo_EN` text COLLATE utf8_czech_ci
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `localization`
--

INSERT INTO `localization` (`lo_ID`, `lo_CS`, `lo_EN`) VALUES
(1, 'Nový', 'New'),
(2, 'Přečtený', 'Read'),
(3, 'Rozpracovaný', 'In progress'),
(4, 'Hotový', 'Done'),
(14, 'test ke smazani', 'test ke smazani'),
(17, 'test 2', 'test 2'),
(18, 'muj stitek blablabala', 'muj stitek blablabala');

-- --------------------------------------------------------

--
-- Struktura tabulky `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
`pr_ID` int(10) unsigned NOT NULL,
  `pr_name` varchar(128) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `projects`
--

INSERT INTO `projects` (`pr_ID`, `pr_name`) VALUES
(1, 'test'),
(2, 'test2');

-- --------------------------------------------------------

--
-- Struktura tabulky `states`
--

CREATE TABLE IF NOT EXISTS `states` (
`st_ID` int(10) unsigned NOT NULL,
  `st_title` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `states`
--

INSERT INTO `states` (`st_ID`, `st_title`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Struktura tabulky `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
`tg_ID` int(10) unsigned NOT NULL,
  `tg_isSystem` tinyint(1) NOT NULL DEFAULT '0',
  `tg_business` int(10) unsigned DEFAULT NULL,
  `tg_title` int(10) unsigned DEFAULT NULL,
  `tg_created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `tags`
--

INSERT INTO `tags` (`tg_ID`, `tg_isSystem`, `tg_business`, `tg_title`, `tg_created`) VALUES
(6, 0, NULL, 14, '2015-04-28 17:54:06'),
(9, 0, NULL, 17, '2015-04-28 17:56:19'),
(10, 0, NULL, 18, '2015-04-29 13:47:26');

-- --------------------------------------------------------

--
-- Struktura tabulky `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
`ta_ID` int(10) unsigned NOT NULL,
  `ta_name` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `ta_timeTo` timestamp NULL DEFAULT NULL,
  `ta_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `ta_author` int(10) unsigned DEFAULT NULL,
  `ta_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ta_description` text COLLATE utf8_czech_ci
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `tasks`
--

INSERT INTO `tasks` (`ta_ID`, `ta_name`, `ta_timeTo`, `ta_urgent`, `ta_author`, `ta_created`, `ta_description`) VALUES
(1, 'testovaci', '2015-11-29 23:00:00', 0, 42, '2015-11-03 23:00:00', 'tester ');

-- --------------------------------------------------------

--
-- Struktura tabulky `tasks_list`
--

CREATE TABLE IF NOT EXISTS `tasks_list` (
`tl_ID` int(10) unsigned NOT NULL,
  `tl_name` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `tl_userID` int(10) unsigned NOT NULL,
  `tl_inserted` timestamp NULL DEFAULT NULL,
  `tl_ico` varchar(32) COLLATE utf8_czech_ci DEFAULT NULL,
  `tl_order` int(10) unsigned DEFAULT NULL,
  `tl_systemIdentifier` varchar(32) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `tasks_list`
--

INSERT INTO `tasks_list` (`tl_ID`, `tl_name`, `tl_userID`, `tl_inserted`, `tl_ico`, `tl_order`, `tl_systemIdentifier`) VALUES
(1, 'Inbox', 45, '2015-11-09 23:00:00', 'ico-inbox', 1, 'inbox'),
(2, 'Urgent', 45, '2015-11-09 23:00:00', 'ico-urgent', 2, 'urgent'),
(3, 'Finished', 45, '2015-11-09 23:00:00', 'ico-finished', 3, 'finished');

-- --------------------------------------------------------

--
-- Struktura tabulky `tasks_list_user`
--

CREATE TABLE IF NOT EXISTS `tasks_list_user` (
  `tlu_tl_ID` int(10) unsigned DEFAULT NULL,
  `tlu_us_ID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `tasks_list_user`
--

INSERT INTO `tasks_list_user` (`tlu_tl_ID`, `tlu_us_ID`) VALUES
(1, 45),
(2, 45),
(3, 45);

-- --------------------------------------------------------

--
-- Struktura tabulky `tasks_user`
--

CREATE TABLE IF NOT EXISTS `tasks_user` (
  `ta_ID` int(10) unsigned DEFAULT NULL,
  `us_ID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`us_ID` int(10) unsigned NOT NULL,
  `us_email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_name` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_surname` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_password` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL,
  `us_groupID` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`us_ID`, `us_email`, `us_name`, `us_surname`, `us_password`, `us_groupID`) VALUES
(42, 'tester@corben.cz', 'František', NULL, 'dd67c1301ef2fb02b99d4b6fea627644debfae24', 1),
(43, 'info@corben.cz', 'Katka', NULL, 'dd67c1301ef2fb02b99d4b6fea627644debfae24', 1),
(44, 'info2222@corben.cz', 'xcvzxdvxcd', NULL, 'dd67c1301ef2fb02b99d4b6fea627644debfae24', 1),
(45, 'tester2@corben.cz', 'tester', NULL, 'dd67c1301ef2fb02b99d4b6fea627644debfae24', 1),
(46, 'zemanek.radek@gmail.com', 'tester', NULL, 'dd67c1301ef2fb02b99d4b6fea627644debfae24', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
`ug_ID` int(10) unsigned NOT NULL,
  `ug_name` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `languages`
--
ALTER TABLE `languages`
 ADD PRIMARY KEY (`la_ID`), ADD KEY `la_name` (`la_name`);

--
-- Klíče pro tabulku `localization`
--
ALTER TABLE `localization`
 ADD PRIMARY KEY (`lo_ID`);

--
-- Klíče pro tabulku `projects`
--
ALTER TABLE `projects`
 ADD PRIMARY KEY (`pr_ID`);

--
-- Klíče pro tabulku `states`
--
ALTER TABLE `states`
 ADD PRIMARY KEY (`st_ID`), ADD KEY `st_title` (`st_title`);

--
-- Klíče pro tabulku `tags`
--
ALTER TABLE `tags`
 ADD PRIMARY KEY (`tg_ID`), ADD KEY `tg_title` (`tg_title`);

--
-- Klíče pro tabulku `tasks`
--
ALTER TABLE `tasks`
 ADD PRIMARY KEY (`ta_ID`), ADD KEY `ta_author` (`ta_author`);

--
-- Klíče pro tabulku `tasks_list`
--
ALTER TABLE `tasks_list`
 ADD PRIMARY KEY (`tl_ID`), ADD KEY `tl_userID` (`tl_userID`), ADD KEY `tl_systemIdentifier` (`tl_systemIdentifier`);

--
-- Klíče pro tabulku `tasks_list_user`
--
ALTER TABLE `tasks_list_user`
 ADD KEY `tlu_us_ID` (`tlu_us_ID`), ADD KEY `tlu_tl_ID` (`tlu_tl_ID`);

--
-- Klíče pro tabulku `tasks_user`
--
ALTER TABLE `tasks_user`
 ADD KEY `ta_ID` (`ta_ID`), ADD KEY `us_ID` (`us_ID`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`us_ID`), ADD KEY `us_groupID` (`us_groupID`);

--
-- Klíče pro tabulku `users_group`
--
ALTER TABLE `users_group`
 ADD PRIMARY KEY (`ug_ID`), ADD KEY `ug_name` (`ug_name`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `languages`
--
ALTER TABLE `languages`
MODIFY `la_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pro tabulku `localization`
--
ALTER TABLE `localization`
MODIFY `lo_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pro tabulku `projects`
--
ALTER TABLE `projects`
MODIFY `pr_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pro tabulku `states`
--
ALTER TABLE `states`
MODIFY `st_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `tags`
--
ALTER TABLE `tags`
MODIFY `tg_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pro tabulku `tasks`
--
ALTER TABLE `tasks`
MODIFY `ta_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pro tabulku `tasks_list`
--
ALTER TABLE `tasks_list`
MODIFY `tl_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
MODIFY `us_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT pro tabulku `users_group`
--
ALTER TABLE `users_group`
MODIFY `ug_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `states`
--
ALTER TABLE `states`
ADD CONSTRAINT `states_ibfk_1` FOREIGN KEY (`st_title`) REFERENCES `localization` (`lo_ID`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `tags`
--
ALTER TABLE `tags`
ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`tg_title`) REFERENCES `localization` (`lo_ID`) ON DELETE SET NULL;

--
-- Omezení pro tabulku `tasks`
--
ALTER TABLE `tasks`
ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`ta_author`) REFERENCES `users` (`us_ID`) ON DELETE SET NULL;

--
-- Omezení pro tabulku `tasks_list_user`
--
ALTER TABLE `tasks_list_user`
ADD CONSTRAINT `tasks_list_user_ibfk_1` FOREIGN KEY (`tlu_tl_ID`) REFERENCES `tasks_list` (`tl_ID`) ON DELETE SET NULL ON UPDATE NO ACTION,
ADD CONSTRAINT `tasks_list_user_ibfk_2` FOREIGN KEY (`tlu_us_ID`) REFERENCES `users` (`us_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `tasks_user`
--
ALTER TABLE `tasks_user`
ADD CONSTRAINT `tasks_user_ibfk_1` FOREIGN KEY (`ta_ID`) REFERENCES `tasks` (`ta_ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `tasks_user_ibfk_2` FOREIGN KEY (`us_ID`) REFERENCES `users` (`us_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
