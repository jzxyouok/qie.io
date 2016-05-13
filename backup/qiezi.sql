-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-05-13 12:23:50
-- 服务器版本： 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qiezi`
--

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

CREATE TABLE `article` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `counter` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `keywords` varchar(255) NOT NULL,
  `excerpt` varchar(300) DEFAULT NULL,
  `author` char(64) DEFAULT NULL,
  `from` varchar(100) DEFAULT NULL,
  `href` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `order` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `article`
--

INSERT INTO `article` (`id`, `title`, `content`, `category_id`, `counter`, `keywords`, `excerpt`, `author`, `from`, `href`, `cover`, `order`, `create_time`, `tm`) VALUES
(1, '发送到发送到', '<p>请输入文章正文fsad</p>', 1, 0, 'key1,key2', '', '管理员', '', '', '', 0, '2016-05-05 08:59:27', '2016-05-06 08:26:59'),
(2, '飞洒地方', '<p>请输入文章正文fas</p>', 1, 0, 'key1', '', '管理员', '', '', '', 0, '2016-05-05 09:00:03', '2016-05-06 08:26:32'),
(3, '飞洒地方', '<p>请输入文章正文法撒旦</p>', 9, 0, 'key1,key2,key3,aferf分,ab', '', '管理员', '', '', '', 0, '2016-05-05 09:01:15', '2016-05-05 09:32:48'),
(4, '这是一篇带tag', '<p>请输入文章正文份饭</p>', 1, 0, '哈哈1,哈啊哈,好啊好啊', '', '管理员', '', '', '', 0, '2016-05-05 11:35:28', '2016-05-05 10:04:08'),
(5, '发送到发送到', '<p>请输入文章正文法撒旦</p>', 1, 0, 'tag1,tag2,key1', '', '管理员', '', '', '', 0, '2016-05-05 11:39:18', '2016-05-11 01:54:02'),
(6, '案发地方', '<p>请输入文章正文法撒旦</p>', 5, 0, 'tag1,tag2,key1', '', '管理员', '', '', '', 0, '2016-05-05 11:41:53', '2016-05-11 01:54:12'),
(7, '案发地方法撒旦', '<p>请输入文章正文法撒旦</p>', 5, 0, 'tag1,tag2,key1', '', '管理员', '', '', '', 0, '2016-05-05 11:42:39', '2016-05-05 09:42:39'),
(8, '试试tag', '<p>试试tag请输入文章正文</p>', 7, 0, 'tag1,tag2,tag3', '份饭', '是否', '法撒旦', '法撒旦', '法撒旦', 0, '2016-05-11 03:52:44', '2016-05-11 01:52:44'),
(9, '试试tag2', '<p>试试tag请输入文章正文</p>', 9, 0, 'tag1,tag3,汤,tag4,tag5', '', '管理员', '', '', 'ff', 0, '2016-05-11 03:55:49', '2016-05-11 02:42:02');

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `root_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `depth` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT '2012-02-18 00:00:00',
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `parent_id`, `root_id`, `depth`, `create_time`, `tm`) VALUES
(1, '法定方式ffff', '第5级分类f', 0, 1, 1, '2016-04-29 13:18:02', '2016-05-03 08:59:23'),
(2, '第二个分类', '没有', 1, 1, 2, '2016-05-03 04:01:37', '2016-05-03 08:59:23'),
(3, '第三个分类', '三级分类', 2, 1, 3, '2016-05-03 04:26:45', '2016-05-03 08:59:23'),
(4, '第4个分类', '', 2, 1, 3, '2016-05-03 04:30:44', '2016-05-03 08:59:23'),
(5, '第2个一级分类', '', 0, 5, 1, '2016-05-03 04:43:11', '2016-05-03 08:59:23'),
(6, '第5个分类', '', 5, 5, 2, '2016-05-03 04:44:15', '2016-05-03 08:59:23'),
(7, '第4级分类', '', 3, 1, 4, '2016-05-03 05:09:34', '2016-05-03 09:00:52'),
(8, '发士大夫撒旦', '', 0, 8, 1, '2016-05-03 05:11:36', '2016-05-03 08:59:23'),
(9, '法定方式', '第5级分类ff', 6, 5, 3, '2016-05-03 09:41:27', '2016-05-03 08:59:23'),
(10, 'fasdfasd', '', 0, 10, 1, '2016-05-03 10:37:48', '2016-05-03 08:59:23'),
(11, 'fasdfasdf', '', 0, 11, 1, '2016-05-03 10:41:47', '2016-05-03 08:59:23'),
(12, 'fasdfasdffasdf', 'fsdfad', 10, 10, 2, '2016-05-03 10:43:50', '2016-05-03 08:59:23'),
(13, '这是一个一级分类', '', 0, 13, 1, '2016-05-03 10:44:43', '2016-05-03 08:59:23'),
(14, '这是一个二级分类', '', 13, 13, 2, '2016-05-03 10:45:08', '2016-05-03 08:59:23'),
(15, '这是一个三级分类', '', 14, 13, 3, '2016-05-03 10:46:14', '2016-05-03 08:59:23'),
(16, '这是一个四级分类', '', 15, 13, 4, '2016-05-03 10:46:59', '2016-05-03 09:00:52'),
(17, '这是一个五级分类', '', 16, 13, 5, '2016-05-03 10:47:30', '2016-05-03 09:00:52'),
(18, '这是一个六级分类发烦烦烦', '', 17, 13, 6, '2016-05-03 10:47:49', '2016-05-03 09:00:52');

-- --------------------------------------------------------

--
-- 表的结构 `file`
--

CREATE TABLE `file` (
  `md5` char(32) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `image`
--

CREATE TABLE `image` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_md5` char(32) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '1982-10-21 00:00:00',
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE `tag` (
  `id` int(11) UNSIGNED NOT NULL,
  `word` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tag`
--

INSERT INTO `tag` (`id`, `word`) VALUES
(6, 'ab'),
(5, 'aferf分'),
(11, 'key1'),
(12, 'key2'),
(4, 'key3'),
(7, 'tag1'),
(8, 'tag2'),
(9, 'tag3'),
(32, 'tag4'),
(33, 'tag5'),
(22, '哈哈1'),
(23, '哈啊哈'),
(24, '好啊好啊'),
(31, '汤');

-- --------------------------------------------------------

--
-- 表的结构 `tag_article`
--

CREATE TABLE `tag_article` (
  `tag_id` int(11) UNSIGNED NOT NULL,
  `target_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tag_article`
--

INSERT INTO `tag_article` (`tag_id`, `target_id`) VALUES
(4, 3),
(5, 3),
(6, 3),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(9, 8),
(9, 9),
(11, 1),
(11, 2),
(11, 3),
(11, 5),
(11, 6),
(11, 7),
(12, 1),
(12, 3),
(22, 4),
(23, 4),
(24, 4),
(31, 9),
(32, 9),
(33, 9);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(36) NOT NULL,
  `password` char(33) NOT NULL,
  `nick` char(64) NOT NULL DEFAULT 'q',
  `email` varchar(100) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '1982-10-21 00:00:00',
  `login_time` datetime DEFAULT '1982-10-21 00:00:00',
  `login_ip` varchar(100) DEFAULT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `nick`, `email`, `create_time`, `login_time`, `login_ip`, `tm`) VALUES
(1, 'admin', '6fc596211340374888eda68debf0846ce', '管理员', '48838096@qq.com', '2016-03-25 10:31:28', '2016-05-11 04:40:22', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-05-11 02:40:22'),
(24, 'fsadfasdf', '1fe992a830802220ba37be5c3838b815e', 'fasfasd', 'fasfs@fasdfasd.co', '2016-04-01 08:26:54', '2016-04-12 10:40:45', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-04-12 08:40:45'),
(39, 'test68nffff', '387f418c8740acfca883caa53214abba1', 'ftest68n', 'test68n@fasd.com', '1982-10-21 00:00:00', '2016-04-28 09:58:28', '2130706433', '2016-05-11 03:43:38'),
(40, 'test168', '1fe992a830802220ba37be5c3838b815e', 'pjlyzs1462936582', 'test168@163.com', '2016-05-11 05:16:22', '2016-05-11 05:26:40', '2130706433,2130706433', '2016-05-11 03:26:40');

-- --------------------------------------------------------

--
-- 表的结构 `user_admin`
--

CREATE TABLE `user_admin` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `code` char(4) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user_admin`
--

INSERT INTO `user_admin` (`user_id`, `code`, `password`, `grade`) VALUES
(1, 'xq1/', 'c816215b20af26b3697a0d563bd9ee8d', 0),
(24, 'mJbd', '3b5a7c7d8d8b294ce584d47bdd3c59ac', 0);

-- --------------------------------------------------------

--
-- 表的结构 `user_profile`
--

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `article` ADD FULLTEXT KEY `title_content` (`title`,`content`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `parent` (`parent_id`,`id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`md5`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `text` (`word`);

--
-- Indexes for table `tag_article`
--
ALTER TABLE `tag_article`
  ADD PRIMARY KEY (`tag_id`,`target_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `nick` (`nick`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_admin`
--
ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
