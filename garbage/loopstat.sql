-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 03 Janvier 2015 à 16:51
-- Version du serveur: 5.5.38-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `loopstat`
--

-- --------------------------------------------------------

--
-- Structure de la table `anim_count`
--

CREATE TABLE IF NOT EXISTS `anim_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anim_name` varchar(255) NOT NULL,
  `total_play_count` bigint(20) unsigned NOT NULL,
  `first_record_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anim_name` (`anim_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `hourly_play_count`
--

CREATE TABLE IF NOT EXISTS `hourly_play_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anim_count_id` int(10) unsigned NOT NULL,
  `hourly_count` int(10) unsigned NOT NULL,
  `record_date` datetime NOT NULL,
  `machines` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anim_count_id` (`anim_count_id`),
  KEY `record_date` (`record_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Structure de la table `keys`
--

CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nim` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `caisse_num` tinyint(3) unsigned NOT NULL,
  `key_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `key_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1361 ;

-- --------------------------------------------------------

--
-- Structure de la table `log_errors`
--

CREATE TABLE IF NOT EXISTS `log_errors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `random_key` varchar(255) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `caisse_num` tinyint(3) unsigned DEFAULT NULL,
  `key_date` datetime NOT NULL,
  `key_value` varchar(255) NOT NULL,
  `post_obj` text NOT NULL,
  `object_data` text NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `record_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `machine_identification`
--

CREATE TABLE IF NOT EXISTS `machine_identification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nim` varchar(255) NOT NULL,
  `caisse_num` tinyint(3) unsigned NOT NULL,
  `cn_fix_id` varchar(255) NOT NULL,
  `record_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=711 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
