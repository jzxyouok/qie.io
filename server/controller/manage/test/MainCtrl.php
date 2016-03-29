<?php

class MainCtrl extends Controller {
	function index() {
		$this->loadView('main');
	}
	function test() {
		$this->loadView('main_test');
	}
}