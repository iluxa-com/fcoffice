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
-- 数据库: `alice_stat`
--

-- --------------------------------------------------------

--
-- 表的结构 `action_record`
--

DROP TABLE IF EXISTS `action_record`;
CREATE TABLE IF NOT EXISTS `action_record` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型',
  `val1` int(10) NOT NULL COMMENT '值1',
  `val2` int(10) NOT NULL COMMENT '值2',
  `val3` int(10) NOT NULL COMMENT '值3',
  `val4` text NOT NULL COMMENT '值4',
  `time` int(10) unsigned NOT NULL COMMENT '时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='动作记录';

-- --------------------------------------------------------

--
-- 表的结构 `crontab_data`
--

DROP TABLE IF EXISTS `crontab_data`;
CREATE TABLE IF NOT EXISTS `crontab_data` (
  `date` date NOT NULL COMMENT '时间',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`date`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='计划任务统计数据表';

-- --------------------------------------------------------

--
-- 表的结构 `step_day`
--

DROP TABLE IF EXISTS `step_day`;
CREATE TABLE IF NOT EXISTS `step_day` (
  `date` date NOT NULL COMMENT '时间',
  `s1` int(10) unsigned NOT NULL,
  `s2` int(10) unsigned NOT NULL,
  `s3` int(10) unsigned NOT NULL,
  `s4` int(10) unsigned NOT NULL,
  `s5` int(10) unsigned NOT NULL,
  `s6` int(10) unsigned NOT NULL,
  `s7` int(10) unsigned NOT NULL,
  `s8` int(10) unsigned NOT NULL,
  `s9` int(10) unsigned NOT NULL,
  `s10` int(10) unsigned NOT NULL,
  `s11` int(10) unsigned NOT NULL,
  `s12` int(10) unsigned NOT NULL,
  `s13` int(10) unsigned NOT NULL,
  `s14` int(10) unsigned NOT NULL,
  `s15` int(10) unsigned NOT NULL,
  `s16` int(10) unsigned NOT NULL,
  `s17` int(10) unsigned NOT NULL,
  `s18` int(10) unsigned NOT NULL,
  `s19` int(10) unsigned NOT NULL,
  `s20` int(10) unsigned NOT NULL,
  `s21` int(10) unsigned NOT NULL,
  `s22` int(10) unsigned NOT NULL,
  `s23` int(10) unsigned NOT NULL,
  `s24` int(10) unsigned NOT NULL,
  `s25` int(10) unsigned NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日用户步骤统计数据表';

-- --------------------------------------------------------

--
-- 表的结构 `user_day`
--

DROP TABLE IF EXISTS `user_day`;
CREATE TABLE IF NOT EXISTS `user_day` (
  `date` date NOT NULL COMMENT '时间',
  `total` int(10) unsigned NOT NULL COMMENT '总用户',
  `active` int(10) unsigned NOT NULL COMMENT '活跃用户',
  `active2` int(10) unsigned NOT NULL COMMENT '活跃用户(等级>=4)',
  `back_user1` int(10) unsigned NOT NULL COMMENT '一天回头数',
  `back_user3` int(10) unsigned NOT NULL COMMENT '三天回头数',
  `back_user7` int(10) unsigned NOT NULL COMMENT '七天回头数',
  `invite_send` int(10) unsigned NOT NULL COMMENT '邀请发送数',
  `invite_accept` int(10) unsigned NOT NULL COMMENT '邀请接受数',
  `mode_0_success` int(10) unsigned NOT NULL COMMENT '普通模式闯关成功数',
  `mode_0_fail` int(10) unsigned NOT NULL COMMENT '普通模式闯关失败数',
  `mode_1_success` int(10) unsigned NOT NULL COMMENT '挑战模式闯关成功数',
  `mode_1_fail` int(10) unsigned NOT NULL COMMENT '挑战模式闯关失败数',
  `mode_2_success` int(10) unsigned NOT NULL COMMENT '隐藏模式闯关成功数',
  `mode_2_fail` int(10) unsigned NOT NULL COMMENT '隐藏模式闯关失败数',
  `pay_num` int(10) unsigned NOT NULL COMMENT '充值人数',
  `pay_in` int(10) unsigned NOT NULL COMMENT '充值',
  `pay_out` int(10) unsigned NOT NULL COMMENT '消费',
  `pay_first` int(10) unsigned NOT NULL COMMENT '新充值用户数',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='天用户统计数据表';

-- --------------------------------------------------------

--
-- 表的结构 `user_hour`
--

DROP TABLE IF EXISTS `user_hour`;
CREATE TABLE IF NOT EXISTS `user_hour` (
  `date` datetime NOT NULL COMMENT '时间',
  `total` int(10) unsigned NOT NULL COMMENT '总用户',
  `active` int(10) unsigned NOT NULL COMMENT '活跃用户',
  `pay_in` int(10) unsigned NOT NULL COMMENT '充值',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时用户统计数据表';

-- --------------------------------------------------------

--
-- 表的结构 `user_month`
--

DROP TABLE IF EXISTS `user_month`;
CREATE TABLE IF NOT EXISTS `user_month` (
  `date` date NOT NULL COMMENT '时间',
  `total` int(10) unsigned NOT NULL COMMENT '总用户',
  `active` int(10) unsigned NOT NULL COMMENT '活跃用户',
  `active2` int(10) unsigned NOT NULL COMMENT '活跃用户(月登录次数>=2)',
  `active3` int(10) unsigned NOT NULL COMMENT '活跃用户(月登录次数>=3 && 等级>=4)',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='月用户统计数据表';
