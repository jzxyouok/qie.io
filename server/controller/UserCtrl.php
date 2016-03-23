<?php

class UserCtrl extends Controller {
	function index() {
		$db = Loader::load('database');
		var_dump($db);
		$this->loadView('user');
	}
}