<?php

class MainCtrl extends Controller {
	private $passport = null;
	
	function __construct() {
		parent::__construct();
		$this->passport = Loader::load('Passport');
		
	}
	function index() {
		if(empty($this->user))
			header('Location: /index.php/user/');
		
		if($this->passport->getAdmin())
			$this->loadView('main');
		else
			$this->loadView('login');
	}
	public function login() {
		if(empty($_POST['captcha'])) {
			$this->message(-1, '请输入验证码', 1);
		}
		$captcha = Loader::load('Captcha');
		if(!$captcha->verify($_POST['captcha'])) {
			$this->message(-1, '验证码错误或者过期', 2);
		}
		if(!$this->verify(300)) {
			$this->message(-1, '页面已经过期，请尝试刷新', 3);
		}
		if(empty($_POST['pwd'])) {
			$this->message(-1, '请输入密码', 5);
		}
		if(empty($this->user)) {
			$this->message(-1, '请先登录', 6);
		}
		if($this->passport->adminLogin($_POST['pwd']))
			$this->message(1);
		else
			$this->message(0,'登录失败');
	}
	public function logout() {
		if($this->passport->adminLogout())
			$this->message(1);
		else
			$this->message(0,'登录失败');
	}
}