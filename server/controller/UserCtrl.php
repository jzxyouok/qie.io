<?php
/*
 * 用户管理
 * 作者：billchen
 * 邮箱：48838096@qq.com
 */

class UserCtrl extends Controller {
	/*
	 * page
	 */
	//登录页
	function index() {
		if(!empty($this->user)) {
			header('Location: '.($this->profile['admin_relogin']?'/index.php/user/center/':$this->profile['admin_dir'].'/'));
		}
		
		$this->display('user');
	}
	//注册页
	public function reg() {
		if(!empty($this->user))
			header('Location: /index.php/user/center/');
		
		$this->display('user_reg');
	}
	//个人中心
	public function center() {
		if(empty($this->user))
			header('Location: /index.php/user/');
		if(!$this->profile['admin_relogin'])
			header('Location: '.$this->profile['admin_dir'].'/');
		
		$user = Loader::load('Passport');
		$this->vars['data'] = $user->selectOne($this->user['id']);
		
		$this->display('user_center');
	}
	/*
	 * api
	 */
	//登录
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
		if(empty($_POST['user_name'])) {
			$this->message(-1, '请输入用户名', 4);
		}
		if(empty($_POST['pwd'])) {
			$this->message(-1, '请输入密码', 5);
		}
		try {
			$psp = Loader::load('Passport');
			$res = $psp->login($_POST['user_name'], $_POST['pwd']);
		
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res === false) {
				//数据库保存失败
				$this->message(0, '登录失败');
			} else {
				$psp->setExpire($this->profile['admin_relogin']?(!empty($_POST['expire'])?(int)$_POST['expire']:604800):0);
				$psp->setCookie();
				if($_GET['url']) {
					header("Location: {$_GET['url']}");
					exit;
				}
				
				$this->message(1, array('id'=>$res['id'], 'name'=>$res['name'], 'nick'=>$res['nick']));
				
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//插入新用户
	public function insert() {
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
		if(!$this->profile['admin_relogin'])
			$this->message(-1, '当前禁止注册', 7);
		
		try {
			$psp = Loader::load('Passport');
			$res = $psp->reg($_POST['user_name'], $_POST['pwd'], $_POST['email'], $_POST['nick']);
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res === false) {
				//数据库保存失败
				$this->message(0, '注册失败');
			} else {
				$psp->setExpire(!empty($_POST['expire'])?(int)$_POST['expire']:604800);
				$psp->setCookie();
				if($_GET['url']) {
					header("Location: {$_GET['url']}");
					exit;
				}
				$this->message(1, array('id'=>$res['id'], 'user_name'=>$res['name'], 'nick'=>$res['nick']));
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//退出
	public function logout() {
		$psp = Loader::load('Passport');
		if($psp->logout()) {
			if($_GET['url'])
				header("Location: {$_GET['url']}");
			else
				$this->message(1);
		}
	}
	//更新信息
	public function update() {
		$data = array();
		if(!empty($_POST['pwd']) && $_POST['pwd'] != $_POST['old_pwd']) {
			$data['pwd'] = $_POST['pwd'];
			$data['old_pwd'] = $_POST['old_pwd'];
		}
		if(!empty($_POST['email']))
			$data['email'] = $_POST['email'];
		if(!empty($_POST['nick']))
			$data['nick'] = $_POST['nick'];
		try {
			$psp = Loader::load('Passport');
			$res = $psp->modify(array('old_password'=>$data['old_pwd'], 'password'=>$data['pwd'], 'email'=>$data['email'], 'nick'=>$data['nick']));
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res === false) {
				//数据库保存失败
				$this->message(0, '修改失败');
			} else {
				if($_GET['url']) {
					header("Location: {$_GET['url']}");
					exit;
				}
			
				$this->message(1, array('id'=>$res['id'], 'user_name'=>$res['name'], 'nick'=>$res['nick']));
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
}