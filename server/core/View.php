<?php
/*
 * 视图类
 * view class
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 更新时间：2016/03/21
 */
require(APP_PATH . '/third_party/smarty/Smarty.class.php');

class View extends Smarty {
	function __construct() {//构造函数
		parent::__construct();
		$this->template_dir = APP_PATH . '/view';
		$this->compile_dir = DOCUMENT_ROOT . '/user_files/template_c';
		$this->cache_dir = DOCUMENT_ROOT . '/user_files/cache';
		$this->caching = false;
		$this->left_delimiter = '<{';
		$this->right_delimiter = '}>';
	}
	/* assign常量
	 */
	public function assignConst() {
		foreach (get_defined_constants() as $k => $v)
			$this->assign($k, $v);
    }
	/* 设置模板文件路径
	 */
	public function setTemplate($dir) {
		$this->template_dir = $dir;
	}
	/* 设置编译文件路径
	 */
	public function setCompile($dir) {
		$this->compile_dir = $dir;
	}
	public function setDelimiter($left, $right) {
		$this->left_delimiter = $left;
		$this->right_delimiter = $right;
	}
	/* 设置缓存
	 */
	public function setCache($t = 300) {
		if(!is_numeric($t)) return;
		$this->cache_dir = DOCUMENT_ROOT . '/user_files/cache';
		$this->cache_lefttime = $t;
	}
	/* 缓存开关
	 */
	public function isCache($f = false) {
		$this->caching = $f;
	}
	public function registerFunction($name = 'func', $func) {
		$this->register_function($name, $func);
	}
}