<?php
/*
 * 用户管理
 */
class UserCtrl extends Controller {
	function index() {
		$this->loadView('user');
	}
	public function reg() {
		$this->loadView('user_reg');
	}
	public function login() {
		
	}
	public function logout() {
		
	}
	public function insert() {
		if(empty($_POST['captcha'])) {
			$this->message(-1, '请输入验证码', 1);
		}
		$captcha = Loader::load('captcha');
		if(!$captcha->verify($_POST['captcha'])) {
			$this->message(-1, '验证码错误', 2);
		}
		if(!$this->verify(300)) {
			$this->message(-1, '页面已经过期', 3);
		}
		$this->message(1, '请求成功');
	}
}