-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- 主机: 192.168.0.222
-- 生成日期: 2011 年 09 月 02 日 14:25
-- 服务器版本: 5.1.49
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `alice_main`
--

--
-- 转存表中的数据 `place_data`
--

INSERT INTO `place_data` (`place_id`, `zone_id`, `name`, `type`, `need_grade`) VALUES
(1, 1, '地点一', 1, 0),
(2, 2, '地点二', 1, 0),
(10001, 1, '彩虹村', 0, 0),
(10002, 2, '洪特尔聚集地', 0, 0);
