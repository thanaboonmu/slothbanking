-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2016 at 10:13 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `AccountType`
--

CREATE TABLE `AccountType` (
  `accountType` varchar(20) NOT NULL,
  `interestRate` decimal(12,2) NOT NULL,
  `limit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `AccountType`
--

INSERT INTO `AccountType` (`accountType`, `interestRate`, `limit`) VALUES
('checking', '0.50', 5),
('interest', '3.00', 5),
('saving', '2.00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `BankInfo`
--

CREATE TABLE `BankInfo` (
  `bankName` varchar(20) NOT NULL,
  `address` varchar(128) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phoneNum` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `BankInfo`
--

INSERT INTO `BankInfo` (`bankName`, `address`, `email`, `phoneNum`) VALUES
('ktb', '20/10 KTB Tower BKK', 'ktbSupport@mail.com', '029999991'),
('scb', '20/10 SCB Tower BKK', 'scbSupport@mail.com', '029999990'),
('slot', '23/130 PrachaUtit54 Bangmod BKK', 'slotSupport@mail.com', '029999999');

-- --------------------------------------------------------

--
-- Table structure for table `Bill_info`
--

CREATE TABLE `Bill_info` (
  `referenceNo` varchar(13) NOT NULL,
  `companyName` varchar(20) NOT NULL,
  `customerFirstName` varchar(20) NOT NULL,
  `customerLastName` varchar(20) NOT NULL,
  `amountDue` decimal(12,2) NOT NULL,
  `debtDateTime` datetime NOT NULL,
  `accountNo` varchar(15) DEFAULT 'null',
  `paidDateTime` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Bill_info`
--

INSERT INTO `Bill_info` (`referenceNo`, `companyName`, `customerFirstName`, `customerLastName`, `amountDue`, `debtDateTime`, `accountNo`, `paidDateTime`) VALUES
('1150', 'TRUE', 'Sirapat', 'NaRanong', '1150.00', '2016-12-06 00:00:00', '1234567893', '2016-12-06 14:27:26'),
('13223', 'TRUE', 'Sirapat', 'NaRanong', '2400.00', '2016-11-30 00:00:00', '1234567893', '2016-12-06 15:53:44'),
('TRUE1276', 'TRUE', 'Sirapat', 'NaRanong', '100.00', '2016-11-29 00:00:00', NULL, NULL),
('TRUE1277', 'TRUE', 'SIrapat', 'NaRanong', '200.00', '2016-12-01 00:00:00', '1234567895', '2016-12-07 14:36:25');

-- --------------------------------------------------------

--
-- Table structure for table `OtherBankAccount`
--

CREATE TABLE `OtherBankAccount` (
  `bankName` varchar(20) NOT NULL,
  `accountNo` varchar(15) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OtherBankAccount`
--

INSERT INTO `OtherBankAccount` (`bankName`, `accountNo`, `firstName`, `LastName`) VALUES
('scb', '1222222220', 'Phond', 'Ph'),
('scb', '1222222221', 'Khajonpong', 'Kh'),
('ktb', '1333333330', 'Stephen', 'Turner'),
('ktb', '1333333331', 'Kurt', 'Rudahl');

-- --------------------------------------------------------

--
-- Table structure for table `Position`
--

CREATE TABLE `Position` (
  `position` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Position`
--

INSERT INTO `Position` (`position`) VALUES
('admin'),
('client'),
('company');

-- --------------------------------------------------------

--
-- Table structure for table `ThisBankBranchInfo`
--

CREATE TABLE `ThisBankBranchInfo` (
  `branchNo` char(4) NOT NULL,
  `branchName` varchar(20) NOT NULL,
  `province` varchar(20) NOT NULL,
  `address` varchar(128) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phoneNum` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ThisBankBranchInfo`
--

INSERT INTO `ThisBankBranchInfo` (`branchNo`, `branchName`, `province`, `address`, `email`, `phoneNum`) VALUES
('0000', 'Bangpakok', 'Bangkok', '42/10 SukSawat20 Bangpakok', 'slotBangpakok@mail.com', '029000000'),
('0001', 'Bangmod', 'Bangkok', '12/11 Bangmod10 Bangmod', 'slotBangmod@mail.com', '029000001'),
('0012', 'Bangsan', 'Chonburee', '23/88 Bangsan50 Bangsan', 'slotBangsan@mail.com', '029000002'),
('0023', 'PraSamutJaydee', 'SamutPrakarn', '99/02 PraSamutJaydee34 PraSamutJaydee', 'slotPraSamutJaydee@mail.com', '029000003');

-- --------------------------------------------------------

--
-- Table structure for table `TransferHistory`
--

CREATE TABLE `TransferHistory` (
  `transferNo` int(13) NOT NULL,
  `accountNo` varchar(15) DEFAULT NULL,
  `otherAccountNo` varchar(15) DEFAULT NULL,
  `bankName` varchar(20) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TransferHistory`
--

INSERT INTO `TransferHistory` (`transferNo`, `accountNo`, `otherAccountNo`, `bankName`, `amount`, `type`, `dateTime`) VALUES
(37, '1234567891', NULL, 'slot', '1150.00', 1, '2016-12-05 23:48:52'),
(38, '1234567891', NULL, 'slot', '1000.00', 0, '2016-12-05 23:48:52'),
(39, '1234567891', NULL, 'slot', '2150.00', 1, '2016-12-05 23:48:53'),
(40, '1234567891', NULL, 'slot', '4150.00', 0, '2016-12-05 23:48:53'),
(41, '1234567891', NULL, 'slot', '1150.00', 1, '2016-12-05 23:49:09'),
(42, '1234567891', NULL, 'slot', '1000.00', 0, '2016-12-05 23:49:09'),
(43, '1234567891', NULL, 'slot', '2150.00', 1, '2016-12-05 23:49:09'),
(44, '1234567891', NULL, 'slot', '4150.00', 0, '2016-12-05 23:49:09'),
(47, '1234567891', NULL, 'slot', '1000.00', 1, '2016-12-06 15:46:10'),
(48, '1234567892', NULL, 'slot', '2000.00', 1, '2016-12-06 15:46:10'),
(49, '1234567893', NULL, 'slot', '3000.00', 1, '2016-12-06 15:46:10'),
(50, '1234567894', NULL, 'slot', '4000.00', 1, '2016-12-06 15:46:10'),
(51, '1234567895', NULL, 'slot', '5000.00', 1, '2016-12-06 15:46:10'),
(52, '1234567896', NULL, 'slot', '6000.00', 1, '2016-12-06 15:46:10'),
(53, '1234567891', NULL, 'slot', '7000.00', 0, '2016-12-06 15:46:10'),
(54, '1234567892', NULL, 'slot', '8000.00', 0, '2016-12-06 15:46:10'),
(55, '1234567893', NULL, 'slot', '9000.00', 0, '2016-12-06 15:46:10'),
(56, '1234567894', NULL, 'slot', '1000.00', 0, '2016-12-06 15:46:10'),
(57, '1234567895', NULL, 'slot', '2000.00', 0, '2016-12-06 15:46:10'),
(58, '1234567896', NULL, 'slot', '3000.00', 0, '2016-12-06 15:46:10'),
(59, '1234567893', NULL, 'slot', '2400.00', 0, '2016-12-06 15:53:44'),
(59, '1234567892', NULL, 'slot', '2400.00', 1, '2016-12-06 15:53:44'),
(60, '1234567891', NULL, 'slot', '10.00', 0, '2016-12-07 14:08:49'),
(60, '1234567891', NULL, 'slot', '10.00', 1, '2016-12-07 14:08:49'),
(61, '1234567891', NULL, 'slot', '80.00', 0, '2016-12-07 14:18:14'),
(61, '1234567893', NULL, 'slot', '80.00', 1, '2016-12-07 14:18:14'),
(62, '1234567891', NULL, 'slot', '10.00', 0, '2016-12-07 14:22:18'),
(62, '1234567895', NULL, 'slot', '10.00', 1, '2016-12-07 14:22:18'),
(63, '1234567896', NULL, 'slot', '10.00', 0, '2016-12-07 14:30:23'),
(63, '1234567891', NULL, 'slot', '10.00', 1, '2016-12-07 14:30:23'),
(64, '1234567895', NULL, 'slot', '200.00', 0, '2016-12-07 14:36:25'),
(64, '1234567892', NULL, 'slot', '200.00', 1, '2016-12-07 14:36:25'),
(65, '1234567896', NULL, 'slot', '5.00', 0, '2016-12-07 14:46:13'),
(65, '1234567892', NULL, 'slot', '5.00', 1, '2016-12-07 14:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `UserAccount`
--

CREATE TABLE `UserAccount` (
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `accountNo` varchar(15) NOT NULL,
  `position` varchar(20) NOT NULL,
  `companyName` varchar(20) DEFAULT 'null',
  `available` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `UserAccount`
--

INSERT INTO `UserAccount` (`username`, `password`, `accountNo`, `position`, `companyName`, `available`) VALUES
('admike', '1234', '1234567896', 'admin', NULL, 1),
('bill_gate', 'gate123', '1922289090', 'company', 'Microsoft', 1),
('burgerMark', 'delicious', '9385934399', 'company', 'Marky Burger', 1),
('carleycutie', 'jamez1112', '7894546983', 'company', 'Carlson Sock', 1),
('champpizza', '1234', '1234567894', 'client', NULL, 0),
('lazadaUser', 'lazadaisthebest', '4746746746', 'company', 'Lazada', 1),
('seagull_za', 'password', '2012743218', 'company', 'Blizzard Entertainme', 1),
('theLastPirate', 'jackSparrow1234', '7439578397', 'company', 'Black Pearl', 1),
('usersirapat', '1234', '1234567891', 'client', NULL, 1),
('usersirapat', '1234', '1234567893', 'client', NULL, 1),
('usersirapat', '1234', '1234567895', 'client', NULL, 1),
('userSomsak', '!@123qwe', '1234567892', 'company', 'TRUE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `UserAccountInfo`
--

CREATE TABLE `UserAccountInfo` (
  `accountNo` varchar(15) NOT NULL,
  `bankName` varchar(20) NOT NULL,
  `branchNo` char(4) NOT NULL,
  `accountType` varchar(20) NOT NULL,
  `atmNo` varchar(16) DEFAULT NULL,
  `atmPassword` char(4) DEFAULT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `identificationNo` char(13) NOT NULL,
  `address` varchar(128) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phoneNum` varchar(10) NOT NULL,
  `birthDate` date NOT NULL,
  `balance` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `UserAccountInfo`
--

INSERT INTO `UserAccountInfo` (`accountNo`, `bankName`, `branchNo`, `accountType`, `atmNo`, `atmPassword`, `firstName`, `lastName`, `identificationNo`, `address`, `email`, `phoneNum`, `birthDate`, `balance`) VALUES
('1234567891', 'slot', '0000', 'saving', '1234567891234567', '1234', 'Sirapat', 'NaRanong', '1234567890123', '69/792 ChapterOne RatBurana Bangkok', 'sirapat@mail.com', '0812345678', '1996-08-27', '420.00'),
('1234567892', 'slot', '0000', 'saving', '1234567891234568', '1234', 'Somsak', 'Saelim', '1234567890124', '10/23 PrachaUtit54 Bangmod Bangkok', 'Somsak@mail.com', '0812345679', '1980-02-22', '203756.00'),
('1234567893', 'slot', '0000', 'saving', '1234567891234569', '1234', 'Sirapat', 'NaRanong', '1234567890123', '69/792 ChapterOne RatBurana Bangkok', 'sirapat@mail.com', '0812345678', '1996-08-27', '1496530.00'),
('1234567894', 'slot', '0000', 'saving', '1234567891234570', '1234', 'Thanaboon', 'Muangwong', '1234567890126', '12/23 Chonburee', 'champ_pizza@mail.com', '0812345679', '1996-03-23', '1000.75'),
('1234567895', 'slot', '0000', 'saving', '1234567891234571', '1234', 'Sirapat', 'NaRanong', '1234567890123', '69/792 ChapterOne RatBurana Bangkok', 'sirapat@mail.com', '0812345678', '1996-08-27', '12155.00'),
('1234567896', 'slot', '0000', 'saving', '1234567891234567', '1234', 'Mike', 'Udom', '1234567890127', '23/21 Suanton Putthabucha Bangkok', 'mike@mail.com', '0812345680', '1996-02-22', '85.00'),
('1922289090', 'slot', '0001', 'saving', '1234567891234590', '1234', 'Bill', 'Gate', '1234521401232', '43/4 Bangbuathong Nonthaburi', 'bill_gate@mail.com', '0962155522', '1967-08-12', '10000000.00'),
('2012743218', 'slot', '0012', 'saving', '3246782436282377', '2124', 'Steve', 'Seagull', '3333443299991', '54/3 Satupradit Bangkok', 'steveSeagull@mail.com', '0977777777', '1990-01-01', '9000000.00'),
('4746746746', 'slot', '0000', 'saving', '0088788756757656', '1234', 'Link', 'Lazada', '2384728934789', '4/2 Charoennakorn Bangkok', 'link@lazada.com', '0912381231', '1991-08-28', '70000.00'),
('7439578397', 'slot', '0000', 'saving', '1792478923478937', '1234', 'Jack', 'Sparrow', '3432423321110', '1/1 Thungkru Bangmod Bangkok', 'jack@pirate.com', '0912726387', '1999-02-01', '65000.00'),
('7894546983', 'slot', '0001', 'saving', '8749237493273289', '8972', 'James', 'Carley', '2314312432432', '900/1 Bangyai Nonthaburi', 'jamey_ez@mail.com', '0821111111', '1996-03-28', '1000.00'),
('9385934399', 'slot', '0023', 'saving', '8249484848583637', '1266', 'Mark', 'Suckburger', '2342112222112', '54/10 Rattanathibeth Nonthaburi', 'mark@burger.com', '0855677899', '1991-12-02', '1000.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AccountType`
--
ALTER TABLE `AccountType`
  ADD PRIMARY KEY (`accountType`);

--
-- Indexes for table `BankInfo`
--
ALTER TABLE `BankInfo`
  ADD PRIMARY KEY (`bankName`);

--
-- Indexes for table `Bill_info`
--
ALTER TABLE `Bill_info`
  ADD PRIMARY KEY (`referenceNo`),
  ADD KEY `Bill_info_fk0` (`companyName`),
  ADD KEY `Bill_info_fk1` (`accountNo`);

--
-- Indexes for table `OtherBankAccount`
--
ALTER TABLE `OtherBankAccount`
  ADD PRIMARY KEY (`accountNo`),
  ADD KEY `OtherBankAccount_fk0` (`bankName`);

--
-- Indexes for table `Position`
--
ALTER TABLE `Position`
  ADD PRIMARY KEY (`position`);

--
-- Indexes for table `ThisBankBranchInfo`
--
ALTER TABLE `ThisBankBranchInfo`
  ADD PRIMARY KEY (`branchNo`);

--
-- Indexes for table `TransferHistory`
--
ALTER TABLE `TransferHistory`
  ADD PRIMARY KEY (`transferNo`,`type`),
  ADD KEY `TransferHistory_fk0` (`accountNo`),
  ADD KEY `TransferHistory_fk1` (`otherAccountNo`),
  ADD KEY `TransferHistory_fk2` (`bankName`);

--
-- Indexes for table `UserAccount`
--
ALTER TABLE `UserAccount`
  ADD PRIMARY KEY (`username`,`accountNo`),
  ADD UNIQUE KEY `companyName` (`companyName`),
  ADD KEY `UserAccount_fk0` (`accountNo`),
  ADD KEY `UserAccount_fk1` (`position`);

--
-- Indexes for table `UserAccountInfo`
--
ALTER TABLE `UserAccountInfo`
  ADD PRIMARY KEY (`accountNo`),
  ADD KEY `UserAccountInfo_fk0` (`bankName`),
  ADD KEY `UserAccountInfo_fk1` (`branchNo`),
  ADD KEY `UserAccountInfo_fk2` (`accountType`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `TransferHistory`
--
ALTER TABLE `TransferHistory`
  MODIFY `transferNo` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Bill_info`
--
ALTER TABLE `Bill_info`
  ADD CONSTRAINT `Bill_info_fk0` FOREIGN KEY (`companyName`) REFERENCES `UserAccount` (`companyName`),
  ADD CONSTRAINT `Bill_info_fk1` FOREIGN KEY (`accountNo`) REFERENCES `UserAccountInfo` (`accountNo`);

--
-- Constraints for table `OtherBankAccount`
--
ALTER TABLE `OtherBankAccount`
  ADD CONSTRAINT `OtherBankAccount_fk0` FOREIGN KEY (`bankName`) REFERENCES `BankInfo` (`bankName`);

--
-- Constraints for table `TransferHistory`
--
ALTER TABLE `TransferHistory`
  ADD CONSTRAINT `TransferHistory_fk0` FOREIGN KEY (`accountNo`) REFERENCES `UserAccountInfo` (`accountNo`),
  ADD CONSTRAINT `TransferHistory_fk1` FOREIGN KEY (`otherAccountNo`) REFERENCES `OtherBankAccount` (`accountNo`),
  ADD CONSTRAINT `TransferHistory_fk2` FOREIGN KEY (`bankName`) REFERENCES `BankInfo` (`bankName`);

--
-- Constraints for table `UserAccount`
--
ALTER TABLE `UserAccount`
  ADD CONSTRAINT `UserAccount_fk0` FOREIGN KEY (`accountNo`) REFERENCES `UserAccountInfo` (`accountNo`),
  ADD CONSTRAINT `UserAccount_fk1` FOREIGN KEY (`position`) REFERENCES `Position` (`position`);

--
-- Constraints for table `UserAccountInfo`
--
ALTER TABLE `UserAccountInfo`
  ADD CONSTRAINT `UserAccountInfo_fk0` FOREIGN KEY (`bankName`) REFERENCES `BankInfo` (`bankName`),
  ADD CONSTRAINT `UserAccountInfo_fk1` FOREIGN KEY (`branchNo`) REFERENCES `ThisBankBranchInfo` (`branchNo`),
  ADD CONSTRAINT `UserAccountInfo_fk2` FOREIGN KEY (`accountType`) REFERENCES `AccountType` (`accountType`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
