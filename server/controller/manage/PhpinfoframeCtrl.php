<?php

class PhpinfoframeCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//phpinfo
	function index() {
		phpinfo();
	}
}