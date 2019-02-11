-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 02 月 11 日 15:18
-- 伺服器版本： 8.0.15
-- PHP 版本： 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `statistical_pollng_system`
--

-- --------------------------------------------------------

--
-- 資料表結構 `jobstatistics`
--

CREATE TABLE `jobstatistics` (
  `JobID` mediumint(11) NOT NULL,
  `member_name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `NickName` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `JobDetail` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Remarks_Avaliable` tinyint(1) NOT NULL,
  `Remarks` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Minutes` int(11) NOT NULL,
  `JobDate` date NOT NULL,
  `AddedTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `jobstatistics`
--

INSERT INTO `jobstatistics` (`JobID`, `member_name`, `NickName`, `JobDetail`, `Remarks_Avaliable`, `Remarks`, `Minutes`, `JobDate`, `AddedTime`) VALUES
(1, 'evilhawk00', 'System Administrator', 'Build PCCU4B ver1.0', 1, '1.Basic Login Function 2.Ranking System 3.Basic Job List entries', 480, '2016-10-06', '2016-10-06 19:58:36'),
(2, 'evilhawk00', 'System Administrator', 'Build PCCU4B Ver1.1', 1, '1.add remarks 2.add date-time selector 3.add splash pop-ups 4.update database structure', 300, '2016-10-08', '2016-10-08 03:11:16'),
(10, 'TestUser6', 'Test6', 'I\'m glad to Login to this system', 0, '', 20, '2016-10-08', '2016-10-08 05:06:45'),
(4, 'TestUser1', 'Test1', 'Test input Here', 1, 'Test Remarks here', 240, '2016-09-30', '2016-10-08 04:54:39'),
(5, 'TestUser1', 'Test1', 'Test entry', 0, '', 360, '2016-10-04', '2016-10-08 04:58:28'),
(6, 'TestUser2', 'Test2', 'testing123', 1, 'Make sure this works', 120, '2016-10-05', '2016-10-08 04:59:08'),
(7, 'TestUser3', 'Test3', 'This seems stable', 0, '', 55, '2016-10-06', '2016-10-08 04:59:27'),
(8, 'TestUser4', 'Test4', 'Hello World :)', 0, '', 65, '2016-10-06', '2016-10-08 04:59:43'),
(9, 'TestUser3', 'Test3', 'Get used to this system', 1, 'Understand how this works', 60, '2016-10-07', '2016-10-08 05:01:53'),
(3, 'evilhawk00', 'System Administrator', 'Build PCCU4B Ver1.2', 1, '1.Now support display job history for each user 2.Display chart pie', 15, '2016-10-09', '2016-10-09 08:46:29'),
(12, 'TestUser7', 'Test7', 'Raise funds for orphans', 0, '', 10, '2016-10-18', '2016-10-17 23:20:28'),
(13, 'TestUser9', 'Test9', 'Collecting funds', 0, '', 480, '2016-10-24', '2016-10-24 03:43:06'),
(14, 'TestUser5', 'Test5', 'Released the product, Sent out invitation emails', 0, '', 50, '2016-10-26', '2016-10-26 08:54:36'),
(15, 'TestUser10', 'Test10', 'Meeting with co-workers', 0, '', 90, '2016-10-27', '2016-10-27 03:52:11'),
(11, 'TestUser4', 'Test4', 'Finish the job we planned', 0, '', 240, '2016-10-28', '2016-10-28 09:37:53'),
(16, 'evilhawk00', 'System Administrator', 'Release this project to public as an open sourced project', 1, 'Feel free to use it', 5, '2019-02-11', '2019-02-11 22:59:44'),
(17, 'evilhawk00', 'System Administrator', 'Final Test', 1, 'This is Functional', 5, '2019-02-13', '2019-02-11 23:08:46');

-- --------------------------------------------------------

--
-- 資料表結構 `members`
--

CREATE TABLE `members` (
  `UID` int(5) NOT NULL,
  `DisplayName` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `NickName` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `LastLoginTime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `members`
--

INSERT INTO `members` (`UID`, `DisplayName`, `NickName`, `Email`, `Password`, `LastLoginTime`) VALUES
(1, 'evilhawk00', 'System Administrator', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', '2019-02-11 22:29:50'),
(5, 'TestUser4', 'Test4', 'test4@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(6, 'TestUser5', 'Test5', 'test5@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(7, 'TestUser6', 'Test6', 'test6@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(8, 'TestUser7', 'Test7', 'test7@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(9, 'TestUser8', 'Test8', 'test8@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(10, 'TestUser9', 'Test9', 'test9@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(11, 'TestUser10', 'Test10', 'test10@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(4, 'TestUser3', 'Test3', 'test3@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(3, 'TestUser2', 'Test2', 'test2@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL),
(2, 'TestUser1', 'Test1', 'test1@test.com', '098f6bcd4621d373cade4e832627b4f6', NULL);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `jobstatistics`
--
ALTER TABLE `jobstatistics`
  ADD PRIMARY KEY (`JobID`);

--
-- 資料表索引 `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`UID`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `jobstatistics`
--
ALTER TABLE `jobstatistics`
  MODIFY `JobID` mediumint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `members`
--
ALTER TABLE `members`
  MODIFY `UID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
