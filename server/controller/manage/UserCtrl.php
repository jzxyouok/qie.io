<?php

class UserCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//管理界面首页
	function index() {
		$this->loadView('user');
	}
}