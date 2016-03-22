<?php
//定义常量
define('DOCUMENT_ROOT', dirname(__FILE__)); //文件系统根目录
define('APP_PATH', DOCUMENT_ROOT.'/server'); //服务端根目录
define('TEST_MODE', true); //测试模式
//引用core目录下文件
require_once(APP_PATH.'/core/App.php');
require_once(APP_PATH.'/core/Cache.php');
require_once(APP_PATH.'/core/Captcha.php');
require_once(APP_PATH.'/core/Controller.php');
require_once(APP_PATH.'/core/Crypt.php');
require_once(APP_PATH.'/core/Loader.php');
require_once(APP_PATH.'/core/Model.php');
require_once(APP_PATH.'/core/Passport.php');
require_once(APP_PATH.'/core/RegExp.php');
require_once(APP_PATH.'/core/Request.php');
require_once(APP_PATH.'/core/Store.php');
require_once(APP_PATH.'/core/Util.php');
require_once(APP_PATH.'/core/View.php');
