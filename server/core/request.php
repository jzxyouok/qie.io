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
	private $path = ''; //REQUEST_URI,path_info
	private $uri = array();
	private $route = array('regexp'=>array('/\/+/', '/[^a-zA-Z0-9_\/,-\\\%\x{4e00}-\x{9fa5}\s]+/u'),
							'replace'=>array('/', ''));
	
	/*
	 * @param array $route 增加路由规则。单条：array('regexp'=>'','replace'=>'')；多条：array('regexp'=>array(),'replace'=>array())
	 */
	function __construct($route = array()) {
		//处理路由
		if(!empty($route['regexp'])) {
			if(is_array($route['regexp']))
				$this->route['regexp'] = array_merge($this->route['regexp'], $route['regexp']);
			else
				$this->route['regexp'][] = (string)$route['regexp'];
		}
		if(!empty($route['replace'])) {
			if(is_array($route['replace']))
				$this->route['replace'] = array_merge($this->route['replace'], $route['replace']);
			else
				$this->route['replace'][] = (string)$route['replace'];
		}
		
		$this->dir = (pathinfo($_SERVER['SCRIPT_NAME']))['dirname'];
		if(!empty($_SERVER['PATH_INFO']) && false)
			$this->path = substr($_SERVER['PATH_INFO'], 1);
		else {
			$this->path = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME'])+1);
			if(empty($this->path))
				$this->path = $_GET['c'].'/'.$_GET['m'].'/'.$_GET['p'];
		}
		
		if(!empty($this->path)) {
			$uri = preg_replace($this->route['regexp'], $this->route['replace'], $this->path);
			$this->uri = explode('/', $uri);
		}
	}
	/*
	 * 获取uri片段值
	 *
	 * @param int $pos 片段位置
	 *
	 * @return string
	 */
	public function uri($pos = 0) {
		return !empty($this->uri[$pos])?$this->uri[$pos]:'';
	}
	public function getDir() {
		return $this->dir;
	}
	public function getPath() {
		return $this->path;
	}
}
