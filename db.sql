-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 12, 2015 at 11:01 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cashier`
--
CREATE DATABASE `cashier` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cashier`;

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `col_id` int(11) NOT NULL auto_increment,
  `date` char(15) NOT NULL,
  `receipt_num` varchar(20) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `sched_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `sy` varchar(15) NOT NULL,
  `semester` varchar(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remark` varchar(11) NOT NULL,
  PRIMARY KEY  (`col_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`col_id`, `date`, `receipt_num`, `stud_id`, `sched_id`, `amount`, `sy`, `semester`, `user_id`, `remark`) VALUES
(1, '02/11/2015', '330', 1, 184, 10.00, '2014-2015', 'I', 5, '0'),
(2, '02/11/2015', '330', 1, 190, 20.00, '2014-2015', 'I', 5, '0'),
(3, '02/11/2015', '330', 1, 196, 20.00, '2014-2015', 'I', 5, '0'),
(4, '02/11/2015', '330', 1, 202, 20.00, '2014-2015', 'I', 5, '0'),
(5, '02/11/2015', '330', 1, 208, 20.00, '2014-2015', 'I', 5, '0'),
(6, '02/11/2015', '330', 1, 214, 20.00, '2014-2015', 'I', 5, '0'),
(7, '02/11/2015', '330', 1, 220, 20.00, '2014-2015', 'I', 5, '0'),
(8, '02/11/2015', '330', 1, 226, 20.00, '2014-2015', 'I', 5, '0'),
(9, '02/11/2015', '330', 1, 232, 20.00, '2014-2015', 'I', 5, '0'),
(10, '02/11/2015', '330', 1, 238, 20.00, '2014-2015', 'I', 5, '0'),
(11, '02/11/2015', '330', 1, 244, 20.00, '2014-2015', 'I', 5, '0'),
(12, '02/11/2015', '330', 1, 250, 20.00, '2014-2015', 'I', 5, '0'),
(13, '02/11/2015', '330', 1, 256, 20.00, '2014-2015', 'I', 5, '0'),
(14, '02/11/2015', '330', 1, 262, 20.00, '2014-2015', 'I', 5, '0'),
(15, '02/11/2015', '330', 1, 268, 20.00, '2014-2015', 'I', 5, '0'),
(16, '02/11/2015', '330', 1, 274, 20.00, '2014-2015', 'I', 5, '0'),
(17, '02/11/2015', '330', 1, 280, 20.00, '2014-2015', 'I', 5, '0'),
(18, '02/12/2015', '1000', 2, 184, 113.00, '2005-2006', 'II', 6, '0'),
(19, '02/12/2015', '1000', 2, 196, 211.00, '2005-2006', 'II', 6, '0'),
(20, '02/12/2015', '1000', 2, 202, 103.00, '2005-2006', 'II', 6, '0'),
(21, '02/12/2015', '1000', 2, 208, 3.00, '2005-2006', 'II', 6, '0'),
(22, '02/12/2015', '1000', 2, 214, 103.00, '2005-2006', 'II', 6, '0'),
(23, '02/12/2015', '1000', 2, 220, 103.00, '2005-2006', 'II', 6, '0'),
(24, '02/12/2015', '1000', 2, 226, 364.00, '2005-2006', 'II', 6, '0'),
(25, '02/12/2015', '4000', 2, 23, 123.00, '2005-2006', 'II', 6, 'Canceled'),
(26, '02/12/2015', '4000', 2, 29, 123.00, '2005-2006', 'II', 6, 'Canceled'),
(27, '02/12/2015', '4000', 2, 35, 13.00, '2005-2006', 'II', 6, 'Canceled'),
(28, '02/12/2015', '4000', 2, 41, 123.00, '2005-2006', 'II', 6, 'Canceled'),
(29, '02/12/2015', '4000', 2, 47, 133.00, '2005-2006', 'II', 6, 'Canceled'),
(30, '02/12/2015', '4000', 2, 53, 213.00, '2005-2006', 'II', 6, 'Canceled'),
(31, '02/12/2015', '4000', 2, 59, 213.00, '2005-2006', 'II', 6, 'Canceled'),
(32, '02/12/2015', '4000', 2, 65, 123.00, '2005-2006', 'II', 6, 'Canceled'),
(33, '02/12/2015', '4000', 2, 71, 2936.00, '2005-2006', 'II', 6, 'Canceled');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_id` int(11) NOT NULL auto_increment,
  `description` varchar(50) NOT NULL,
  `acronym` varchar(20) NOT NULL,
  `dept_id` int(11) NOT NULL,
  PRIMARY KEY  (`course_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `description`, `acronym`, `dept_id`) VALUES
(1, 'Bachelor of Science in Information Technology', 'BSINT', 1),
(3, 'AMDNA', 'AMDNA', 1),
(4, 'MIDWIFERY', 'MIDWIFERY', 1),
(5, 'Bacheor of Elementary Education', 'BEED', 2),
(8, 'ACCTG', 'ACCTG', 3),
(93, 'asdf', 'asf', 5);

-- --------------------------------------------------------

--
-- Table structure for table `daily_deposit`
--

CREATE TABLE IF NOT EXISTS `daily_deposit` (
  `date` varchar(15) NOT NULL,
  `tui_amount` float NOT NULL,
  `misc_amount` float(11,2) NOT NULL,
  `tf_amount` float(11,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `daily_deposit`
--

INSERT INTO `daily_deposit` (`date`, `tui_amount`, `misc_amount`, `tf_amount`) VALUES
('02/11/2015', 0, 0.00, 23.00),
('02/12/2015', 0, 0.00, 123.00);

-- --------------------------------------------------------

--
-- Table structure for table `dailyreport`
--

CREATE TABLE IF NOT EXISTS `dailyreport` (
  `daily_r_id` int(11) NOT NULL auto_increment,
  `date` varchar(10) NOT NULL,
  `sy` char(10) NOT NULL,
  `or` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `tuition` char(20) NOT NULL,
  `t_amount` int(11) NOT NULL,
  `misc` char(20) NOT NULL,
  `misc_amount` int(11) NOT NULL,
  `yearbook` char(10) NOT NULL,
  `y_amount` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `undeposit` int(11) NOT NULL,
  PRIMARY KEY  (`daily_r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dailyreport`
--


-- --------------------------------------------------------

--
-- Table structure for table `date`
--

CREATE TABLE IF NOT EXISTS `date` (
  `date` varchar(123) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `date`
--

INSERT INTO `date` (`date`, `id`) VALUES
('2015/01/01', 1),
('2015/01/07', 2),
('2015/01/09', 3),
('2015/01/13', 4),
('2015/01/20', 5),
('2015/01/21', 6);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `dept_id` int(11) NOT NULL auto_increment,
  `description` varchar(50) NOT NULL,
  `acronym` varchar(20) NOT NULL,
  PRIMARY KEY  (`dept_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `description`, `acronym`) VALUES
(1, 'College of Arts And Sciences', 'CAS'),
(2, 'College of Education', 'CED'),
(3, 'College of Business Administration', 'CBA'),
(4, 'College of industrial Technology', 'CIT'),
(5, 'College of Forestry and Fishery', 'CAF'),
(21, 'deptname', 'newdept');

-- --------------------------------------------------------

--
-- Table structure for table `exceeded_money`
--

CREATE TABLE IF NOT EXISTS `exceeded_money` (
  `ref_id` int(11) NOT NULL auto_increment,
  `stud_id` int(11) NOT NULL,
  `receipt_num` varchar(20) NOT NULL,
  `amount` float NOT NULL,
  `from_sy` char(12) NOT NULL,
  `from_semester` char(2) NOT NULL,
  `to_sy` varchar(12) NOT NULL,
  `to_semester` char(2) NOT NULL,
  `action` char(20) NOT NULL,
  PRIMARY KEY  (`ref_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `exceeded_money`
--


-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `note_id` int(11) NOT NULL,
  `stud_id` int(255) NOT NULL,
  `sy` varchar(15) NOT NULL,
  `semester` char(2) NOT NULL,
  `note` varchar(255) NOT NULL,
  `date` varchar(15) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`note_id`, `stud_id`, `sy`, `semester`, `note`, `date`, `user_id`) VALUES
(0, 1, '2014-2015', 'I', 'niote askfjsaddkfjh', '02/02/2015', 1),
(0, 1, '2014-2015', 'I', 'asfsdf', '02/02/2015', 1),
(0, 1, '2014-2015', 'I', 'sc', '02/05/2015', 5),
(0, 1, '2014-2015', 'I', 'with ;phg\ndffdgdf', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'WEA\n', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'WERTY', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'GTHGHGH;QQQ', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'SDFDSF;\n\nGH', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'BNNBN', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'ljlkjlkj; khk hk;\n', '02/07/2015', 5),
(0, 1, '2014-2015', 'I', 'sfsdf', '02/10/2015', 5),
(0, 1, '2005-2006', 'I', 'asdfsf', '02/12/2015', 6);

-- --------------------------------------------------------

--
-- Table structure for table `paymentlist`
--

CREATE TABLE IF NOT EXISTS `paymentlist` (
  `payment_id` int(11) NOT NULL auto_increment,
  `payment_desc` varchar(70) NOT NULL,
  `payment_group` varchar(50) NOT NULL,
  PRIMARY KEY  (`payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `paymentlist`
--

INSERT INTO `paymentlist` (`payment_id`, `payment_desc`, `payment_group`) VALUES
(1, 'tuition', 'sched'),
(2, 'registration', 'sched'),
(4, 'athletics/Sports', 'misc'),
(5, 'CHED-Academic', 'misc'),
(6, 'Equipment Fee', 'misc'),
(7, 'Guidance', 'misc'),
(8, 'Internet fee', 'misc'),
(9, 'Library', 'misc'),
(10, 'Medical/Dental', 'misc'),
(11, 'Student Services', 'misc'),
(12, 'Anti-tb', 'misc'),
(13, 'FFP/FAHP', 'misc'),
(14, 'BSP', 'misc'),
(15, 'GSP', 'misc'),
(16, 'Insurance', 'misc'),
(17, 'Maintenance Fee', 'misc'),
(18, 'NORSUNIAN', 'misc'),
(19, 'PMHA', 'misc'),
(20, 'Red Cross', 'misc'),
(21, 'Student Government', 'misc'),
(22, 'Related Learning Experience (RLE)', 'rle'),
(23, 'Authentication', 'other'),
(24, 'CAV', 'other'),
(25, 'Certification', 'other'),
(26, 'Completion Fee', 'other'),
(27, 'Editing Fee', 'other'),
(28, 'English Testing Fee', 'other'),
(29, 'Evaluation', 'other'),
(30, 'EVOC-DEFTAC', 'other'),
(31, 'Honorable Dismissal', 'other'),
(32, 'NORSU Admission Tesrt Fee', 'other'),
(33, 'P.E Uniform (T-shirts & Shorts)', 'other'),
(34, 'Recomendation Fee', 'other'),
(35, 'Student Teaching Fee', 'other'),
(36, 'Transcipt of Records/Form 137', 'other'),
(37, 'Graduation Fee', 'grad'),
(38, 'Alumni', 'grad'),
(39, 'Yearbook', 'grad'),
(40, 'Diploma', 'grad'),
(41, 'Toga', 'grad'),
(42, 'ID Card', 'new'),
(43, 'ID Sling/ Protector', 'new'),
(44, 'Handbook', 'new'),
(85, 'Laboratory Fee', 'sched'),
(87, 'othermisc1', 'othermisc'),
(88, 'othermisc2', 'othermisc'),
(89, 'Overload/Additional Subject', 'other'),
(90, 'Adding/Dropping/Changing', 'other');

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

CREATE TABLE IF NOT EXISTS `refund` (
  `ref_id` int(255) NOT NULL,
  `receipt_num` varchar(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `refund`
--

INSERT INTO `refund` (`ref_id`, `receipt_num`, `amount`) VALUES
(0, '321', 10.05);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_of_fees`
--

CREATE TABLE IF NOT EXISTS `schedule_of_fees` (
  `sched_id` int(11) NOT NULL auto_increment,
  `payment_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `category` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level` varchar(10) NOT NULL,
  `sy` varchar(15) NOT NULL,
  `semester` varchar(2) NOT NULL,
  PRIMARY KEY  (`sched_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=312 ;

--
-- Dumping data for table `schedule_of_fees`
--

INSERT INTO `schedule_of_fees` (`sched_id`, `payment_id`, `amount`, `category`, `course_id`, `year_level`, `sy`, `semester`) VALUES
(1, 1, 213, 'tui', 1, 'I&II', '2005-2006', 'I'),
(2, 1, 213, 'tui', 3, 'I&II', '2005-2006', 'I'),
(3, 1, 213, 'tui', 4, 'I&II', '2005-2006', 'I'),
(4, 1, 213, 'tui', 5, 'I&II', '2005-2006', 'I'),
(5, 1, 213, 'tui', 8, 'I&II', '2005-2006', 'I'),
(6, 1, 213, 'tui', 93, 'I&II', '2005-2006', 'I'),
(7, 2, 1, 'tui', 1, 'I&II', '2005-2006', 'I'),
(8, 2, 1, 'tui', 3, 'I&II', '2005-2006', 'I'),
(9, 2, 1, 'tui', 4, 'I&II', '2005-2006', 'I'),
(10, 2, 1, 'tui', 5, 'I&II', '2005-2006', 'I'),
(11, 2, 1, 'tui', 8, 'I&II', '2005-2006', 'I'),
(12, 2, 1, 'tui', 93, 'I&II', '2005-2006', 'I'),
(13, 85, 13, 'tui', 1, 'I&II', '2005-2006', 'I'),
(14, 85, 13, 'tui', 3, 'I&II', '2005-2006', 'I'),
(15, 85, 13, 'tui', 4, 'I&II', '2005-2006', 'I'),
(16, 85, 13, 'tui', 5, 'I&II', '2005-2006', 'I'),
(17, 85, 13, 'tui', 8, 'I&II', '2005-2006', 'I'),
(18, 85, 13, 'tui', 93, 'I&II', '2005-2006', 'I'),
(19, 4, 123, 'tf', 1, 'I&II', '2005-2006', 'I'),
(20, 4, 123, 'tf', 3, 'I&II', '2005-2006', 'I'),
(21, 4, 123, 'tf', 4, 'I&II', '2005-2006', 'I'),
(22, 4, 123, 'tf', 5, 'I&II', '2005-2006', 'I'),
(23, 4, 123, 'tf', 8, 'I&II', '2005-2006', 'I'),
(24, 4, 123, 'tf', 93, 'I&II', '2005-2006', 'I'),
(25, 5, 123, 'tf', 1, 'I&II', '2005-2006', 'I'),
(26, 5, 123, 'tf', 3, 'I&II', '2005-2006', 'I'),
(27, 5, 123, 'tf', 4, 'I&II', '2005-2006', 'I'),
(28, 5, 123, 'tf', 5, 'I&II', '2005-2006', 'I'),
(29, 5, 123, 'tf', 8, 'I&II', '2005-2006', 'I'),
(30, 5, 123, 'tf', 93, 'I&II', '2005-2006', 'I'),
(31, 6, 13, 'tf', 1, 'I&II', '2005-2006', 'I'),
(32, 6, 13, 'tf', 3, 'I&II', '2005-2006', 'I'),
(33, 6, 13, 'tf', 4, 'I&II', '2005-2006', 'I'),
(34, 6, 13, 'tf', 5, 'I&II', '2005-2006', 'I'),
(35, 6, 13, 'tf', 8, 'I&II', '2005-2006', 'I'),
(36, 6, 13, 'tf', 93, 'I&II', '2005-2006', 'I'),
(37, 7, 123, 'tf', 1, 'I&II', '2005-2006', 'I'),
(38, 7, 123, 'tf', 3, 'I&II', '2005-2006', 'I'),
(39, 7, 123, 'tf', 4, 'I&II', '2005-2006', 'I'),
(40, 7, 123, 'tf', 5, 'I&II', '2005-2006', 'I'),
(41, 7, 123, 'tf', 8, 'I&II', '2005-2006', 'I'),
(42, 7, 123, 'tf', 93, 'I&II', '2005-2006', 'I'),
(43, 8, 133, 'tf', 1, 'I&II', '2005-2006', 'I'),
(44, 8, 133, 'tf', 3, 'I&II', '2005-2006', 'I'),
(45, 8, 133, 'tf', 4, 'I&II', '2005-2006', 'I'),
(46, 8, 133, 'tf', 5, 'I&II', '2005-2006', 'I'),
(47, 8, 133, 'tf', 8, 'I&II', '2005-2006', 'I'),
(48, 8, 133, 'tf', 93, 'I&II', '2005-2006', 'I'),
(49, 9, 213, 'misc', 1, 'I&II', '2005-2006', 'I'),
(50, 9, 213, 'misc', 3, 'I&II', '2005-2006', 'I'),
(51, 9, 213, 'misc', 4, 'I&II', '2005-2006', 'I'),
(52, 9, 213, 'misc', 5, 'I&II', '2005-2006', 'I'),
(53, 9, 213, 'misc', 8, 'I&II', '2005-2006', 'I'),
(54, 9, 213, 'misc', 93, 'I&II', '2005-2006', 'I'),
(55, 10, 213, 'misc', 1, 'I&II', '2005-2006', 'I'),
(56, 10, 213, 'misc', 3, 'I&II', '2005-2006', 'I'),
(57, 10, 213, 'misc', 4, 'I&II', '2005-2006', 'I'),
(58, 10, 213, 'misc', 5, 'I&II', '2005-2006', 'I'),
(59, 10, 213, 'misc', 8, 'I&II', '2005-2006', 'I'),
(60, 10, 213, 'misc', 93, 'I&II', '2005-2006', 'I'),
(61, 11, 123, 'misc', 1, 'I&II', '2005-2006', 'I'),
(62, 11, 123, 'misc', 3, 'I&II', '2005-2006', 'I'),
(63, 11, 123, 'misc', 4, 'I&II', '2005-2006', 'I'),
(64, 11, 123, 'misc', 5, 'I&II', '2005-2006', 'I'),
(65, 11, 123, 'misc', 8, 'I&II', '2005-2006', 'I'),
(66, 11, 123, 'misc', 93, 'I&II', '2005-2006', 'I'),
(67, 12, 12123, 'misc', 1, 'I&II', '2005-2006', 'I'),
(68, 12, 12123, 'misc', 3, 'I&II', '2005-2006', 'I'),
(69, 12, 12123, 'misc', 4, 'I&II', '2005-2006', 'I'),
(70, 12, 12123, 'misc', 5, 'I&II', '2005-2006', 'I'),
(71, 12, 12123, 'misc', 8, 'I&II', '2005-2006', 'I'),
(72, 12, 12123, 'misc', 93, 'I&II', '2005-2006', 'I'),
(73, 13, 12, 'misc', 1, 'I&II', '2005-2006', 'I'),
(74, 13, 12, 'misc', 3, 'I&II', '2005-2006', 'I'),
(75, 13, 12, 'misc', 4, 'I&II', '2005-2006', 'I'),
(76, 13, 12, 'misc', 5, 'I&II', '2005-2006', 'I'),
(77, 13, 12, 'misc', 8, 'I&II', '2005-2006', 'I'),
(78, 13, 12, 'misc', 93, 'I&II', '2005-2006', 'I'),
(79, 14, 231, 'tui', 1, 'I&II', '2005-2006', 'I'),
(80, 14, 231, 'tui', 3, 'I&II', '2005-2006', 'I'),
(81, 14, 231, 'tui', 4, 'I&II', '2005-2006', 'I'),
(82, 14, 231, 'tui', 5, 'I&II', '2005-2006', 'I'),
(83, 14, 231, 'tui', 8, 'I&II', '2005-2006', 'I'),
(84, 14, 231, 'tui', 93, 'I&II', '2005-2006', 'I'),
(85, 15, 123, 'tui', 1, 'I&II', '2005-2006', 'I'),
(86, 15, 123, 'tui', 3, 'I&II', '2005-2006', 'I'),
(87, 15, 123, 'tui', 4, 'I&II', '2005-2006', 'I'),
(88, 15, 123, 'tui', 5, 'I&II', '2005-2006', 'I'),
(89, 15, 123, 'tui', 8, 'I&II', '2005-2006', 'I'),
(90, 15, 123, 'tui', 93, 'I&II', '2005-2006', 'I'),
(91, 16, 31, 'tui', 1, 'I&II', '2005-2006', 'I'),
(92, 16, 31, 'tui', 3, 'I&II', '2005-2006', 'I'),
(93, 16, 31, 'tui', 4, 'I&II', '2005-2006', 'I'),
(94, 16, 31, 'tui', 5, 'I&II', '2005-2006', 'I'),
(95, 16, 31, 'tui', 8, 'I&II', '2005-2006', 'I'),
(96, 16, 31, 'tui', 93, 'I&II', '2005-2006', 'I'),
(97, 17, 231, 'tui', 1, 'I&II', '2005-2006', 'I'),
(98, 17, 231, 'tui', 3, 'I&II', '2005-2006', 'I'),
(99, 17, 231, 'tui', 4, 'I&II', '2005-2006', 'I'),
(100, 17, 231, 'tui', 5, 'I&II', '2005-2006', 'I'),
(101, 17, 231, 'tui', 8, 'I&II', '2005-2006', 'I'),
(102, 17, 231, 'tui', 93, 'I&II', '2005-2006', 'I'),
(103, 18, 213, 'tui', 1, 'I&II', '2005-2006', 'I'),
(104, 18, 213, 'tui', 3, 'I&II', '2005-2006', 'I'),
(105, 18, 213, 'tui', 4, 'I&II', '2005-2006', 'I'),
(106, 18, 213, 'tui', 5, 'I&II', '2005-2006', 'I'),
(107, 18, 213, 'tui', 8, 'I&II', '2005-2006', 'I'),
(108, 18, 213, 'tui', 93, 'I&II', '2005-2006', 'I'),
(109, 19, 31, 'tui', 1, 'I&II', '2005-2006', 'I'),
(110, 19, 31, 'tui', 3, 'I&II', '2005-2006', 'I'),
(111, 19, 31, 'tui', 4, 'I&II', '2005-2006', 'I'),
(112, 19, 31, 'tui', 5, 'I&II', '2005-2006', 'I'),
(113, 19, 31, 'tui', 8, 'I&II', '2005-2006', 'I'),
(114, 19, 31, 'tui', 93, 'I&II', '2005-2006', 'I'),
(115, 20, 213, 'tui', 1, 'I&II', '2005-2006', 'I'),
(116, 20, 213, 'tui', 3, 'I&II', '2005-2006', 'I'),
(117, 20, 213, 'tui', 4, 'I&II', '2005-2006', 'I'),
(118, 20, 213, 'tui', 5, 'I&II', '2005-2006', 'I'),
(119, 20, 213, 'tui', 8, 'I&II', '2005-2006', 'I'),
(120, 20, 213, 'tui', 93, 'I&II', '2005-2006', 'I'),
(121, 21, 32, 'tui', 1, 'I&II', '2005-2006', 'I'),
(122, 21, 32, 'tui', 3, 'I&II', '2005-2006', 'I'),
(123, 21, 32, 'tui', 4, 'I&II', '2005-2006', 'I'),
(124, 21, 32, 'tui', 5, 'I&II', '2005-2006', 'I'),
(125, 21, 32, 'tui', 8, 'I&II', '2005-2006', 'I'),
(126, 21, 32, 'tui', 93, 'I&II', '2005-2006', 'I'),
(127, 22, 12, 'misc', 1, 'I&', '2005-2006', 'I'),
(128, 22, 123, 'misc', 3, 'I&', '2005-2006', 'I'),
(129, 22, 23123, 'misc', 4, 'I&', '2005-2006', 'I'),
(130, 22, 213, 'misc', 5, 'I&', '2005-2006', 'I'),
(131, 22, 13, 'misc', 8, 'I&', '2005-2006', 'I'),
(132, 22, 213, 'misc', 93, 'I&', '2005-2006', 'I'),
(133, 23, 12, 'tui', 0, '', '2005-2006', 'I'),
(134, 24, 13, 'tui', 0, '', '2005-2006', 'I'),
(135, 25, 132, 'tui', 0, '', '2005-2006', 'I'),
(136, 26, 13, 'tui', 0, '', '2005-2006', 'I'),
(137, 27, 123, 'tui', 0, '', '2005-2006', 'I'),
(138, 28, 213, 'tui', 0, '', '2005-2006', 'I'),
(139, 29, 21, 'tui', 0, '', '2005-2006', 'I'),
(140, 30, 213, 'tui', 0, '', '2005-2006', 'I'),
(141, 31, 213, 'tui', 0, '', '2005-2006', 'I'),
(142, 32, 21, 'tui', 0, '', '2005-2006', 'I'),
(143, 33, 213, 'tui', 0, '', '2005-2006', 'I'),
(144, 34, 13, 'tui', 0, '', '2005-2006', 'I'),
(145, 35, 123, 'tui', 0, '', '2005-2006', 'I'),
(146, 36, 31, 'tui', 0, '', '2005-2006', 'I'),
(147, 89, 132, 'tui', 0, '', '2005-2006', 'I'),
(148, 90, 3, 'tui', 0, '', '2005-2006', 'I'),
(149, 37, 123, 'tui', 0, '&IV', '2005-2006', '0'),
(150, 38, 32, 'tui', 0, '&IV', '2005-2006', '0'),
(151, 39, 13, 'tui', 0, '&IV', '2005-2006', '0'),
(152, 40, 13, 'tui', 0, '&IV', '2005-2006', '0'),
(153, 41, 213, 'tui', 0, '&IV', '2005-2006', '0'),
(154, 42, 213, 'tui', 0, 'I&', '2005-2006', 'I'),
(155, 43, 213, 'tui', 0, 'I&', '2005-2006', 'I'),
(156, 44, 213, 'tui', 0, 'I&', '2005-2006', 'I'),
(157, 87, 213, 'tui', 0, '', '2005-2006', 'I'),
(158, 88, 123, 'tui', 0, '', '2005-2006', 'I'),
(159, 1, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(160, 1, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(161, 1, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(162, 1, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(163, 1, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(164, 1, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(165, 2, 1233, 'tui', 1, 'I&II', '2005-2006', 'II'),
(166, 2, 1233, 'tui', 3, 'I&II', '2005-2006', 'II'),
(167, 2, 1233, 'tui', 4, 'I&II', '2005-2006', 'II'),
(168, 2, 1233, 'tui', 5, 'I&II', '2005-2006', 'II'),
(169, 2, 1233, 'tui', 8, 'I&II', '2005-2006', 'II'),
(170, 2, 1233, 'tui', 93, 'I&II', '2005-2006', 'II'),
(171, 85, 12, 'tui', 1, 'I&II', '2005-2006', 'II'),
(172, 85, 12, 'tui', 3, 'I&II', '2005-2006', 'II'),
(173, 85, 12, 'tui', 4, 'I&II', '2005-2006', 'II'),
(174, 85, 12, 'tui', 5, 'I&II', '2005-2006', 'II'),
(175, 85, 12, 'tui', 8, 'I&II', '2005-2006', 'II'),
(176, 85, 12, 'tui', 93, 'I&II', '2005-2006', 'II'),
(177, 4, 231, 'tui', 1, 'I&II', '2005-2006', 'II'),
(178, 4, 231, 'tui', 3, 'I&II', '2005-2006', 'II'),
(179, 4, 231, 'tui', 4, 'I&II', '2005-2006', 'II'),
(180, 4, 231, 'tui', 5, 'I&II', '2005-2006', 'II'),
(181, 4, 231, 'tui', 8, 'I&II', '2005-2006', 'II'),
(182, 4, 231, 'tui', 93, 'I&II', '2005-2006', 'II'),
(183, 5, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(184, 5, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(185, 5, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(186, 5, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(187, 5, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(188, 5, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(189, 6, 13, 'tui', 1, 'I&II', '2005-2006', 'II'),
(190, 6, 13, 'tui', 3, 'I&II', '2005-2006', 'II'),
(191, 6, 13, 'tui', 4, 'I&II', '2005-2006', 'II'),
(192, 6, 13, 'tui', 5, 'I&II', '2005-2006', 'II'),
(193, 6, 13, 'tui', 8, 'I&II', '2005-2006', 'II'),
(194, 6, 13, 'tui', 93, 'I&II', '2005-2006', 'II'),
(195, 7, 231, 'tui', 1, 'I&II', '2005-2006', 'II'),
(196, 7, 231, 'tui', 3, 'I&II', '2005-2006', 'II'),
(197, 7, 231, 'tui', 4, 'I&II', '2005-2006', 'II'),
(198, 7, 231, 'tui', 5, 'I&II', '2005-2006', 'II'),
(199, 7, 231, 'tui', 8, 'I&II', '2005-2006', 'II'),
(200, 7, 231, 'tui', 93, 'I&II', '2005-2006', 'II'),
(201, 8, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(202, 8, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(203, 8, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(204, 8, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(205, 8, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(206, 8, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(207, 9, 23, 'tui', 1, 'I&II', '2005-2006', 'II'),
(208, 9, 23, 'tui', 3, 'I&II', '2005-2006', 'II'),
(209, 9, 23, 'tui', 4, 'I&II', '2005-2006', 'II'),
(210, 9, 23, 'tui', 5, 'I&II', '2005-2006', 'II'),
(211, 9, 23, 'tui', 8, 'I&II', '2005-2006', 'II'),
(212, 9, 23, 'tui', 93, 'I&II', '2005-2006', 'II'),
(213, 10, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(214, 10, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(215, 10, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(216, 10, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(217, 10, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(218, 10, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(219, 11, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(220, 11, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(221, 11, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(222, 11, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(223, 11, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(224, 11, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(225, 12, 23213, 'tui', 1, 'I&II', '2005-2006', 'II'),
(226, 12, 23213, 'tui', 3, 'I&II', '2005-2006', 'II'),
(227, 12, 23213, 'tui', 4, 'I&II', '2005-2006', 'II'),
(228, 12, 23213, 'tui', 5, 'I&II', '2005-2006', 'II'),
(229, 12, 23213, 'tui', 8, 'I&II', '2005-2006', 'II'),
(230, 12, 23213, 'tui', 93, 'I&II', '2005-2006', 'II'),
(231, 13, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(232, 13, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(233, 13, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(234, 13, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(235, 13, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(236, 13, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(237, 14, 213, 'tui', 1, 'I&II', '2005-2006', 'II'),
(238, 14, 213, 'tui', 3, 'I&II', '2005-2006', 'II'),
(239, 14, 213, 'tui', 4, 'I&II', '2005-2006', 'II'),
(240, 14, 213, 'tui', 5, 'I&II', '2005-2006', 'II'),
(241, 14, 213, 'tui', 8, 'I&II', '2005-2006', 'II'),
(242, 14, 213, 'tui', 93, 'I&II', '2005-2006', 'II'),
(243, 15, 13, 'tui', 1, 'I&II', '2005-2006', 'II'),
(244, 15, 13, 'tui', 3, 'I&II', '2005-2006', 'II'),
(245, 15, 13, 'tui', 4, 'I&II', '2005-2006', 'II'),
(246, 15, 13, 'tui', 5, 'I&II', '2005-2006', 'II'),
(247, 15, 13, 'tui', 8, 'I&II', '2005-2006', 'II'),
(248, 15, 13, 'tui', 93, 'I&II', '2005-2006', 'II'),
(249, 16, 1233, 'tui', 1, 'I&II', '2005-2006', 'II'),
(250, 16, 1233, 'tui', 3, 'I&II', '2005-2006', 'II'),
(251, 16, 1233, 'tui', 4, 'I&II', '2005-2006', 'II'),
(252, 16, 1233, 'tui', 5, 'I&II', '2005-2006', 'II'),
(253, 16, 1233, 'tui', 8, 'I&II', '2005-2006', 'II'),
(254, 16, 1233, 'tui', 93, 'I&II', '2005-2006', 'II'),
(255, 17, 1, 'tui', 1, 'I&II', '2005-2006', 'II'),
(256, 17, 1, 'tui', 3, 'I&II', '2005-2006', 'II'),
(257, 17, 1, 'tui', 4, 'I&II', '2005-2006', 'II'),
(258, 17, 1, 'tui', 5, 'I&II', '2005-2006', 'II'),
(259, 17, 1, 'tui', 8, 'I&II', '2005-2006', 'II'),
(260, 17, 1, 'tui', 93, 'I&II', '2005-2006', 'II'),
(261, 18, 123, 'tui', 1, 'I&II', '2005-2006', 'II'),
(262, 18, 123, 'tui', 3, 'I&II', '2005-2006', 'II'),
(263, 18, 123, 'tui', 4, 'I&II', '2005-2006', 'II'),
(264, 18, 123, 'tui', 5, 'I&II', '2005-2006', 'II'),
(265, 18, 123, 'tui', 8, 'I&II', '2005-2006', 'II'),
(266, 18, 123, 'tui', 93, 'I&II', '2005-2006', 'II'),
(267, 19, 1, 'tui', 1, 'I&II', '2005-2006', 'II'),
(268, 19, 1, 'tui', 3, 'I&II', '2005-2006', 'II'),
(269, 19, 1, 'tui', 4, 'I&II', '2005-2006', 'II'),
(270, 19, 1, 'tui', 5, 'I&II', '2005-2006', 'II'),
(271, 19, 1, 'tui', 8, 'I&II', '2005-2006', 'II'),
(272, 19, 1, 'tui', 93, 'I&II', '2005-2006', 'II'),
(273, 20, 13, 'tui', 1, 'I&II', '2005-2006', 'II'),
(274, 20, 13, 'tui', 3, 'I&II', '2005-2006', 'II'),
(275, 20, 13, 'tui', 4, 'I&II', '2005-2006', 'II'),
(276, 20, 13, 'tui', 5, 'I&II', '2005-2006', 'II'),
(277, 20, 13, 'tui', 8, 'I&II', '2005-2006', 'II'),
(278, 20, 13, 'tui', 93, 'I&II', '2005-2006', 'II'),
(279, 21, 1, 'tui', 1, 'I&II', '2005-2006', 'II'),
(280, 21, 1, 'tui', 3, 'I&II', '2005-2006', 'II'),
(281, 21, 1, 'tui', 4, 'I&II', '2005-2006', 'II'),
(282, 21, 1, 'tui', 5, 'I&II', '2005-2006', 'II'),
(283, 21, 1, 'tui', 8, 'I&II', '2005-2006', 'II'),
(284, 21, 1, 'tui', 93, 'I&II', '2005-2006', 'II'),
(285, 22, 123, 'misc', 1, 'I&', '2005-2006', 'II'),
(286, 22, 123, 'misc', 3, 'I&', '2005-2006', 'II'),
(287, 22, 213, 'misc', 4, 'I&', '2005-2006', 'II'),
(288, 22, 21, 'misc', 5, 'I&', '2005-2006', 'II'),
(289, 22, 123, 'misc', 8, 'I&', '2005-2006', 'II'),
(290, 22, 123, 'misc', 93, 'I&', '2005-2006', 'II'),
(291, 23, 123, 'tui', 0, '', '2005-2006', 'II'),
(292, 24, 123, 'tui', 0, '', '2005-2006', 'II'),
(293, 25, 123, 'tui', 0, '', '2005-2006', 'II'),
(294, 26, 3, 'tui', 0, '', '2005-2006', 'II'),
(295, 27, 123, 'tui', 0, '', '2005-2006', 'II'),
(296, 28, 321, 'tui', 0, '', '2005-2006', 'II'),
(297, 29, 1, 'tui', 0, '', '2005-2006', 'II'),
(298, 30, 12321, 'tui', 0, '', '2005-2006', 'II'),
(299, 31, 123, 'tui', 0, '', '2005-2006', 'II'),
(300, 32, 213, 'tui', 0, '', '2005-2006', 'II'),
(301, 33, 213, 'tui', 0, '', '2005-2006', 'II'),
(302, 34, 213, 'tui', 0, '', '2005-2006', 'II'),
(303, 35, 123, 'tui', 0, '', '2005-2006', 'II'),
(304, 36, 123, 'tui', 0, '', '2005-2006', 'II'),
(305, 89, 123, 'tui', 0, '', '2005-2006', 'II'),
(306, 90, 123, 'tui', 0, '', '2005-2006', 'II'),
(307, 42, 123, 'tui', 0, 'I&', '2005-2006', 'II'),
(308, 43, 1233, 'tui', 0, 'I&', '2005-2006', 'II'),
(309, 44, 213, 'tui', 0, 'I&', '2005-2006', 'II'),
(310, 87, 213, 'tui', 0, '', '2005-2006', 'II'),
(311, 88, 213, 'tui', 0, '', '2005-2006', 'II');

-- --------------------------------------------------------

--
-- Table structure for table `scholar`
--

CREATE TABLE IF NOT EXISTS `scholar` (
  `scholar_id` int(11) NOT NULL auto_increment,
  `stud_id` int(11) NOT NULL,
  `scholarship_id` int(11) NOT NULL,
  `sem` char(2) NOT NULL,
  `sy` varchar(10) NOT NULL,
  PRIMARY KEY  (`scholar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `scholar`
--

INSERT INTO `scholar` (`scholar_id`, `stud_id`, `scholarship_id`, `sem`, `sy`) VALUES
(1, 11, 11, 'II', '2017-2018'),
(2, 7, 12, 'II', '2017-2018'),
(3, 4, 13, 'II', '2017-2018'),
(4, 3, 14, 'II', '2017-2018'),
(5, 12, 15, 'II', '2013-2014'),
(6, 11, 16, 'II', '2017-2018'),
(7, 9, 0, 'II', '2017-2018'),
(8, 13, 17, 'II', '2013-2014'),
(9, 0, 0, 'II', '2013-2014'),
(10, 0, 0, 'II', '2013-2014');

-- --------------------------------------------------------

--
-- Table structure for table `scholarship`
--

CREATE TABLE IF NOT EXISTS `scholarship` (
  `scholar_id` int(11) NOT NULL auto_increment,
  `description` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY  (`scholar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `scholarship`
--

INSERT INTO `scholarship` (`scholar_id`, `description`, `amount`) VALUES
(1, 'LGU BAYAWAN', 5000),
(2, 'LGU BAYAWAN', 10000),
(3, 'ched', 5000),
(21, 'sdge', 1);

-- --------------------------------------------------------

--
-- Table structure for table `signatory`
--

CREATE TABLE IF NOT EXISTS `signatory` (
  `sig_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY  (`sig_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `signatory`
--

INSERT INTO `signatory` (`sig_id`, `name`, `status`) VALUES
(1, 'MYRNa m. tongzon', 'Deactivate'),
(2, 'jake d. cornelia', 'Activated');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `stud_id` int(11) NOT NULL auto_increment,
  `stud_number` varchar(20) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  PRIMARY KEY  (`stud_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`stud_id`, `stud_number`, `fname`, `lname`) VALUES
(1, '1', 'jake', 'cornelia'),
(2, '213', 'christine', 'lajot');

-- --------------------------------------------------------

--
-- Table structure for table `student_scholarship`
--

CREATE TABLE IF NOT EXISTS `student_scholarship` (
  `id` int(11) NOT NULL auto_increment,
  `scholar_id` int(11) NOT NULL,
  `stud_id` varchar(111) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `student_scholarship`
--


-- --------------------------------------------------------

--
-- Table structure for table `student_status`
--

CREATE TABLE IF NOT EXISTS `student_status` (
  `stat_id` int(11) NOT NULL auto_increment,
  `stud_id` int(11) NOT NULL,
  `scholar_id` int(11) NOT NULL,
  `scholar_printed` int(2) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester` char(5) NOT NULL,
  `sy` varchar(15) NOT NULL,
  `year_level` varchar(10) NOT NULL,
  `status` varchar(30) NOT NULL,
  PRIMARY KEY  (`stat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `student_status`
--

INSERT INTO `student_status` (`stat_id`, `stud_id`, `scholar_id`, `scholar_printed`, `course_id`, `semester`, `sy`, `year_level`, `status`) VALUES
(1, 1, 0, 0, 8, 'I', '2005-2006', 'I', 'new'),
(2, 2, 0, 0, 3, 'I', '2005-2006', 'I', 'new'),
(3, 1, 3, 0, 4, 'II', '2005-2006', 'I', 'shiftee');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `username` varchar(30) character set latin1 collate latin1_bin NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `sy` varchar(15) NOT NULL,
  `semester` char(2) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `username`, `password`, `type`, `sy`, `semester`, `status`) VALUES
(6, 'christine', 'xtine', 'db88b30344e039d94791a8e7c9988447', 'Collection', '', '', 0),
(5, 'jake cornelia', 'jakecorn', 'c20ad4d76fe97759aa27a0c99bff6710', 'admin', '2005-2006', 'II', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_deposit`
--

CREATE TABLE IF NOT EXISTS `user_deposit` (
  `log_id` int(11) NOT NULL auto_increment,
  `date` varchar(15) NOT NULL,
  `deposited_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_deposit`
--

INSERT INTO `user_deposit` (`log_id`, `date`, `deposited_amount`, `user_id`) VALUES
(1, '02/12/2015', 500, 6),
(2, '02/11/2015', 30, 5),
(3, '02/11/2015', 300, 5),
(4, '02/12/2015', 500, 6),
(5, '02/12/2015', 4000, 6);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `date` varchar(20) NOT NULL,
  `time` varchar(10) NOT NULL,
  `action` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`log_id`, `date`, `time`, `action`, `user_id`) VALUES
(1, '02/12/2015', '04:36 am', 'BEED-I to BSINT I', 5),
(2, '02/12/2015', '04:37 am', 'BSINT-I to MIDWIFERY I', 5);
