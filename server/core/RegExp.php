<?php
//常用正则表达式
class RegExp {
	const EMAIL = "/^[0-9a-zA-Z._-]+@(?:[0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/";
	const TEL = "/^1[3578]\d{9}$|^0?\d{2,3}-?\d{7,8}$/";
	const ZIP = "/^\d{6}$/";
	const USERNAME = "/^[\$a-zA-Z][\$a-zA-Z0-9_-]{3,32}$/";
	const PASSWORD = "/^[\w`~!@#$%^&*()_+-={}\[\]|\\\;:'<>,.?\/\"]{6,}$/";
	const URI = "/(https?|ftp|file):\/\/((?:[0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6})(?:\/\w*)?/i";
	const AT = "/@([a-zA-Z0-9\x{4e00}-\x{9fa5}]+)/u";
	const TOPIC = "/#([\sa-zA-Z0-9\x{4e00}-\x{9fa5}]+)#/u";
	const TAG = "/<([a-z][1-9]?)[^>]*>(.*)<\/\\1>/i";
	const CHNWORD = "/[\x{4e00}-\x{9fa5}]+/u";
	const CHNWORD_JS = "/[\u4E00-\u9FA5]/";
	const WORDS = "/^[\$\s_-a-zA-Z0-9\x{4e00}-\x{9fa5}]{1,}$/u";
	const IMAGE = "/<(?:img|input)[^>]*?src=(['\"]?)(.+?)\\1[^>]*>/iu";
	const IMAGES = '#(\'|")([a-z0-9.\-_:/?]+?\.(?:jpg|jpeg|gif|png|bmp)(?:.*?))\\1#iu';
}

