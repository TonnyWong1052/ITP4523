-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:8889
-- 產生時間： 2022 年 07 月 07 日 17:40
-- 伺服器版本： 5.7.34
-- PHP 版本： 8.0.8

drop database  IF EXISTS ProjectDB;
create database projectDB character set utf8;
use projectDB;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `ProjectDB`
--

-- --------------------------------------------------------

--
-- 資料表結構 `Customer`
--

CREATE TABLE `Customer` (
  `customerEmail` varchar(50) NOT NULL,
  `customerName` varchar(100) NOT NULL,
  `phoneNumber` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Customer`
--

INSERT INTO `Customer` (`customerEmail`, `customerName`, `phoneNumber`) VALUES
('mary@gmail.com', 'Mary', '58674321'),
('tom@gmail.com', 'Tom', '57568291'),
('tonny@gmail.com', 'Tonny', '21895222');

-- --------------------------------------------------------

--
-- 資料表結構 `Item`
--

CREATE TABLE `Item` (
  `itemID` int(11) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `itemDescription` text,
  `stockQuantity` int(11) NOT NULL DEFAULT '0',
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Item`
--

INSERT INTO `Item` (`itemID`, `itemName`, `itemDescription`, `stockQuantity`, `price`) VALUES
(1, 'NOVEL NF4091 9”All-way Strong Wind Circulation Fan', 'Simple Design with 3D stereo blower Turbo super strong wind up', 50, 500),
(2, 'CS-RZ24YKA 2.5 HP Inverter Split Type Heat Pump Air-Conditioner', '2.5 HP (Heat Pump Model - With Remote Control)', 99, 20000),
(3, 'QN100B Neo QLED 2K LED LCD TV', 'Infinity Screen, More immersive viewing experience', 79, 13000),
(4, 'M33 5G Smartphone', '6.6” FHD+ Infinity-V Display, 120Hz refresh rate 50MP main camera equipped with small ', 300, 2000),
(5, 'Mackook Air 2022 M2', 'Created by Apple', 20, 8999),
(6, 'MacBook Pro 2022 M2', 'Created by Apple', 0, 14000),
(7, 'IPad Air 2022 M1', 'Created by Apple', 2, 4999),
(8, 'AirPod Pro', 'Created by Apple', 3, 1700);

-- --------------------------------------------------------

--
-- 資料表結構 `ItemOrders`
--

CREATE TABLE `ItemOrders` (
  `orderID` varchar(255) NOT NULL,
  `itemID` int(11) NOT NULL,
  `orderQuantity` int(5) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `ItemOrders`
--

INSERT INTO `ItemOrders` (`orderID`, `itemID`, `orderQuantity`, `price`) VALUES
('1', 1, 2, 1000),
('1', 4, 1, 2000),
('2', 3, 1, 13000),
('4', 3, 1, 13000),
('4', 4, 1, 2000),
('5', 1, 1, 500),
('5', 5, 1, 8999),
('6', 3, 1, 13000),
('6', 4, 1, 2000),
('7', 3, 1, 13000);

-- --------------------------------------------------------

--
-- 資料表結構 `Orders`
--

CREATE TABLE `Orders` (
  `orderID` varchar(255) NOT NULL,
  `customerEmail` varchar(50) NOT NULL,
  `staffID` varchar(50) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deliveryAddress` varchar(255) DEFAULT NULL,
  `deliveryDate` date DEFAULT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Orders`
--

INSERT INTO `Orders` (`orderID`, `customerEmail`, `staffID`, `dateTime`, `deliveryAddress`, `deliveryDate`, `totalPrice`) VALUES
('1', 'mary@gmail.com', 's0001', '2022-03-24 13:12:13', NULL, NULL, 2910),
('2', 'tom@gmail.com', 's0001', '2022-04-10 14:10:20', 'Flat 8, Chates Farm Court, John Street, Hong Kong', '2022-04-15', 11440),
('4', 'mary@gmail.com', 's0004', '2022-07-07 12:41:46', NULL, NULL, 13200),
('5', 'tom@gmail.com', 's0004', '2022-07-07 12:42:35', NULL, NULL, 8739.08),
('6', 'mary@gmail.com', 's003', '2022-07-07 17:15:35', NULL, NULL, 13200),
('7', 'mary@gmail.com', 's003', '2022-07-07 17:39:50', NULL, NULL, 11440);

-- --------------------------------------------------------

--
-- 資料表結構 `Staff`
--

CREATE TABLE `Staff` (
  `staffID` varchar(50) NOT NULL,
  `staffName` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Staff`
--

INSERT INTO `Staff` (`staffID`, `staffName`, `password`, `position`) VALUES
('s0001', 'Chan Tai Man', 'a123', 'Staff'),
('s0002', 'Wong Ka Ho', 'b123', 'Manager'),
('s0004', 'Chan Xiu Ming', 's0004', 'Staff'),
('s0005', 'Wong Hoi Ming', 's0005', 'Manager'),
('s003', 'Chan ka Chung', 'c123', 'Staff');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`customerEmail`);

--
-- 資料表索引 `Item`
--
ALTER TABLE `Item`
  ADD PRIMARY KEY (`itemID`);

--
-- 資料表索引 `ItemOrders`
--
ALTER TABLE `ItemOrders`
  ADD PRIMARY KEY (`orderID`,`itemID`),
  ADD KEY `FKItemOrders932280` (`itemID`),
  ADD KEY `FKItemOrders159103` (`orderID`);

--
-- 資料表索引 `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `FKOrders837071` (`customerEmail`),
  ADD KEY `FKOrders846725` (`staffID`);

--
-- 資料表索引 `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`staffID`);

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `ItemOrders`
--
ALTER TABLE `ItemOrders`
  ADD CONSTRAINT `FKItemOrders159103` FOREIGN KEY (`orderID`) REFERENCES `Orders` (`orderID`),
  ADD CONSTRAINT `FKItemOrders932280` FOREIGN KEY (`itemID`) REFERENCES `Item` (`itemID`);

--
-- 資料表的限制式 `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `FKOrders837071` FOREIGN KEY (`customerEmail`) REFERENCES `Customer` (`customerEmail`),
  ADD CONSTRAINT `FKOrders846725` FOREIGN KEY (`staffID`) REFERENCES `Staff` (`staffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
