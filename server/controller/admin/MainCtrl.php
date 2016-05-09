<?php
/*
 * 后台登录验证
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class MainCtrl extends Controller {
	private $isAdmin = false;
	
	function __construct($startTime = 0) {
		parent::__construct($startTime);
		$this->isAdmin = $this->hasAdminLogin(false);
	}
	/*
	 * page
	 */
	//管理界面首页
	function index() {
		if(empty($this->user))
			header('Location: /index.php/user/');
		
		if($this->isAdmin) {
			$this->view('main');
		} else
			$this->view('login');
	}
	//修改密码
	function edit() {
		if(empty($this->user) || !$this->isAdmin)
			header('Location: /index.php/user/');
		
		$this->vars['admin_relogin'] = $this->profile['admin_relogin'];
		$this->view('main_edit');
	}
	/*
	 * api
	 */
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
		
		try {
			$passport = Loader::load('Passport');
			$res = $passport->adminLogin($_POST['pwd']);
			if(!empty($res['code'])) {
				$this->message(-1, '登录失败', 10+$res['code']);
			} else if(!$res) {
				$this->message(0,'登录失败');
			} else {
				$this->message(1);
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//退出接口（二次验证）
	public function logout() {
		if(!$this->isAdmin) {
			exit();
		}
		$passport = Loader::load('Passport');
		if($this->profile['admin_relogin'])
			$passport->adminLogout();
		else
			$passport->logout();
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	//更新密码（二次验证）
	public function update() {
		if(!$this->isAdmin) {
			$this->message(-1, '请先登录', 1);
		}
		if(empty($_POST['old_pwd']))
			$this->message(-1, '请输入旧密码', 1);
		if(empty($_POST['pwd']))
			$this->message(-1, '请输入新密码', 1);
		
		try {
			$passport = Loader::load('Passport');
			$res = $passport->adminModify($_POST['pwd'], $_POST['old_pwd']);
		
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if(!$res) {
				$this->message(0,'修改失败');
			} else {
				$this->message(1);
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
}