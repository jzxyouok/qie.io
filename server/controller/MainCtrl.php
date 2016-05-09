<?php

class MainCtrl extends Controller {
	function index() {
		$this->view('main', 'theme');
	}
}