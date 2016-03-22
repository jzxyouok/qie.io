<?php
/*
 * 用户授权验证类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/02/18
 * 更新时间：2016/03/22
 * 
 */
 /*
  * database
 
CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(36) NOT NULL,
  `password` char(32) NOT NULL,
  `nick` char(64) NOT NULL DEFAULT 'q',
  `email` varchar(100) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '1982-10-21 00:00:00',
  `login_time` datetime DEFAULT '1982-10-21 00:00:00',
  `login_ip` varchar(100) DEFAULT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `user_ext` (
  `user_id` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  */

class PassportException extends Exception{}

class Passport {
	/*
	 * 构造函数，检查登陆
	 */
	function __construct() {
		echo 'passport';
	}
}
