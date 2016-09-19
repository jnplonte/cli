-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 25, 2016 at 07:23 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `iman_bankaccount`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) DEFAULT NULL,
  `overdraft` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `overdraft`, `timestamp`) VALUES
(9, 'jnpxxl', 0, '2016-05-25 03:46:52'),
(11, 'john', 1, '2016-05-25 03:48:39'),
(12, 'john p', 0, '2016-05-25 03:48:43'),
(13, 'john p', 0, '2016-05-25 03:58:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_balance`
--

CREATE TABLE IF NOT EXISTS `user_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `balance` float DEFAULT '0',
  `transaction_log` varchar(225) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user_balance`
--

INSERT INTO `user_balance` (`id`, `user_id`, `balance`, `transaction_log`, `lastupdate`) VALUES
(5, 9, 0, NULL, '2016-05-25 03:46:52'),
(7, 11, -1564.45, 'remove 2000 balance to account', '2016-05-25 05:47:57'),
(8, 12, 0, NULL, '2016-05-25 03:48:44'),
(9, 13, 0, NULL, '2016-05-25 03:58:11');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
