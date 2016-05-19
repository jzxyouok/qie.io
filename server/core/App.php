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
			if(!class_exists('Loader')) {
				if(!file_exists(APP_PATH.'/core/Loader.php'))
					exit('App::__construct(): Loader not exists');
				require(APP_PATH.'/core/Loader.php');
			}
			
			/*
			 * 设定环境变量
			 */
			$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
			//测试模式
			define('STATE', (int)$profile['state']);
			//全局加密SALT
			define('SALT', (string)$profile['salt']);
			//domain
			define('DOMAIN', (string)$profile['domain']);
			//默认数据库配置
			define('DB_CONFIG', (string)$profile['db_config']);
			
			//是否关闭网站
			$isClosed = !$profile['state'];
			
			if(STATE == -1) {
				error_reporting(E_ERROR | E_WARNING | E_PARSE); //E_ALL
				error_reporting(1);
			} else {
				error_reporting(0);
			}
			/*
			 * 实现路由
			 */
			//处理管理后台目录
			$route = array('dir'=>array('regexp'=>array('#^'.$profile['admin_dir'].'#i' //管理后台目录
																									),
																	'replace'=>array('/admin')
																	),
										'path'=>array('regexp'=>array('/\/+/', //过滤多余的/斜杠
																									'/[^a-zA-Z0-9_\/,-\\\%\x{4e00}-\x{9fa5}\s]+/u' //过滤非法字符
																									),
									  							'replace'=>array('/', '')
																	));
			
			$userRoute = Loader::loadVar(APP_PATH.'/config/route.php');
			$route['dir'] = array_merge($route['dir'], $userRoute['dir']);
			$route['path'] = array_merge($route['path'], $userRoute['path']);
			//处理物理目录dir
			$info = pathinfo($_SERVER['SCRIPT_NAME']);
			$dir = $info['dirname'];
			//管理目录不允许关闭网站
			if($dir == $profile['admin_dir'])
				$isClosed = false;
			//物理目录转虚拟目录
			$dir = preg_replace($route['dir']['regexp'], $route['dir']['replace'], $dir);
			//处理虚拟path
			if(!empty($_SERVER['PATH_INFO']))
				$path = substr($_SERVER['PATH_INFO'], 1);
			else {
				$path = substr($_SERVER['QUERY_STRING']?str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']):$_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME'])+1);
				if(empty($path))
					$path = $_GET['c'].'/'.$_GET['m'].'/'.$_GET['p']; //controller,method,param
			}
			$segments = array();
			if(!empty($path)) {
				$segments = explode('/', preg_replace($route['path']['regexp'], $route['path']['replace'], $path));
			}
			
			$position = 0; //系统调用的uri起始位置,调用的方法在这个位置上+1,调用的方法需要的参数在这个位置
			$ctrlName = ''; //自动调用的控制器名称
			$controller = null; //自动调用的控制器
			$method = ''; //自动调用的控制器方法
			$param = $position+2; //调用方法时用的参数的uri参数位置为3
			
			//按照访问路径加载控制器
			if(empty($segments[$position]) || !($ctrlName = strtolower($segments[$position]))) {
				$ctrlName = 'main'; //默认控制器，也就是网站首页
				$method = 'index';
				$param = -1;
			}
			//关闭网站
			if($isClosed && $ctrlName != 'user') {
				self::closed();
			}
			
			$ctrlName{0} = strtoupper($ctrlName{0});
			$controller = Loader::load('controller'.$dir.'/'.$ctrlName.'Ctrl', array(), false);
			//控制器不存在
			if(!$controller)
				self::error('App::__construct: controller not found');
			
			$controller->processStart = $process_start;
			$controller->dir = $dir;
			$controller->segments = $segments;
			
			if(empty($method) && !($method = $segments[$position+1])) {
				$method = 'index'; //尝试加载默认方法
				$param = -1; //没有参数
			}
			
			if(!is_callable(array($controller, $method))) {
				if($param == -1 || !is_callable(array($controller, 'index')))
					self::error('App::__construct: method not found'); //错误处理，找不到对象方法。
				
				$method = 'index';
				$param = $position + 1;
			}
			
			$controller->paramPos = $param;
			if($param != -1 && NULL !== ($param = $segments[$param]))
				$controller->$method($param);
			else
				$controller->$method();
		} catch(Exception $error) {
			self::error('Exception:'.$error->getMessage());
		}
	}
	/*
	 * 出错页面
	 *
	 * @param string $msg
	 * @param object $obj
	 *
	 */
	public static function error($msg = '', $obj = null) {
		if(STATE === -1) {
			header('Content-type: text/html; charset=utf-8');
			echo '<h4>',$msg,'</h4>','<div><pre>',var_dump($obj),'</pre></div>';
		} else
			header('Location: /index.php/error/'.$msg.'/');
		exit;
	}
	/*
	 * 网站维护页面
	 */
	public static function closed() {
		$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
		$html = <<<EOT
<html>
<head>
<title>网站维护中-{$profile['title']}</title>
</head>
<body>
we will come back soon.
{$profile['analytics']}
</body>
</html>
EOT;
		exit($html);
	}
}