-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2015 at 03:50 PM
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
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `quiz_count` int(1) NOT NULL DEFAULT '0',
  `probationFlag` int(11) NOT NULL,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `phone_number` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `phone`, `quiz_count`, `probationFlag`) VALUES
(1, 'test', '+254720255774', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `probation`
--

CREATE TABLE IF NOT EXISTS `probation` (
  `probation_id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  `redeem_quest` varchar(255) NOT NULL,
  `probation_status` int(11) NOT NULL COMMENT 'locked=1,unlock=0',
  PRIMARY KEY (`probation_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `probation`
--

INSERT INTO `probation` (`probation_id`, `member_id`, `date_time`, `redeem_quest`, `probation_status`) VALUES
(1, 1, '2015-03-05 10:36:08', 'Google', 0);

-- --------------------------------------------------------

--
-- Table structure for table `quest_answer`
--

CREATE TABLE IF NOT EXISTS `quest_answer` (
  `quiz_id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(160) NOT NULL,
  `answer` varchar(50) NOT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `quest_answer`
--

INSERT INTO `quest_answer` (`quiz_id`, `question`, `answer`) VALUES
(1, 'what is my name?', 'nerd');

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
(1, 'codejam17', 'Google', 1),
(2, 'codejam01', 'SCICodeJam', 1);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE IF NOT EXISTS `submissions` (
  `submission_id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL,
  `quiz_id` int(10) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `submission_date` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT 'correct=1, wrong=0',
  PRIMARY KEY (`submission_id`),
  KEY `member_id` (`member_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `probation`
--
ALTER TABLE `probation`
  ADD CONSTRAINT `probation_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quest_answer` (`quiz_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
