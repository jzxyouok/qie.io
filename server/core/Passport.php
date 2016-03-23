<?php
/*
 * 用户授权验证类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/02/18
 * 更新时间：2016/03/22
 * 
 */
 /*
  * database
 
CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(36) NOT NULL,
  `password` char(32) NOT NULL,
  `nick` char(64) NOT NULL DEFAULT 'q',
  `email` varchar(100) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '1982-10-21 00:00:00',
  `login_time` datetime DEFAULT '1982-10-21 00:00:00',
  `login_ip` varchar(100) DEFAULT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `nick` (`nick`),
  ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  */

class PassportException extends Exception{}

class Passport extends Model {
	private $user = array(); //用户数组
	public static $users = array();
	private $expire = 604800; //cookie过期时间
	private $auth = ''; //授权信息
	private $loginTime = null;
	private $loginIp = null;
	const MINIMUM_USER_ID = 1; //最小用户id
	const EXPIRE_MAX = 1209600; //cookie过期最长时间为一个星期，14*24*60*60
	const SALT = '!@#qie.$%^io&*()';
	
	/*
	 * 构造函数，检查登陆
	 */
	function __construct($user = false) {
		if(empty($user)) {
			$id = $_COOKIE['u_id'];
			$name = $_COOKIE['u_name'];
			$nick = $_COOKIE['u_nick'];
			$auth = $_COOKIE['u_auth'];
		} else {
			$id = $user['id'];
			$name = $user['name'];
			$nick = $user['nick'];
			$auth = $user['auth'];
		}
		//已登陆情况下，初始化用户信息
		if($id) {
			if($res = $this->verify($id, $name, $auth)) {
				$this->auth = $res;
				$this->user['id'] = $id;
				$this->user['name'] = $name;
				$this->user['nick'] = $nick;
			} else {
				//var_dump($_COOKIE);
				$this->logout();
			}
		}
		$this->loginTime = $_SERVER['REQUEST_TIME'];
		$this->loginIp = ip2long(Util::getIP());
	}
	/*
	 * 用户登陆
	 *
	 * @param string $name_or_email 用户名或者邮箱地址
	 * @param string $password 用户密码
	 * @param string $setCookie 是否记录cookie
	 *
	 * @return array/boolean 返回用户数组或者false
	 */
	public function login($name_or_email = '', $password = '') {
		if($this->user['id'])
			return true;
		$password = trim($password);
		$name_or_email = strtolower(trim($name_or_email));
		if(empty($name_or_email) || empty($password))
			return $this->error(1, '用户名或者密码为空');
		if(!preg_match(RegExp::PASSWORD, $password))
			return $this->error(2, '用户名或者密码为空');
			
		//判断是ID还是邮箱或者是用户名
		
		$sql = "SELECT `password`,`id`,`name`,`nick`,`email`,`login_ip` FROM `user` WHERE ";
		
		if(preg_match(RegExp::USERNAME, $name_or_email))
			$sql .= "`name`='{$name_or_email}' LIMIT 1";
		else if(false !== strpos($name_or_email, '@'))
			$sql .= "`email`='".addslashes($name_or_email)."' LIMIT 1";
		else
			return $this->error(3, '用户名或者邮箱格式错误');
		
		$db = Loader::load('database');
		$res = $db->query($sql);
		//如果查询为空
		if(empty($res))
			return $this->error(4, '用户名或者密码错误');
		
		if($res[0]['password'] !== $this->encode($password, substr($res[0]['password'], 0, 1)))
			return $this->error(5, '用户名或者密码错误');
		
		//初始化user数组
		$this->user['id'] = $res[0]['id'];
		$this->user['name'] = $res[0]['name'];
		$this->user['nick'] = $res[0]['nick'];
		//设置cookie和auth
		$this->setAuth();
		$this->setCookie();
		//存储最近5个登陆ip
		$res = explode(',', $res[0]['login_ip']);
		if(empty($res[0]))
			$res[0] = $this->loginIp;
		else
			array_push($res, $this->loginIp);
		if(count($res) > 5)
			array_shift($res);
		$ip = implode(',', $res);
		//更新登陆信息
		$sql = "UPDATE `user` SET `login_ip`='{$ip}',`login_time`='".date(DATE_FORMAT, $this->loginTime)."' WHERE `id`={$this->user['id']} LIMIT 1";
		$db->execute($sql);
		
		return array('id'=>$this->user['id'], 'name'=>$this->user['name'], 'nick'=>$this->user['nick'], 'auth'=>$this->auth);
	}
	/*
	 * 用户注册
	 *
	 * @param string $name 用户名
	 * @param string $email 用户邮箱地址
	 * @param string $password 用户密码
	 * @param string $nick 用户昵称
	 * @param string $setCookie 是否记录cookie
	 *
	 * @return array/boolean 返回用户数组或者false
	 */
	public function reg($name = '', $email = '', $nick = '', $password = '') {
		
	}
	/*
	 * 用户退出
	 *
	 * @return boolean
	 */
	public function logout() {
		
	}
	/*
	 * 验证合法性
	 *
	 * @param string $id 用户id
	 * @param string $name 用户名
	 * @param string $auth 授权信息
	 *
	 * @return boolean
	 */
	public function verify($id, $name, $auth) {
		if(empty($id) || !is_numeric($id) || self::MINIMUM_USER_ID > $id || empty($name) || !preg_match(RegExp::USERNAME, $name) || empty($auth))
			return false;
		
		$authArr = explode('_', $auth);
		if(2 <= count($authArr) && $authArr[0] === md5(Crypt::encrypt($id.$name.$authArr[1]))) {
			if($this->loginTime < ($authArr[1]-120) || $this->loginTime > ($authArr[1] + $this->expire))
				return false;
			else
				return $authArr[0];
		} else
			return false;
	}
	/*
	 * 密码加密方法
	 */
	public function encode($password, $index = false) {
		if(empty($password))
			return false;
			
		$new_password = '';
		$len = strlen($password);
		if(100 < $len) {
			//密码最长只支持100位
			$password = substr($password, 0, 100);
			$len = 100;
		}
		
		if($index === false) {
			$index = mt_rand(0, $len);
		}
		if($index > 9) {
			$index = 9; //保证密码是33位
		}
		if($index === 0) {
			$new_password = self::SALT.$password;
		} else if($index === $len) {
			//$index不可能>$len
			$new_password = $password.self::SALT;
		} else {
			$new_password = substr($password, 0, $index).self::SALT.substr($password, $index);
		}
		
		return $index.md5($new_password);
	}
	/*
	 * 设置用户授权验证
	 *
	 * @return boolean
	 */
	public function setAuth() {
		if(!empty($this->auth))
			return true;
		else if(!empty($this->user)) {
			$this->auth = md5(Crypt::encrypt($this->user['id'] . $this->user['name'] . $this->loginTime)) . '_' . $this->loginTime . '_'.$this->loginIp;
			return true;
		} else
			return false;
	}
	/*
	 * 获取用户授权验证信息
	 * 
	 * @return string 授权信息
	 */
	public function getAuth() {
		return $this->auth;
	}
	/*
	 * 设置用户登陆cookie
	 *
	 * @return boolean
	 */
	public function setCookie() {
		if(defined('DOMAIN'))
			$domain = DOMAIN;
		else
			$domain = $_SERVER['SERVER_NAME'];
		
		$e = $this->expire !== 0 ? $this->loginTime + $this->expire : 0;
		if(setcookie('u_id', $this->user['id'], $e, '/', '.'.$domain, 0, true) && setcookie('u_name', $this->user['name'], ($e === 0 ? $this->loginTime: $e) + 604800, '/', '.'.$domain, 0) && setcookie('u_nick', $this->user['nick'], ($e === 0 ? $this->loginTime: $e) + 604800, '/', '.'.$domain, 0) && setcookie('u_auth', $this->auth, $e, '/', '.'.$domain, 0, true))
			return true;
		else
			return false;
	}
	/*
	 * 获取用户信息
	 * 对于用户名刚好为数字的用户，必须用string形式传入用户名
	 *
	 * @param string $idOrName 用户id或者用户名，如果为空，返回当前登录用户
	 * @param string $type 查询类型。可选id,name,nick
	 *
	 * @return array/boolean 用户数组(array('id'=>,'name'=>,'nick'=>,'face'=>))或者false
	 */
	public function getUser($idOrName = FALSE, $type = 'id') {
		if(!$idOrName) {
			//返回当前用户
			if(!empty($this->user))
				return $this->user;
			else
				return false;
		} else {
			$idOrName = trim($idOrName);
			switch($type) {
				case 'id':
					//确认id
					if(!is_numeric($idOrName) || self::MINIMUM_USER_ID > $idOrName)
						return false;
					
					if(isset($this->user['id']) && $idOrName == $this->user['id'])
						return $this->user;
					
					if(!empty(self::$users[$idOrName]))
						return self::$users[$idOrName];
					
					$sql = "SELECT `id`,`name`,`nick` FROM `user` WHERE `id`={$idOrName} LIMIT 1";
					break;
				case 'name':
					//确认用户名
					if(!preg_match(RegExp::USERNAME, $idOrName))
						return false;
					
					if(isset($this->user['name']) && $idOrName == $this->user['name'])
						return $this->user;
					
					if(!empty(self::$users)) {
						foreach(self::$users as $v)
							if($v['name'] == $idOrName)
							return $v;
					}
					$sql = "SELECT `id`,`name`,`nick` FROM `user` WHERE `name`='{$idOrName}' LIMIT 1";
					break;
				case 'nick':
					if(strlen($idOrName) > 60)
						return false;
					
					$idOrName = addslashes($idOrName);
					
					if(isset($this->user['nick']) && $idOrName == $this->user['nick'])
						return $this->user;
					
					//如果之前已经存在
					if(!empty(self::$users)) {
						foreach(self::$users as $v)
							if($v['nick'] == $idOrName)
							return $v;
					}
					
					$sql = "SELECT `id`,`name`,`nick` FROM `user` WHERE `nick`='{$idOrName}' LIMIT 1";
					break;
				default:
					return false;
			}
			
			//查询其他用户	
			$db = Loader::load('database');
			$res = $db->query($sql);
			
			if($res[0]) {
				self::$users[$res['id']] = $res;
				return $res;
			} else
				return false;
		}
	}
}
