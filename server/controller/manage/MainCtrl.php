<?php

class MainCtrl extends Controller {
	private $isAdmin = false;
	private $passport = NULL;
	
	function __construct($startTime = 0) {
		parent::__construct($startTime);
		$this->passport = Loader::load('Passport');
		$this->isAdmin = $this->profile['admin_relogin'] ? $this->passport->isAdmin() : !empty($this->user);
	}
	//管理界面首页
	function index() {
		if(empty($this->user))
			header('Location: /index.php/user/');
		
		if($this->isAdmin) {
			$this->vars['admin_relogin'] = $this->profile['admin_relogin'];
			$this->loadView('main');
		} else
			$this->loadView('login');
	}
	//登录接口（二次验证）
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
	//退出接口（二次验证）
	public function logout() {
		if($this->profile['admin_relogin'])
			$this->passport->adminLogout();
		else
			$this->passport->logout();
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	//更新密码（二次验证）
	public function update() {
		if(empty($_POST['old_pwd']))
			$this->message(-1, '请输入旧密码', 1);
		if(empty($_POST['pwd']))
			$this->message(-1, '请输入新密码', 1);
		
		$res = $this->passport->adminModify($_POST['pwd'], $_POST['old_pwd']);
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if(!$res) {
			$this->message(0,'修改失败');
		} else {
			$this->message(1);
		}
	}
}