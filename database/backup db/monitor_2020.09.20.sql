-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2020 at 01:03 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitor`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_accounts`
--

CREATE TABLE `tbl_accounts` (
  `userid` int(11) NOT NULL,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `fullname` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `ldap` int(11) NOT NULL,
  `domainid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_accounts`
--

INSERT INTO `tbl_accounts` (`userid`, `username`, `fullname`, `email`, `password`, `active`, `ldap`, `domainid`) VALUES
(1, 'ducnx4', 'Nguyễn Xuân Đức', 'ducnx4@fpt.com.vn', 'e10adc3949ba59abbe56e057f20f883e', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_domains`
--

CREATE TABLE `tbl_domains` (
  `domainid` int(11) NOT NULL,
  `domainname` text COLLATE utf8_unicode_ci NOT NULL,
  `company` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `ldaphost` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `ldapport` int(11) DEFAULT NULL,
  `domainactive` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_domains`
--

INSERT INTO `tbl_domains` (`domainid`, `domainname`, `company`, `address`, `tel`, `ldaphost`, `ldapport`, `domainactive`) VALUES
(1, 'fpt.com.vn', 'FPT', 'Phạm Hùng, Hà Nội', '0936180379', '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_genconfig`
--

CREATE TABLE `tbl_genconfig` (
  `configid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_interfacestatus`
--

CREATE TABLE `tbl_interfacestatus` (
  `id` int(11) NOT NULL,
  `interfacevalue` int(11) DEFAULT NULL,
  `displayname` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_interfacestatus`
--

INSERT INTO `tbl_interfacestatus` (`id`, `interfacevalue`, `displayname`) VALUES
(1, 0, 'Uknown'),
(2, 1, 'Up'),
(3, 2, 'Down'),
(4, 3, 'Warning'),
(5, 4, 'Shutdown'),
(6, 5, 'Testing'),
(7, 6, 'Dormant'),
(8, 7, 'Not Present'),
(9, 8, 'Lower Layer Down'),
(10, 9, 'Unmanaged'),
(11, 10, 'Unplugged'),
(12, 11, 'External'),
(13, 12, 'Unreachable'),
(14, 14, 'Critical'),
(15, 15, 'Partly Available'),
(16, 16, 'Misconfigured'),
(17, 17, 'Undefined'),
(18, 19, 'Unconfirmed'),
(19, 22, 'Active'),
(20, 24, 'Inactive'),
(21, 25, 'Expired'),
(22, 26, 'Monitoring Disabled'),
(23, 27, 'Disabled'),
(24, 28, 'NotLicensed'),
(25, 29, 'Other Category'),
(26, 30, 'Not Running');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rights`
--

CREATE TABLE `tbl_rights` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `slwnpmconfig` int(11) NOT NULL,
  `slwnpmuse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_rights`
--

INSERT INTO `tbl_rights` (`id`, `userid`, `slwnpmconfig`, `slwnpmuse`) VALUES
(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slwnpmservers`
--

CREATE TABLE `tbl_slwnpmservers` (
  `domainid` int(11) NOT NULL,
  `slwnpmserverid` int(11) NOT NULL,
  `displayname` text COLLATE utf8_unicode_ci NOT NULL,
  `hostname` text COLLATE utf8_unicode_ci NOT NULL,
  `secures` text COLLATE utf8_unicode_ci NOT NULL,
  `port` int(11) NOT NULL,
  `basestring` text COLLATE utf8_unicode_ci NOT NULL,
  `user` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_slwnpmservers`
--

INSERT INTO `tbl_slwnpmservers` (`domainid`, `slwnpmserverid`, `displayname`, `hostname`, `secures`, `port`, `basestring`, `user`, `password`) VALUES
(1, 2, 'Vietlott', '172.16.0.10', 'https', 17778, '/SolarWinds/InformationService/v3/Json/Query?', 'admin', 'Cisco@1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `tbl_domains`
--
ALTER TABLE `tbl_domains`
  ADD PRIMARY KEY (`domainid`);

--
-- Indexes for table `tbl_genconfig`
--
ALTER TABLE `tbl_genconfig`
  ADD PRIMARY KEY (`configid`);

--
-- Indexes for table `tbl_interfacestatus`
--
ALTER TABLE `tbl_interfacestatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rights`
--
ALTER TABLE `tbl_rights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_slwnpmservers`
--
ALTER TABLE `tbl_slwnpmservers`
  ADD PRIMARY KEY (`slwnpmserverid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_domains`
--
ALTER TABLE `tbl_domains`
  MODIFY `domainid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_genconfig`
--
ALTER TABLE `tbl_genconfig`
  MODIFY `configid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_interfacestatus`
--
ALTER TABLE `tbl_interfacestatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_rights`
--
ALTER TABLE `tbl_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_slwnpmservers`
--
ALTER TABLE `tbl_slwnpmservers`
  MODIFY `slwnpmserverid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
