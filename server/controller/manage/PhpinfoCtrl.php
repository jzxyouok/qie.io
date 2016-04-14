<?php

class PhpinfoCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//phpinfo
	function index() {
		$this->loadView('phpinfo');
	}
}