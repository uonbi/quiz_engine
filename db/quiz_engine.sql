-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2015 at 08:03 AM
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
(1, 10, '2015-03-07 05:18:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `phone`, `quiz_count`, `probationFlag`, `redeem_quest`, `probation_status`, `time`) VALUES
(10, 'denis', '+254725332343', 3, 3, 'kopokopo', 1, '2015-03-07 05:48:10'),
(12, '', '+254720255774', 4, 1, '', 0, '2015-03-07 06:29:57'),
(13, '', '+254700745702', 1, 2, '', 0, '2015-03-07 06:34:55');

-- --------------------------------------------------------

--
-- Table structure for table `quest_answer`
--

CREATE TABLE IF NOT EXISTS `quest_answer` (
  `quiz_id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(50) NOT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `quest_answer`
--

INSERT INTO `quest_answer` (`quiz_id`, `question`, `answer`) VALUES
(1, 'I was born in Kenya in 2008 to 4 parents. I am a swahili witness. What’s one of  my mother’s 3 letter name?\r\n(Reply using ''hunt<space> answer''', 'ory'),
(2, 'Only the Japanese knew how energetic and intelligent I am until some Kenyan women discovered this and adopted me. I now mentor and network my children. Who am I?\r\n(Reply using ''hunt<space>answer'')', 'akirachix'),
(3, 'Born and raised in Kenya, I am the fastest of my kind.I am a kenyan storekeeper resting in the skies. When i get formed, it pours. Who am I?\r\n(Reply using ''hunt<space>answer'')', 'angani'),
(4, 'Architects find me the cheapest option. I am what it takes to build strong foundations. When you take me to the remotest areas, just raise my head high I won’t fail.Who am I?\r\n(Reply using ''hunt<space>answer'')', 'brck'),
(5, 'I have sheltered my children to maturity. I am a happy parent. I crawled, I walked, now I run. Come celebrate, high five friends! Who am I?\r\n(Reply using ''hunt<space>answer'')', 'ihub'),
(6, 'I help you find anything about anything. You run to me to get all the answers. I am a mathematical representation. What is my C.E.O''s 2nd name?\r\n(Reply with ''hunt<space>answer'')', 'page');

-- --------------------------------------------------------

--
-- Table structure for table `redemptions`
--

CREATE TABLE IF NOT EXISTS `redemptions` (
  `redemption_id` int(11) NOT NULL AUTO_INCREMENT,
  `codejam` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  PRIMARY KEY (`redemption_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `redemptions`
--

INSERT INTO `redemptions` (`redemption_id`, `codejam`, `owner`) VALUES
(1, 'scicodejam7', 'angani'),
(2, 'scicodejam23', 'google'),
(3, 'scicodejam31', 'kopokopo');

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
