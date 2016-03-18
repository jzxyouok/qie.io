<?php
/*
 * 程序入口
 * app init
 *
 */
class App {
	private $loader = null;
	
	function __construct() {
		$this->loader = new Loader();
	}
	public function loader($className = '', $path = '') {
		echo $className.'/'.$path;
	}
}