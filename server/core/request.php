<?php
/*
 * 用户请求处理类
 * request handle
 *
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/03/21
 *
 * 处理用户请求
 * 1.处理路由
 * 2.过滤用户内容
 */

class Request {
	private $dir = ''; //物理路径
	private $path = ''; //虚拟路径。index.php后面部分(REQUEST_URI,path_info)
	private $segments = array();
	private $route = array('regexp'=>array('/\/+/', '/[^a-zA-Z0-9_\/,-\\\%\x{4e00}-\x{9fa5}\s]+/u'),
							'replace'=>array('/', ''));
	
	/*
	 * @param array $route 增加路由规则。单条：array('regexp'=>'','replace'=>'')；多条：array('regexp'=>array(),'replace'=>array())
	 */
	function __construct($route = array()) {
		//TODO:处理路由
		
		$info = pathinfo($_SERVER['SCRIPT_NAME']);
		$this->dir = $info['dirname'];
		
		if(!empty($_SERVER['PATH_INFO']))
			$this->path = substr($_SERVER['PATH_INFO'], 1);
		else {
			$this->path = substr($_SERVER['QUERY_STRING']?str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']):$_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME'])+1);
			if(empty($this->path))
				$this->path = $_GET['c'].'/'.$_GET['m'].'/'.$_GET['p']; //controller,method,param
		}
		
		if(!empty($this->path)) {
			$this->segments = explode('/', preg_replace($this->route['regexp'], $this->route['replace'], $this->path));
		}
	}
	/*
	 * 获取uri片段值
	 *
	 * @param int $pos 片段位置
	 *
	 * @return string
	 */
	public function segment($pos = 0) {
		return !empty($this->segments[$pos])?$this->segments[$pos]:'';
	}
	public function getDir() {
		return $this->dir;
	}
	public function getPath() {
		return $this->path;
	}
}
