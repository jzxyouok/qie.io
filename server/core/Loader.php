<?php
/*
 * 对象加载类
 * loader class
 *
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/03/21
 */

class Loader {
	private static $objects = array(); //生成的对象数组
	private static $vars = array();
	/*
	 * 加载对象
	 */
	public static function load($class = '', $args = array(), $key = 0) {
		if(empty($class))
			return false;
		
		$obj = NULL;
		/* 支持多级目录引入 */
		if(false !== ($flag = strrpos($class, '/'))) {
			$path = substr($class, 0, $flag);
			$class = substr($class, $flag + 1);
		} else 
			$path = 'core';
		//如果对象已经存在，直接返回
		if(false !== $key && isset(self::$objects[$class][$key]))
			return self::$objects[$class][$key];
		
		//引入不存在的类文件
		if(!class_exists($class)) {
			if(!file_exists($path = APP_PATH . "/{$path}/{$class}.php"))
				return false;
			require($path);
			if(!class_exists($class))
				return false;
		}
		
		$obj = new ReflectionClass($class);
		$obj = $obj->newInstanceArgs(is_array($args)?$args:array());
		/* eval方法 
		$argString = '';
		if(!empty($args)) { //设置对象参数
			foreach($args as $k => $v)
				$argString .= ', $args['.(preg_match("/^\d$/", $k) ? $k : '\'' . $k . '\'') . ']';
			$argString = substr($argString, 2);
		}
		$obj = null;
		eval("\$obj = new {$class}({$argString});");
		*/
		
		if($key !== false)
			self::$objects[$class][$key] = $obj;
		return $obj;
	}
	/*
	 * 加载配置文件
	 */
	public static function loadVar($path, $name = '') {
		if(isset(self::$vars[$path])) {
			return self::$vars[$path];
		}
		
		include($path);
		if(empty($name)) {
			$info = pathinfo($path);
			$name = $info['filename'];
		}
		self::$vars[$path] = ${$name};
		return self::$vars[$path];
	}
}