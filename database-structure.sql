-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2019 at 11:11 PM
-- Server version: 5.5.60-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wow`
--
CREATE DATABASE IF NOT EXISTS `wow` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wow`;

-- --------------------------------------------------------

--
-- Table structure for table `char-feed`
--

DROP TABLE IF EXISTS `char-feed`;
CREATE TABLE IF NOT EXISTS `char-feed` (
  `pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pk_char` int(10) unsigned NOT NULL,
  `ts_timestamp` int(10) unsigned NOT NULL,
  `ts_text` char(32) DEFAULT NULL,
  `feed_data` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `pk_char` (`pk_char`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `id2char`
--

DROP TABLE IF EXISTS `id2char`;
CREATE TABLE IF NOT EXISTS `id2char` (
  `pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `toon_id` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `toon_server` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `toon_char` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `last-online`
--

DROP TABLE IF EXISTS `last-online`;
CREATE TABLE IF NOT EXISTS `last-online` (
  `pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pk_char` int(10) unsigned NOT NULL DEFAULT '0',
  `ts_timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `ts_text` char(32) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `pk_char` (`pk_char`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
