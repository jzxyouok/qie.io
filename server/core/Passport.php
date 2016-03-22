<?php
/*
 * 用户授权验证类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/02/18
 * 更新时间：2012/06/23
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(36) NOT NULL,
  `password` char(32) NOT NULL,
  `nick` char(64) NOT NULL DEFAULT 'q',
  `email` varchar(100) NOT NULL DEFAULT '',
  `profile` varchar(255) NOT NULL,
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT '1982-10-21 00:00:00',
  `login_time` datetime DEFAULT '1982-10-21 00:00:00',
  `login_ip` varchar(100) DEFAULT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10388 ;
 * 
 * status字段，{-1:锁定;0:保护；1:正常}
 */
 
ob_start();
class PassportException extends Exception{}

class Passport {
	/*
	 * 构造函数，检查登陆
	 */
	function __construct() {
		echo 'passport';
	}
}
