-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-05-05 13:12:47
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
(1, '发送到发送到', '<p>请输入文章正文</p>', 1, 0, '', '', '管理员', '', '', '', 0, '2016-05-05 08:59:27', '2016-05-05 06:59:27'),
(2, '飞洒地方', '<p>请输入文章正文</p>', 1, 0, '', '', '管理员', '', '', '', 0, '2016-05-05 09:00:03', '2016-05-05 07:00:03'),
(3, '飞洒地方', '<p>请输入文章正文法撒旦</p>', 9, 0, 'key1,key2,key3,aferf分,ab', '', '管理员', '', '', '', 0, '2016-05-05 09:01:15', '2016-05-05 09:32:48'),
(4, '这是一篇带tag', '<p>请输入文章正文份饭</p>', 1, 0, '哈哈1,哈啊哈,好啊好啊', '', '管理员', '', '', '', 0, '2016-05-05 11:35:28', '2016-05-05 10:04:08'),
(5, '发送到发送到', '<p>请输入文章正文法撒旦</p>', 1, 0, 'tag1，tag2，key1', '', '管理员', '', '', '', 0, '2016-05-05 11:39:18', '2016-05-05 09:39:18'),
(6, '案发地方', '<p>请输入文章正文法撒旦</p>', 5, 0, 'tag1，tag2，key1', '', '管理员', '', '', '', 0, '2016-05-05 11:41:53', '2016-05-05 09:41:53'),
(7, '案发地方法撒旦', '<p>请输入文章正文法撒旦</p>', 5, 0, 'tag1,tag2,key1', '', '管理员', '', '', '', 0, '2016-05-05 11:42:39', '2016-05-05 09:42:39');

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
-- 表的结构 `tag`
--

CREATE TABLE `tag` (
  `id` int(11) UNSIGNED NOT NULL,
  `word` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tag_article`
--

CREATE TABLE `tag_article` (
  `tag_id` int(11) UNSIGNED NOT NULL,
  `target_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 'admin', '6fc596211340374888eda68debf0846ce', '管理员', '48838096@qq.com', '2016-03-25 10:31:28', '2016-05-04 04:01:23', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-05-04 02:01:23'),
(13, 'fasdfasdfasd', '387f418c8740acfca883caa53214abba1', 'mkferc1459424404', 'fasdfas@fasd.com', '2016-03-31 13:40:04', '2016-03-31 13:40:04', '2130706433', '2016-03-31 11:40:04'),
(14, 'fsadfasd', '387f418c8740acfca883caa53214abba1', 'qbezli1459424424', 'fasfsad@fasdfsa.com', '2016-03-31 13:40:24', '2016-03-31 13:40:24', '2130706433', '2016-03-31 11:40:24'),
(16, 'fasdfasdfasdfsdf', '5b65e73228f9b45e938f7f11b306c67d5', 'tkrxwx1459424686', 'fasdfassadfd@fasdfas.com', '2016-03-31 13:44:46', '2016-03-31 13:44:46', '2130706433', '2016-03-31 11:44:46'),
(17, 'safasdfasdf', '681cb30c1be5793b51bfa1ece989b18f7', 'goqmyz1459424844', 'fsdfsad@fasdfsa.com', '2016-03-31 13:47:24', '2016-03-31 13:47:24', '2130706433', '2016-03-31 11:47:24'),
(18, 'fasddddd', '1fe992a830802220ba37be5c3838b815e', 'tnwwqu1459425051', 'fsadfasdddddddddd@fsadf.com', '2016-03-31 13:50:51', '2016-03-31 13:50:51', '2130706433', '2016-03-31 11:50:51'),
(19, 'fasdfsafasdf', '681cb30c1be5793b51bfa1ece989b18f7', 'qzmmpz1459425099', 'fsadfsadfs@fsad.com', '2016-03-31 13:51:39', '2016-03-31 13:59:26', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-03-31 11:59:26'),
(20, 'fadfasdfas', '4b002bb6b207f13446d6d035373f6de53', 'ipppeg1459425619', 'fasdfasdfasd@fasdfsa.com', '2016-03-31 14:00:19', '2016-03-31 14:00:19', '2130706433', '2016-03-31 12:00:19'),
(21, 'fsadfasfsadfasd', '1fe992a830802220ba37be5c3838b815e', 'zwlmej1459425669', 'fasdfasdfasdf@fasd.com', '2016-03-31 14:01:09', '2016-03-31 14:01:09', '2130706433', '2016-03-31 12:01:09'),
(22, 'fasdfasdf', '387f418c8740acfca883caa53214abba1', 'ninoke', 'fasdfas@fasdfas.com', '2016-03-31 14:02:02', '2016-03-31 14:25:21', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-03-31 12:25:21'),
(23, 'fasdfasdfsa', '0612a62af1483892679fbd010931f5e81', 'dwjgps1459427240', 'fsadfasddddddd@fasd.com', '2016-03-31 14:27:20', '2016-04-01 04:59:09', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-04-01 02:59:09'),
(24, 'fsadfasdf', '1fe992a830802220ba37be5c3838b815e', 'fasfasd', 'fasfs@fasdfasd.co', '2016-04-01 08:26:54', '2016-04-12 10:40:45', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-04-12 08:40:45'),
(26, 'fsadfsadffasdfasdf', '0612a62af1483892679fbd010931f5e81', 'sdlyoi1460445699', 'fsadfsaasdfasd@fsdfsadfadfas.com', '2016-04-12 09:21:39', '2016-04-12 09:21:39', '2130706433', '2016-04-12 07:21:39'),
(27, 'fsadfasdfasdfsadf', '5b65e73228f9b45e938f7f11b306c67d5', 'cvkfsu1460445747', 'fasdfsadf2@fasdf.com', '2016-04-12 09:22:27', '2016-04-12 09:22:27', '2130706433', '2016-04-12 07:22:27'),
(30, 'aaaaaaaaaaaaaaaa', '1fe992a830802220ba37be5c3838b815e', 'trpavn1460447812', 'fsadfsa@fasddddddddddddd.com', '2016-04-12 09:56:52', '2016-04-12 09:56:52', '2130706433', '2016-04-12 07:56:52'),
(36, 'ffffffffasddddddddddd', '297c53d5571130cf0ff5e770108b6a53a', 'ffffffffasddddddddddd', 'ffffffffasddddddddddd@fsad.com', '2016-04-13 13:41:34', '2016-04-13 13:41:34', '2130706433', '2016-04-13 11:41:34'),
(37, 'fffffffffffffsadfff', '3db8df79782f7749587ef5846c68c0321', 'suitzt146054888', 'fffffffffffffsad@fffffffffff22ff.com', '2016-04-13 13:42:18', '2016-04-25 09:35:00', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-04-28 07:10:13'),
(38, 'test38', '57dc06554bf7bf68806de31ac4aba205b', 'test38nick', 'test38@38.com', '1982-10-21 00:00:00', '2016-04-26 04:23:34', '2130706433', '2016-04-26 02:23:34'),
(39, 'test68n', '387f418c8740acfca883caa53214abba1', 'ftest68n', 'test68n@fasd.com', '1982-10-21 00:00:00', '2016-04-28 09:58:28', '2130706433', '2016-04-28 07:58:28');

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
(24, 'mJbd', '3b5a7c7d8d8b294ce584d47bdd3c59ac', 0),
(39, 'Mlxz', '0700829b5526fa23d7e2298fb5ba4e9b', 1);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
