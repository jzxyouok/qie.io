<?php

class ArticleCtrl extends Controller {
	protected $autoload = array('this'=>'adminCheck');
	
	//管理界面首页
	function index() {
		$this->loadView('article');
	}
}