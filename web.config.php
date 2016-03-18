<?php
//定义常量
define('DOCUMENT_ROOT', dirname(__FILE__));
define('APP_PATH', DOCUMENT_ROOT.'/server');
define('APP_CORE_PATH', APP_PATH.'/core');
//引用core目录下文件
require_once(APP_CORE_PATH.'/app.php');
require_once(APP_CORE_PATH.'/loader.php');
