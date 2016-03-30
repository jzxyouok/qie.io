<?php

class ErrorCtrl extends Controller {
	function index($msg = '') {
		$this->vars['msg'] = $msg;
		$this->loadView('error');
	}
}