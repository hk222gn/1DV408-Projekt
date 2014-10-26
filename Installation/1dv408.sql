-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 26 okt 2014 kl 19:49
-- Serverversion: 5.6.15-log
-- PHP-version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `1dv408`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `UserID` int(255) NOT NULL,
  `Name` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `MaxHealth` int(255) NOT NULL,
  `CurrentHealth` int(255) NOT NULL,
  `Attack` int(255) NOT NULL,
  `Defense` int(255) NOT NULL,
  `Level` int(255) NOT NULL,
  `Exp` int(255) NOT NULL,
  `Gold` int(255) NOT NULL,
  `StatPoints` int(255) NOT NULL,
  `WeaponEntry` int(11) DEFAULT '0',
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `characters`
--

INSERT INTO `characters` (`UserID`, `Name`, `MaxHealth`, `CurrentHealth`, `Attack`, `Defense`, `Level`, `Exp`, `Gold`, `StatPoints`, `WeaponEntry`) VALUES
(13, 'test', 25, 17, 10, 18, 14, 200, 14, 2, 5),
(9, 'a', 25, 17, 10, 18, 14, 200, 14, 2, 5),
(14, 'a', 25, 17, 10, 18, 14, 200, 14, 2, 5);

-- --------------------------------------------------------

--
-- Tabellstruktur `enemy`
--

CREATE TABLE IF NOT EXISTS `enemy` (
  `Entry` int(255) NOT NULL,
  `Name` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `MaxHealth` int(255) NOT NULL,
  `CurrentHealth` int(255) NOT NULL,
  `Attack` int(255) NOT NULL,
  `Defense` int(255) NOT NULL,
  `Level` int(255) NOT NULL,
  `ExpOnKill` int(255) NOT NULL,
  `GoldOnKill` int(255) NOT NULL,
  PRIMARY KEY (`Entry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `enemy`
--

INSERT INTO `enemy` (`Entry`, `Name`, `MaxHealth`, `CurrentHealth`, `Attack`, `Defense`, `Level`, `ExpOnKill`, `GoldOnKill`) VALUES
(0, 'Imp', 6, 6, 2, 1, 1, 10, 3),
(1, 'Ogre', 12, 12, 6, 0, 5, 30, 10),
(2, 'Moving Stone', 40, 40, 10, 6, 10, 50, 15),
(3, 'Possessed plant', 60, 60, 12, 8, 15, 60, 20),
(4, 'Diablo Clone', 100, 100, 15, 15, 20, 150, 50);

-- --------------------------------------------------------

--
-- Tabellstruktur `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `Entry` int(255) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `Password` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `TempPW` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `TempPWExpiration` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`Entry`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=15 ;

--
-- Dumpning av Data i tabell `user`
--

INSERT INTO `user` (`Entry`, `Name`, `Password`, `TempPW`, `TempPWExpiration`) VALUES
(14, 'aaaa', '123123', NULL, NULL),
(13, 'test', 'test12', NULL, NULL),
(12, 'otto', 'otto12', NULL, NULL),
(11, 'adminaa', 'password', NULL, NULL),
(10, 'admina', 'passsss', NULL, NULL),
(9, 'admin', 'password', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellstruktur `weapons`
--

CREATE TABLE IF NOT EXISTS `weapons` (
  `Entry` int(255) NOT NULL,
  `Name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `Attack` int(11) NOT NULL,
  `Defense` int(11) NOT NULL,
  `Health` int(11) NOT NULL,
  `LifeOnHit` int(11) NOT NULL,
  `Price` int(11) NOT NULL,
  PRIMARY KEY (`Entry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `weapons`
--

INSERT INTO `weapons` (`Entry`, `Name`, `Attack`, `Defense`, `Health`, `LifeOnHit`, `Price`) VALUES
(0, 'Copper Sword', 1, 0, 0, 0, 0),
(1, 'Silver Sword', 3, 0, 0, 0, 30),
(2, 'Golden Sword', 5, 0, 0, 0, 90),
(3, 'Platinum Sword', 8, 2, 0, 0, 150),
(4, 'Diamond Sword', 9, 1, 0, 0, 300),
(5, 'Grand Poking Stick', 12, 2, 0, 0, 666);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
