-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-07-10 01:59:24
-- 服务器版本： 5.5.47-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wuan`
--

-- --------------------------------------------------------

--
-- 表的结构 `authorization`
--

CREATE TABLE IF NOT EXISTS `authorization` (
  `area_dif` varchar(2) COLLATE utf8_bin NOT NULL COMMENT '权限位置区分',
  `aser_dif` varchar(2) COLLATE utf8_bin NOT NULL COMMENT '权限区分',
  `note` varchar(8) COLLATE utf8_bin NOT NULL COMMENT '说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='权限表';

--
-- 转存表中的数据 `authorization`
--

INSERT INTO `authorization` (`area_dif`, `aser_dif`, `note`) VALUES
('01', '01', '用户-会员'),
('01', '02', '用户-管理员'),
('01', '03', '用户-总管理'),
('02', '01', '星球-创建者'),
('02', '02', '星球-管理'),
('02', '03', '星球-成员');

-- --------------------------------------------------------

--
-- 表的结构 `group_base`
--

CREATE TABLE IF NOT EXISTS `group_base` (
  `id` int(4) unsigned NOT NULL COMMENT '组id',
  `name` varchar(11) CHARACTER SET gbk NOT NULL COMMENT '组名',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '删除',
  `g_image` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '组图片',
  `g_introduction` varchar(50) CHARACTER SET gbk DEFAULT NULL COMMENT '组介绍'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='组表';

-- --------------------------------------------------------

--
-- 表的结构 `group_detail`
--

CREATE TABLE IF NOT EXISTS `group_detail` (
  `group_base_id` int(4) unsigned NOT NULL COMMENT '组id',
  `user_base_id` int(5) unsigned NOT NULL COMMENT '成员id',
  `authorization` varchar(2) COLLATE utf8_bin NOT NULL COMMENT '权限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='组成员表';

-- --------------------------------------------------------

--
-- 表的结构 `post_base`
--

CREATE TABLE IF NOT EXISTS `post_base` (
  `id` int(9) unsigned NOT NULL COMMENT '帖子id',
  `user_base_id` int(5) unsigned NOT NULL COMMENT '发帖人id',
  `group_base_id` int(4) unsigned NOT NULL COMMENT '组id',
  `title` varchar(30) CHARACTER SET gbk NOT NULL COMMENT '标题',
  `digest` int(1) NOT NULL DEFAULT '0' COMMENT '精华',
  `sticky` int(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '删除'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='主帖';

-- --------------------------------------------------------

--
-- 表的结构 `post_detail`
--

CREATE TABLE IF NOT EXISTS `post_detail` (
  `post_base_id` int(5) unsigned NOT NULL COMMENT '帖子id',
  `user_base_id` int(5) unsigned NOT NULL COMMENT '回帖人id',
  `replyid` int(5) unsigned DEFAULT NULL COMMENT '回复的id',
  `text` varchar(5000) COLLATE utf8_bin NOT NULL COMMENT '???',
  `floor` int(4) NOT NULL COMMENT '楼层',
  `createTime` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '发布时间',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='回复帖';

-- --------------------------------------------------------

--
-- 表的结构 `post_image`
--

CREATE TABLE IF NOT EXISTS `post_image` (
  `post_base_id` int(11) NOT NULL COMMENT '帖子id',
  `post_image_id` int(11) NOT NULL COMMENT '图片id',
  `p_image` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '帖子图片',
  `delete` int(11) NOT NULL DEFAULT '0' COMMENT '删除'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='?ظ?????ͼƬ';

-- --------------------------------------------------------

--
-- 表的结构 `user_base`
--

CREATE TABLE IF NOT EXISTS `user_base` (
  `id` int(5) unsigned NOT NULL COMMENT '用户id',
  `password` varchar(35) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `nickname` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '昵称',
  `Email` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '邮箱'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户表基本';

-- --------------------------------------------------------

--
-- 表的结构 `user_detail`
--

CREATE TABLE IF NOT EXISTS `user_detail` (
  `user_base_id` int(5) unsigned NOT NULL COMMENT '用户id',
  `authorization` varchar(2) COLLATE utf8_bin NOT NULL COMMENT '身份',
  `status` int(1) NOT NULL COMMENT '状态',
  `lastLogTime` datetime NOT NULL COMMENT '上次登录'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户详情';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `group_base`
--
ALTER TABLE `group_base`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `group_detail`
--
ALTER TABLE `group_detail`
  ADD PRIMARY KEY (`group_base_id`,`user_base_id`);

--
-- Indexes for table `post_base`
--
ALTER TABLE `post_base`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_base_id` (`user_base_id`,`group_base_id`);

--
-- Indexes for table `post_detail`
--
ALTER TABLE `post_detail`
  ADD PRIMARY KEY (`post_base_id`,`floor`),
  ADD KEY `user_base_id` (`user_base_id`,`replyid`);

--
-- Indexes for table `post_image`
--
ALTER TABLE `post_image`
  ADD PRIMARY KEY (`post_image_id`,`post_base_id`);

--
-- Indexes for table `user_base`
--
ALTER TABLE `user_base`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`user_base_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group_base`
--
ALTER TABLE `group_base`
  MODIFY `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '组id',AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `post_base`
--
ALTER TABLE `post_base`
  MODIFY `id` int(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '帖子id',AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `post_image`
--
ALTER TABLE `post_image`
  MODIFY `post_image_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片id',AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `user_base`
--
ALTER TABLE `user_base`
  MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
