<?php
/*
 * 程序入口
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
			$route = Loader::loadVar(APP_PATH.'/config/route.php');
			$request = Loader::load('Request', $route);
			$position = 0; //系统调用的uri起始位置,调用的方法在这个位置上+1,调用的方法需要的参数在这个位置
			$obj = ''; //自动调用的控制器
			$ctrlObj = null; //自动调用的控制器
			$method = ''; //自动调用的控制器方法
			$param = $position+2; //调用方法是用的参数的uri参数位置为3
			//按照访问路径加载控制器
			if(!$obj = strtolower($request->uri($position))) {
				$obj = 'main'; //默认控制器，也就是网站首页
				$method = 'index';
				$param = -1;
			}
			$obj{0} = strtoupper($obj{0});
			$ctrlObj = Loader::load('controller/'.$request->dir().'/'.$obj.'Ctrl', array($process_start), false);
			if(!$ctrlObj)
				$this->error('系统找不到对象', $request);
			
			if(empty($method) && !($method = $request->uri($position+1))) {
				$method = 'index'; //尝试加载默认方法
				$param = -1;
			}
			if(!method_exists($ctrlObj, $method)) {
				$method = 'index';
				if(!method_exists($ctrlObj, $method))
					$this->error('系统找不到对应的处理过程', array($request, $ctrlObj)); //错误处理，找不到对象方法。
				$param = $position + 1;
			}
			$ctrlObj->setParamPos($param);
			if($param != -1 && NULL !== ($param = $request->uri($param)))
				$ctrlObj->$method($param);
			else
				$ctrlObj->$method();
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