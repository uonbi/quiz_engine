-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2015 at 12:02 AM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quiz_engine`
--

-- --------------------------------------------------------

--
-- Table structure for table `airtime_winners`
--

CREATE TABLE IF NOT EXISTS `airtime_winners` (
  `entry_id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores entries of users who get the first five questions correct' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `airtime_winners`
--

INSERT INTO `airtime_winners` (`entry_id`, `member_id`, `date_time`) VALUES
(1, 1, '2015-03-07 00:00:00'),
(2, 2, '2015-03-07 02:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `quiz_count` int(1) NOT NULL DEFAULT '0',
  `probationFlag` int(11) NOT NULL,
  `redeem_quest` varchar(255) NOT NULL,
  `probation_status` int(1) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `phone_number` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `phone`, `quiz_count`, `probationFlag`, `redeem_quest`, `probation_status`, `time`) VALUES
(1, '', '+254720255774', 1, 0, '', 0, '2015-03-06 22:02:14'),
(2, '', '+254725332343', 1, 0, '', 0, '2015-03-06 22:53:34'),
(3, '', '+254728590438', 1, 0, '', 0, '2015-03-06 23:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `quest_answer`
--

CREATE TABLE IF NOT EXISTS `quest_answer` (
  `quiz_id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(160) NOT NULL,
  `answer` varchar(50) NOT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `quest_answer`
--

INSERT INTO `quest_answer` (`quiz_id`, `question`, `answer`) VALUES
(1, 'what is my name?', 'nerd'),
(2, 'What is the first name of Facebook founder?', 'mark'),
(3, 'What is the second name of the Kenyan President?', 'muigai');

-- --------------------------------------------------------

--
-- Table structure for table `redemptions`
--

CREATE TABLE IF NOT EXISTS `redemptions` (
  `redemption_id` int(11) NOT NULL AUTO_INCREMENT,
  `codejam_code` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`redemption_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `redemptions`
--

INSERT INTO `redemptions` (`redemption_id`, `codejam_code`, `owner`, `member_id`) VALUES
(1, 'codejam17', 'google', 1),
(2, 'codejam01', 'scicodejam', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `airtime_winners`
--
ALTER TABLE `airtime_winners`
  ADD CONSTRAINT `airtime_winners_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
