<?php
/*
 * 后台phpinfo
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class PhpinfoCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//phpinfo
	function index() {
		$this->loadView('phpinfo');
	}
}