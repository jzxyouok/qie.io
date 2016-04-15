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
			
			/*
			 * 设定环境变量
			 */
			$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
			//测试模式
			define('TEST_MODE', isset($profile['test_mode'])?(boolean)$profile['test_mode']:false);
			if(TEST_MODE) {
				error_reporting(E_ERROR | E_WARNING | E_PARSE); //E_ALL
				error_reporting(1);
			} else {
				error_reporting(0);
			}
			
			/*
			 * 实现路由
			 */
			$route = Loader::loadVar(APP_PATH.'/config/route.php');
			$request = Loader::load('Request', array($route));
			$dir = $request->getDir();
			
			$position = 0; //系统调用的uri起始位置,调用的方法在这个位置上+1,调用的方法需要的参数在这个位置
			$ctrlName = ''; //自动调用的控制器名称
			$controller = null; //自动调用的控制器
			$method = ''; //自动调用的控制器方法
			$param = $position+2; //调用方法是用的参数的uri参数位置为3
			
			//按照访问路径加载控制器
			if(!($ctrlName = strtolower($request->segment($position)))) {
				$ctrlName = 'main'; //默认控制器，也就是网站首页
				$method = 'index';
				$param = -1;
			}
			$ctrlName{0} = strtoupper($ctrlName{0});
			$controller = Loader::load('controller'.$dir.'/'.$ctrlName.'Ctrl', array($process_start), false);
			if(!$controller)
				self::error('找不到控制器对象::controller not found', $request);
			
			if(empty($method) && !($method = $request->segment($position+1))) {
				$method = 'index'; //尝试加载默认方法
				$param = -1;
			}
			if(!is_callable(array($controller, $method))) {
				$method = 'index';
				if(!is_callable(array($controller, $method)))
					self::error('找不到对象方法::method not found', array($request, $controller)); //错误处理，找不到对象方法。
				
				$param = $position + 1;
			}
			$controller->setParamPos($param);
			
			if($param != -1 && NULL !== ($param = $request->segment($param)))
				$controller->$method($param);
			else
				$controller->$method();
		} catch(Exception $error) {
			self::error('Exception:'.$error->getMessage());
		}
	}
	public static function error($msg = '', $obj = null) {
		if(TEST_MODE) {
			header('Content-type: text/html; charset=utf-8');
			echo '<h4>',$msg,'</h4>','<div><pre>',var_dump($obj),'</pre></div>';
		} else
			header('Location: /index.php/error/'.$msg.'/');
		exit;
	}
}