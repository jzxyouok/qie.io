<?php
/*
 * 后台phpinfo框架
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class PhpinfoframeCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//phpinfo
	function index() {
		phpinfo();
	}
}