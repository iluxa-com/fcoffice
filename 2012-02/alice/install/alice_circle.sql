-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- 主机: 192.168.0.253
-- 生成日期: 2011 年 05 月 05 日 17:01
-- 服务器版本: 5.1.49
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `alice_circle`
--

-- --------------------------------------------------------

--
-- 表的结构 `friend_task`
--

DROP TABLE IF EXISTS `friend_task`;
CREATE TABLE IF NOT EXISTS `friend_task` (
  `from_user_id` int(10) unsigned NOT NULL COMMENT '邀请人用户ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `task_id` smallint(5) unsigned NOT NULL COMMENT '任务ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='好友任务表';

-- --------------------------------------------------------

--
-- 表的结构 `leave_msg`
--

DROP TABLE IF EXISTS `leave_msg`;
CREATE TABLE IF NOT EXISTS `leave_msg` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `from_user_id` int(10) unsigned NOT NULL COMMENT '留言人ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `msg` varchar(1000) NOT NULL COMMENT '留言',
  `is_read` tinyint(1) unsigned NOT NULL COMMENT '是否阅读',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';

-- --------------------------------------------------------

--
-- 表的结构 `news_log`
--

DROP TABLE IF EXISTS `news_log`;
CREATE TABLE IF NOT EXISTS `news_log` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `log_type` tinyint(3) unsigned NOT NULL COMMENT '日志类型',
  `content` text NOT NULL COMMENT '日志内容(json_encode序列化数据)',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='动态日志表';

-- --------------------------------------------------------

--
-- 表的结构 `other_msg`
--

DROP TABLE IF EXISTS `other_msg`;
CREATE TABLE IF NOT EXISTS `other_msg` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `from_user_id` int(10) unsigned NOT NULL COMMENT '留言人ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `item_id` int(10) unsigned NOT NULL COMMENT '道具ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';

-- --------------------------------------------------------

--
-- 表的结构 `request_gift`
--

DROP TABLE IF EXISTS `request_gift`;
CREATE TABLE IF NOT EXISTS `request_gift` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `from_user_id` int(10) unsigned NOT NULL COMMENT '索求人用户ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `item_id` smallint(5) unsigned NOT NULL COMMENT '道具ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='索求礼物表';

-- --------------------------------------------------------

--
-- 表的结构 `send_gift`
--

DROP TABLE IF EXISTS `send_gift`;
CREATE TABLE IF NOT EXISTS `send_gift` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `from_user_id` int(10) unsigned NOT NULL COMMENT '送礼人用户ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `log_type` tinyint(3) unsigned NOT NULL COMMENT '日志类型(0=免费,1=背包)',
  `item_id` smallint(5) unsigned NOT NULL COMMENT '道具ID',
  `num` tinyint(3) unsigned NOT NULL COMMENT '数量',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='赠送礼物表';

-- --------------------------------------------------------

--
-- 表的结构 `chest_invite`
--

DROP TABLE IF EXISTS `chest_invite`;
CREATE TABLE IF NOT EXISTS `chest_invite` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `from_user_id` int(10) unsigned NOT NULL COMMENT '邀请人用户ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='宝箱邀请表';

-- --------------------------------------------------------

--
-- 表的结构 `silver_log`
--

DROP TABLE IF EXISTS `silver_log`;
CREATE TABLE IF NOT EXISTS `silver_log` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `before_amount` int(10) unsigned NOT NULL COMMENT '钱的数量(变动前)',
  `change_amount` int(10) NOT NULL COMMENT '变动的数量(正数表示增加,负数表示减少)',
  `after_amount` int(10) unsigned NOT NULL COMMENT '钱的数量(变动后)',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='银币日志表';

-- --------------------------------------------------------

--
-- 表的结构 `gold_recharge`
--

DROP TABLE IF EXISTS `gold_recharge`;
CREATE TABLE IF NOT EXISTS `gold_recharge` (
  `order_id` varchar(32) NOT NULL COMMENT '订单编号',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型(0=正式,1=测试)',
  `src_num` int(10) NOT NULL COMMENT '数量(平台币)',
  `dst_num` int(10) NOT NULL COMMENT '数量(游戏币)',
  `finished` tinyint(3) unsigned NOT NULL COMMENT '完成状态(0=未完成,1=已完成)',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='金币充值表';

-- --------------------------------------------------------

--
-- 表的结构 `gold_log`
--

DROP TABLE IF EXISTS `gold_log`;
CREATE TABLE IF NOT EXISTS `gold_log` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型(0=充值,1=消费)',
  `num` int(10) NOT NULL COMMENT '消费数量',
  `remain` int(10) NOT NULL COMMENT '剩余数量',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='金币日志表';