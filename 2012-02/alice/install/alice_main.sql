-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- 主机: 192.168.0.222
-- 生成日期: 2011 年 07 月 29 日 14:34
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

-- --------------------------------------------------------

--
-- 表的结构 `area_data`
--

DROP TABLE IF EXISTS `area_data`;
CREATE TABLE IF NOT EXISTS `area_data` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区ID',
  `zone_id` int(10) unsigned NOT NULL COMMENT '区域ID',
  `place_id` int(10) unsigned NOT NULL COMMENT '地点ID',
  `name` varchar(60) NOT NULL COMMENT '地点名称',
  `description` varchar(1000) NOT NULL COMMENT '地点描述',
  `need_grade` tinyint(3) unsigned NOT NULL COMMENT '限制等级',
  `need_energy` tinyint(3) unsigned NOT NULL COMMENT '体力消耗点数',
  `is_open` tinyint(1) unsigned NOT NULL COMMENT '是否开放(0=关闭,1=开放)',
  `node_count` tinyint(1) unsigned NOT NULL COMMENT '节点数',
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='地点数据表';

-- --------------------------------------------------------

--
-- 表的结构 `debug_log`
--

DROP TABLE IF EXISTS `debug_log`;
CREATE TABLE IF NOT EXISTS `debug_log` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `log_type` smallint(5) unsigned NOT NULL COMMENT '日志类型',
  `log_data` text NOT NULL COMMENT '日志数据',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='调试日志表';

-- --------------------------------------------------------

--
-- 表的结构 `exchange_data`
--

DROP TABLE IF EXISTS `exchange_data`;
CREATE TABLE IF NOT EXISTS `exchange_data` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `name` varchar(20) NOT NULL COMMENT '组合名称',
  `item_ids` text NOT NULL COMMENT '道具ID列表(逗号分隔)',
  `reward` text NOT NULL COMMENT '兑换奖励(json编码的字符串)',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏兑换数据定义';

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` varchar(32) NOT NULL,
  `p1` tinyint(3) unsigned NOT NULL,
  `p2` tinyint(3) unsigned NOT NULL,
  `p3` tinyint(3) unsigned NOT NULL,
  `p4` tinyint(3) unsigned NOT NULL,
  `p5` tinyint(3) unsigned NOT NULL,
  `p6` tinyint(3) unsigned NOT NULL,
  `p7` tinyint(3) unsigned NOT NULL,
  `p8` tinyint(3) unsigned NOT NULL,
  `p9` tinyint(3) unsigned NOT NULL,
  `p10` tinyint(3) unsigned NOT NULL,
  `p11` tinyint(3) unsigned NOT NULL,
  `p12` tinyint(3) unsigned NOT NULL,
  `p13` tinyint(3) unsigned NOT NULL,
  `p14` tinyint(3) unsigned NOT NULL,
  `p15` tinyint(3) unsigned NOT NULL,
  `p16` tinyint(3) unsigned NOT NULL,
  `p17` tinyint(3) unsigned NOT NULL,
  `p18` tinyint(3) unsigned NOT NULL,
  `p19` tinyint(3) unsigned NOT NULL,
  `p20` tinyint(3) unsigned NOT NULL,
  `p21` tinyint(3) unsigned NOT NULL,
  `p22` tinyint(3) unsigned NOT NULL,
  `p23` tinyint(3) unsigned NOT NULL,
  `p24` tinyint(3) unsigned NOT NULL,
  `p25` tinyint(3) unsigned NOT NULL,
  `p26` tinyint(3) unsigned NOT NULL,
  `p27` tinyint(3) unsigned NOT NULL,
  `p28` tinyint(3) unsigned NOT NULL,
  `p29` tinyint(3) unsigned NOT NULL,
  `p30` tinyint(3) unsigned NOT NULL,
  `suggestion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='调查反馈表';

-- --------------------------------------------------------

--
-- 表的结构 `item_data`
--

DROP TABLE IF EXISTS `item_data`;
CREATE TABLE IF NOT EXISTS `item_data` (
  `item_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '道具ID',
  `item_name` varchar(20) NOT NULL COMMENT '道具名称',
  `link_name` varchar(100) NOT NULL COMMENT '链接名称(AS用)',
  `description` text NOT NULL COMMENT '道具描述',
  `buyable` tinyint(1) NOT NULL COMMENT '能否购买',
  `useable` tinyint(1) NOT NULL COMMENT '能否使用',
  `clickable` tinyint(1) NOT NULL COMMENT '能否点击',
  `dbclickable` tinyint(1) NOT NULL COMMENT '能否双击',
  `dragable` tinyint(1) NOT NULL COMMENT '能否拖动',
  `grade` smallint(5) unsigned NOT NULL COMMENT '购买等级限制',
  `silver` int(10) unsigned NOT NULL COMMENT '金币单价',
  `gold` int(10) unsigned NOT NULL COMMENT 'FH币单价',
  `extra_info` text NOT NULL COMMENT '附加信息(json编码的对象或都为空)',
  `filename` varchar(255) NOT NULL COMMENT '文件名(AS用)',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='道具数据表';

-- --------------------------------------------------------

--
-- 表的结构 `place_data`
--

DROP TABLE IF EXISTS `place_data`;
CREATE TABLE IF NOT EXISTS `place_data` (
  `place_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地点ID',
  `zone_id` int(10) unsigned NOT NULL COMMENT '区域ID',
  `name` varchar(60) NOT NULL COMMENT '地点名称',
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型(0=城镇,1=闯关)',
  `need_grade` tinyint(3) unsigned NOT NULL COMMENT '需要等级',
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='地点数据表';

-- --------------------------------------------------------

--
-- 表的结构 `task_data`
--

DROP TABLE IF EXISTS `task_data`;
CREATE TABLE IF NOT EXISTS `task_data` (
  `task_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `zone_id` smallint(5) unsigned NOT NULL COMMENT '区域ID',
  `place_id` smallint(5) unsigned NOT NULL COMMENT '地区ID',
  `area_id` smallint(5) unsigned NOT NULL COMMENT '地区ID',
  `npc_id` smallint(5) unsigned NOT NULL COMMENT 'NPC ID',
  `type` tinyint(1) unsigned NOT NULL COMMENT '任务类型(0=主线任务,1=系统任务,2=NPC任务,3=新手任务)',
  `target` smallint(5) unsigned NOT NULL COMMENT '目标NPC',
  `image` varchar(60) NOT NULL COMMENT '图标',
  `grade` tinyint(3) unsigned NOT NULL COMMENT '任务需要等级',
  `name` varchar(255) NOT NULL COMMENT '任务名称',
  `need` text NOT NULL COMMENT '任务需求(json编码的对象或者为空)',
  `reward` text NOT NULL COMMENT '任务奖励(json编码的对象或者为空)',
  `description1` text NOT NULL COMMENT '任务前描述',
  `description2` text NOT NULL COMMENT '任务中描述',
  `description3` text NOT NULL COMMENT '任务完成描述',
  `npc_talk` text NOT NULL COMMENT 'NPC对话内容(多条用@分隔)',
  `manhua` smallint(5) unsigned NOT NULL COMMENT '漫画ID',
  `level_id` smallint(5) unsigned NOT NULL COMMENT '关联关卡ID',
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='任务数据表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE IF NOT EXISTS `admin_user` (
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `name` varchar(30) NOT NULL COMMENT '姓名',
  `roles` varchar(255) NOT NULL COMMENT '角色',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `last_login` int(10) unsigned NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL COMMENT '最后登录IP',
  `login_times` int(10) unsigned NOT NULL COMMENT '登录次数',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- --------------------------------------------------------

--
-- 表的结构 `zone_data`
--

DROP TABLE IF EXISTS `zone_data`;
CREATE TABLE IF NOT EXISTS `zone_data` (
  `zone_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '区域ID',
  `name` varchar(60) NOT NULL COMMENT '区域名称',
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='区域数据表';
