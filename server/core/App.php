<?php
/*
 * 程序入口/初始化/设定环境变量
 * app init
 *
 * 作者：billchen
 * 邮箱：48838096@qq.com
 */
 
class App {
	/*
	 *
	 * @param int $process_start 系统开始时间
	 *
	 */
	function __construct($process_start = 0) {
		try {
			if(!class_exists('Loader'))
				require(APP_PATH.'/core/Loader.php');
			
			//设定环境变量
			$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
			//测试模式
			define('TEST_MODE', isset($profile['test_mode'])?(boolean)$profile['test_mode']:false);
			if(TEST_MODE) {
				error_reporting(E_ERROR | E_WARNING | E_PARSE); //E_ALL
				error_reporting(1);
			} else {
				error_reporting(0);
			}
			
			$route = Loader::loadVar(APP_PATH.'/config/route.php');
			$request = Loader::load('Request', array($route));
			$dir = $request->getDir();
			$position = 0; //系统调用的uri起始位置,调用的方法在这个位置上+1,调用的方法需要的参数在这个位置
			$objName = ''; //自动调用的控制器名称
			$object = null; //自动调用的控制器
			$method = ''; //自动调用的控制器方法
			$param = $position+2; //调用方法是用的参数的uri参数位置为3
			
			//按照访问路径加载控制器
			if(!$objName = strtolower($request->segment($position))) {
				$objName = 'main'; //默认控制器，也就是网站首页
				$method = 'index';
				$param = -1;
			}
			$objName{0} = strtoupper($objName{0});
			$object = Loader::load('controller'.$dir.'/'.$objName.'Ctrl', array($process_start), false);
			if(!$object)
				$this->error('找不到对象::object not found', $request);
			
			if(empty($method) && !($method = $request->segment($position+1))) {
				$method = 'index'; //尝试加载默认方法
				$param = -1;
			}
			if(!method_exists($object, $method)) {
				$method = 'index';
				if(!method_exists($object, $method))
					$this->error('找不到方法::method not found', array($request, $object)); //错误处理，找不到对象方法。
				$param = $position + 1;
			}
			$object->setParamPos($param);
			if($param != -1 && NULL !== ($param = $request->segment($param)))
				$object->$method($param);
			else
				$object->$method();
		} catch(Exception $error) {
			$this->error('Exception:'.$error->getMessage());
		}
	}
	public function error($msg = '', $obj = null) {
		if(defined('TEST_MODE') && TEST_MODE) {
			header('Content-type: text/html; charset=utf-8');
			echo '<h4>',$msg,'</h4>','<div><pre>',var_dump($obj),'</pre></div>';
		} else
			header('Location: /index.php/error/'.$msg.'/');
		exit;
	}
}