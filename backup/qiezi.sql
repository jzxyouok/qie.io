SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


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

INSERT INTO `user` (`id`, `name`, `password`, `nick`, `email`, `create_time`, `login_time`, `login_ip`, `tm`) VALUES
(1, 'admin', '6fc596211340374888eda68debf0846ce', '管理员', '48838096@qq.com', '2016-03-25 10:31:28', '2016-03-30 10:07:06', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-03-30 08:07:06');

CREATE TABLE `user_admin` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `code` char(4) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_admin` (`user_id`, `code`, `password`, `grade`) VALUES
(1, 'qA-J', 'fc94e9c8f72e14941b962c2ec9eaad67', 0);

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `nick` (`nick`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);


ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
