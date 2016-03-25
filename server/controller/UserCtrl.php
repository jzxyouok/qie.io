<?php
/*
 * 用户管理
 */
class UserCtrl extends Controller {
	/*
	 * 页面
	 */
	function index() {
		$this->loadView('user');
	}
	public function reg() {
		if(!empty($this->user))
			header('Location: /index.php/user/center/');
		
		$this->loadView('user_reg');
	}
	public function center() {
		$this->loadView('user_center');
	}
	/*
	 * 接口
	 */
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
		if(empty($_POST['user_name'])) {
			$this->message(-1, '请输入用户名', 4);
		}
		if(empty($_POST['pwd'])) {
			$this->message(-1, '请输入密码', 5);
		}
		if(empty($_POST['email'])) {
			$this->message(-1, '请输入昵称和邮箱', 5);
		}
		if(!empty($this->user)) {
			$this->message(-1, '请不要重复注册', 6);
		}
		$psp = Loader::load('passport');
		$res = $psp->reg($_POST['user_name'], $_POST['pwd'], $_POST['email'], $_POST['nick']);
		if($res['code']) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		}
		if(empty($res['code'])) {
			$this->message(1, array('id'=>$res['id'], 'user_name'=>$res['name'], 'nick'=>$res['nick']));
		}
		$this->message(1, '请求成功');
	}
}