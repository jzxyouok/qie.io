<?php
//定义系统常量
define('DOCUMENT_ROOT', dirname(__FILE__)); //文件系统根目录
define('APP_PATH', DOCUMENT_ROOT.'/server'); //服务端根目录
define('TEST_MODE', true); //测试模式
//配置
if(TEST_MODE) {
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //E_ALL
	error_reporting(1);
} else {
	error_reporting(0);
}