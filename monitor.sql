-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2021 at 05:19 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

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
  `ldap` int(11) DEFAULT NULL,
  `domainid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_accounts`
--

INSERT INTO `tbl_accounts` (`userid`, `username`, `fullname`, `email`, `password`, `active`, `ldap`, `domainid`) VALUES
(17, 'admin', 'admin', 'admin@local', '21232f297a57a5a743894a0e4a801fc3', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ciscosdwanschedules`
--

CREATE TABLE `tbl_ciscosdwanschedules` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deviceid` text COLLATE utf8_unicode_ci NOT NULL,
  `templateid` text COLLATE utf8_unicode_ci NOT NULL,
  `domainid` int(11) NOT NULL,
  `lastrun` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ciscosdwanservers`
--

CREATE TABLE `tbl_ciscosdwanservers` (
  `ciscosdwanserverid` int(11) NOT NULL,
  `domainid` int(11) NOT NULL,
  `displayname` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `secures` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hostname` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `user` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `basestring` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_ciscosdwanservers`
--

INSERT INTO `tbl_ciscosdwanservers` (`ciscosdwanserverid`, `domainid`, `displayname`, `secures`, `hostname`, `port`, `user`, `password`, `basestring`) VALUES
(1, 1, 'Sandbox Cisco', 'https', 'sandbox-sdwan-1.cisco.com', 443, 'devnetuser', 'RG!_Yw919_83', '/dataservice/');

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
-- Table structure for table `tbl_refreshrate`
--

CREATE TABLE `tbl_refreshrate` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `product` text COLLATE utf8_unicode_ci NOT NULL,
  `refreshrate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_refreshrate`
--

INSERT INTO `tbl_refreshrate` (`id`, `userid`, `product`, `refreshrate`) VALUES
(1, 1, 'casvd', 1000),
(3, 10, 'casvd', 5000),
(4, 16, 'casvd', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rights`
--

CREATE TABLE `tbl_rights` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `slwnpmconfig` int(11) NOT NULL,
  `slwnpmuse` int(11) NOT NULL,
  `ciscosdwanconfig` int(11) DEFAULT NULL,
  `ciscosdwanuse` int(11) DEFAULT NULL,
  `accountconfig` int(11) DEFAULT NULL,
  `casvduse` int(11) DEFAULT NULL,
  `casvdconfig` int(11) DEFAULT NULL,
  `centreonuse` int(11) DEFAULT NULL,
  `centreonconfig` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_rights`
--

INSERT INTO `tbl_rights` (`id`, `userid`, `slwnpmconfig`, `slwnpmuse`, `ciscosdwanconfig`, `ciscosdwanuse`, `accountconfig`, `casvduse`, `casvdconfig`, `centreonuse`, `centreonconfig`) VALUES
(16, 17, 1, 1, 1, 1, 1, 1, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `tbl_ciscosdwanschedules`
--
ALTER TABLE `tbl_ciscosdwanschedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ciscosdwanservers`
--
ALTER TABLE `tbl_ciscosdwanservers`
  ADD PRIMARY KEY (`ciscosdwanserverid`);

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
-- Indexes for table `tbl_refreshrate`
--
ALTER TABLE `tbl_refreshrate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rights`
--
ALTER TABLE `tbl_rights`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_ciscosdwanschedules`
--
ALTER TABLE `tbl_ciscosdwanschedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_ciscosdwanservers`
--
ALTER TABLE `tbl_ciscosdwanservers`
  MODIFY `ciscosdwanserverid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `tbl_refreshrate`
--
ALTER TABLE `tbl_refreshrate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_rights`
--
ALTER TABLE `tbl_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
