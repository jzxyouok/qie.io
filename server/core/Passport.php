<?php
/*
 * 用户授权验证类
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2012/02/18
 * 更新时间：2016/03/22
 */
 /*
  * database
--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(36) NOT NULL,
  `password` char(33) NOT NULL,
  `nick` char(64) NOT NULL DEFAULT 'q',
  `email` varchar(100) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '1982-10-21 00:00:00',
  `login_time` datetime DEFAULT '1982-10-21 00:00:00',
  `login_ip` varchar(100) DEFAULT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_admin`
--

CREATE TABLE `user_admin` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `code` char(4) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_profile`
--

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `nick` (`nick`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_admin`
--
ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
	
INSERT INTO `user` (`id`, `name`, `password`, `nick`, `email`, `create_time`, `login_time`, `login_ip`, `tm`) VALUES
(1, 'admin', '6fc596211340374888eda68debf0846ce', '管理员', '48838096@qq.com', '2016-03-25 10:31:28', '2016-04-19 11:27:43', '2130706433,2130706433,2130706433,2130706433,2130706433', '2016-04-19 09:27:43');
INSERT INTO `user_admin` (`user_id`, `code`, `password`, `grade`) VALUES
(1, 'xq1/', 'c816215b20af26b3697a0d563bd9ee8d', 0);
  */

class PassportException extends Exception{}

class Passport extends Model {
	private $user = array(); //用户数组
	private $expire = 0; //cookie过期时间
	private $auth = ''; //授权信息
	private $loginTime = null;
	private $loginIP = null;
	public $table = 'user';
	public static $users = array();
	const MINIMUM_USER_ID = 1; //最小用户id
	const EXPIRE_MAX = 1209600; //cookie过期最长时间为一个星期，14*24*60*60
	const MY_SALT = '!@#qie.$%^io&*()';
	
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
		$this->loginTime = $_SERVER['REQUEST_TIME'];
		$this->loginIP = ip2long(Util::getIP());
		
		//$this->expire = 10;
		//已登陆情况下，初始化用户信息
		if($id) {
			if($res = $this->verify($id, $name, $auth)) {
				$this->auth = $res[0];
				$this->expire = $res[2];
				$this->user['id'] = $id;
				$this->user['name'] = $name;
				$this->user['nick'] = $nick;
			} else {
				//var_dump($_COOKIE);
				$this->logout();
			}
		}
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
		
		//[0]:auth,[1]:loginTime,[2]:expire,[3]:loginIP
		$authArr = explode('_', $auth);
		if(2 <= count($authArr) && $authArr[0] === md5(self::MY_SALT.(defined('SALT')?SALT:'').Crypt::encrypt($id.$name).$authArr[1].$authArr[2])) {
			//$this->expire>0时，可以实现强制重新登录
			if($authArr[2] > 0 && ($this->loginTime < $authArr[1] || $this->loginTime > ($authArr[1] + ($this->expire>0?$this->expire:$authArr[2]))))
				return false;
			else
				return $authArr;
		} else
			return false;
	}
	/*
	 * 用户登陆
	 *
	 * @param string $name_or_email 用户名或者邮箱地址
	 * @param string $password 用户密码
	 *
	 * @return array/boolean 返回用户数组或者false
	 */
	public function login($name_or_email = '', $password = '') {
		if($this->user['id'])
			return $this->user;
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
		
		$db = Loader::load('Database');
		$res = $db->query($sql);
		//如果查询为空
		if(empty($res))
			return $this->error(4, '用户名或者密码错误');
		if($res[0]['password'] !== self::encode($password, substr($res[0]['password'], 0, 1)))
			return $this->error(5, '用户名或者密码错误');
		
		//初始化user数组
		$this->user['id'] = $res[0]['id'];
		$this->user['name'] = $res[0]['name'];
		$this->user['nick'] = $res[0]['nick'];
		//设置cookie和auth
		$this->setAuth();
		//存储最近5个登陆ip
		$res = explode(',', $res[0]['login_ip']);
		if(empty($res[0]))
			$res[0] = $this->loginIP;
		else
			array_push($res, $this->loginIP);
		if(count($res) > 5)
			array_shift($res);
		$ip = implode(',', $res);
		//更新登陆信息
		$sql = "UPDATE `user` SET `login_ip`='{$ip}',`login_time`='".date(DATE_FORMAT, $this->loginTime)."' WHERE `id`={$this->user['id']} LIMIT 1";
		$db->execute($sql);
		
		return array('id'=>$this->user['id'], 'name'=>$this->user['name'], 'nick'=>$this->user['nick']);
	}
	/*
	 * 用户注册
	 *
	 * @param string $name 用户名
	 * @param string $password 用户密码
	 * @param string $email 用户邮箱地址
	 * @param string $nick 用户昵称
	 *
	 * @return array/boolean 返回用户数组或者false
	 */
	public function reg($name = '', $password = '', $email = '', $nick = '') {
		//检查用户名格式
		//if(is_numeric($name))
		//	throw new PassportException('用户名不能全为数字');
		if(!preg_match(RegExp::USERNAME, $name))
			return $this->error(1, '用户名只能使用[ 字母 数字 _ $ ]，并且只能以字母或$开头。');
		//检查邮箱格式
		if(!preg_match(RegExp::EMAIL, $email))
			return $this->error(2, '邮箱格式错误');
		//检查密码
		$password = trim($password);
		if(!preg_match(RegExp::PASSWORD, $password))
			return $this->error(3, '密码不能少于6个字符');
		//处理昵称
		if(empty($nick)) {
			$nick = Util::randCode(6, 'qwertyuiopasdfghjklzxcvbnm').$_SERVER['REQUEST_TIME'];
		} else
			$nick = addslashes(mb_substr(strip_tags(trim($nick)), 0, 64, 'utf-8'));
		
		$name = strtolower($name);
		$email = strtolower($email);
		//过滤不合法字符
		if($this->filter($name) || $this->filter($nick))
			return $this->error(3, '用户名或者昵称不允许使用');
		
		$db = Loader::load('Database');
		$sql = "INSERT INTO `user` (`name`,`email`,`password`,`nick`,`create_time`,`login_time`,`login_ip`) VALUES ('{$name}','{$email}','".self::encode($password)."','{$nick}','".date(DATE_FORMAT, $this->loginTime)."','".date(DATE_FORMAT, $this->loginTime)."','{$this->loginIP}')";
		$res = $db->execute($sql);
		if($res > 0) {
			//注册成功
			$this->user['id'] = $res;
			$this->user['name'] = $name;
			$this->user['nick'] = $nick;
			$this->setAuth();
			
			return array('id'=>$this->user['id'], 'name'=>$this->user['name'], 'nick'=>$this->user['nick']);
		}
		return false;
	}
	/*
	 * 用户退出
	 *
	 * @return boolean
	 */
	public function logout() {
		//如果用户没登陆
		if(empty($_COOKIE['u_id']) && empty($_COOKIE['u_auth']))
			return true;
		
		$this->auth = '';
		$this->user = array();
		
		$domain = '.'.(defined('DOMAIN') && DOMAIN?DOMAIN:$_SERVER['SERVER_NAME']);
		if(setcookie('u_id', '', ($this->loginTime - 60), '/', $domain, 0) && setcookie('u_auth', '', ($this->loginTime - 60), '/', $domain, 0))
			return true;
		else
			return false;
	}
	/*
	 * 用户授权信息修改
	 *
	 * @param string $name 用户名
	 * @param string $email 用户邮箱
	 * @param string $nick 用户昵称
	 * @param string $password 新用户密码
	 * @param string $old_password 旧用户密码，如果修改密码就需要
	 * @param string $setCookie 是否记录cookie
	 *
	 * @return boolean
	 */
	public function modify($cfg = array()){
		if(!empty($cfg['name'])) {
			//过滤用户名
			$cfg['name'] = strtolower(trim($cfg['name']));
			if($cfg['name'] == $this->user['name'] || $this->filter($cfg['name']))
				unset($cfg['name']);
			
			if($cfg['name'] && !preg_match(RegExp::USERNAME, $cfg['name']))
				return $this->error(1, '用户名不能包含特殊符号,只能包含:字母|数字|_|-');
		}
		if(!empty($cfg['email'])) {
			//过滤邮箱
			$cfg['email'] = strtolower(trim($cfg['email']));
			if(!preg_match(RegExp::EMAIL, $cfg['email']))
				return $this->error(2, '邮箱格式错误');
		}
		if(!empty($cfg['nick'])) {
			//过滤昵称
			$cfg['nick'] = addslashes(mb_substr(strip_tags(trim($cfg['nick'])), 0, 64, 'utf-8'));
			if($cfg['nick'] == $this->user['nick'] || $this->filter($cfg['nick']))
				unset($cfg['nick']);
		}
		
		$cfg['old_password'] = trim($cfg['old_password']);
		$cfg['password'] = trim($cfg['password']);
		if(!empty($cfg['old_password']) && !empty($cfg['password']) && $cfg['old_password'] != $cfg['password'] && preg_match(RegExp::PASSWORD, $cfg['password'])) {
			//过滤密码
		} else {
			unset($cfg['old_password']);
			unset($cfg['password']);
		}
		if(empty($cfg['password']) && empty($cfg['name']) && empty($cfg['email']) && empty($cfg['nick'])){
			//没有做任何修改的
			return false;
		}
		
		$db = Loader::load('Database');
		//查询新用户名或者新邮箱是否已经被其他人使用
		$sql = "SELECT (".($cfg['password'] ? "SELECT `password` FROM `user` WHERE `id`=".$this->user['id']." LIMIT 1" : "NULL").") AS `password`,(".(!empty($cfg['name']) ? "SELECT `name` FROM `user` WHERE `name`='{$cfg['name']}' AND `id`!=".$this->user['id']." LIMIT 1" : "NULL").") AS `name`,(".(!empty($cfg['email']) ? "SELECT `email` FROM `user` WHERE `email`='{$cfg['email']}' AND `id`!=".$this->user['id']." LIMIT 1" : "NULL").") AS `email`,(".(!empty($cfg['nick']) ? "SELECT `nick` FROM `user` WHERE `nick`='{$cfg['nick']}' AND `id`!=".$this->user['id']." LIMIT 1" : "NULL").") AS `nick`";
		$res = $db->query($sql);
		
		//查询旧密码是否正确
		if((!empty($cfg['old_password'])) && !empty($res[0]['password']) && $res[0]['password'] !== self::encode($cfg['old_password'], substr($res[0]['password'], 0, 1)))
			return $this->error(3, '原密码错误');
		//查询用户名是否被占用
		if(!empty($cfg['name']) && !empty($res[0]['name']))
			return $this->error(4, '用户名已经被占用');
		//查询邮箱是否被占用
		if(!empty($cfg['email']) && !empty($res[0]['email']))
			return $this->error(5, '邮箱已经被占用');
		//查询邮箱是否被占用
		if(!empty($cfg['nick']) && !empty($res[0]['nick']))
			return $this->error(6, '昵称已经被占用');
			
		//更新信息
		$sql = "UPDATE `user` SET ".(!empty($cfg['name']) ? "`name`='{$cfg['name']}'," : "").(!empty($cfg['email']) ? "`email`='{$cfg['email']}'," : "").(!empty($cfg['nick']) ? "`nick`='{$cfg['nick']}'," : "").(empty($cfg['password']) ? "" : "`password`='".self::encode($cfg['password'])."',")."`tm`=NOW() WHERE `id`=".$this->user['id']." LIMIT 1";
		$res = $db->execute($sql);
		
		if($res > 0) {
			if($cfg['name'] || $cfg['nick']) {
				//如果修改了用户名或者昵称
				if($cfg['name'])
					$this->user['name'] = $cfg['name'];
				else
					$this->user['nick'] = $cfg['nick'];
				$this->auth = '';
				$this->setAuth();
				$this->setCookie();
			}
			return array('id'=>$this->user['id'], 'name'=>$this->user['name'], 'nick'=>$this->user['nick'], 'auth'=>$this->auth);
		}
		return false;
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
			$this->auth = md5(self::MY_SALT.(defined('SALT')?SALT:'').Crypt::encrypt($this->user['id'] . $this->user['name']) . $this->loginTime . $this->expire) . '_' . $this->loginTime . '_' . $this->expire . '_'.$this->loginIP;
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
		$domain = '.'.(defined('DOMAIN') && DOMAIN?DOMAIN:$_SERVER['SERVER_NAME']);
		$e = $this->expire > 0 ? $this->loginTime + $this->expire : 0;
		
		if(setcookie('u_id', $this->user['id'], $e, '/', $domain, 0, true) && setcookie('u_name', $this->user['name'], $this->loginTime + $this->expire + 604800 , '/', $domain, 0) && setcookie('u_nick', $this->user['nick'], $this->loginTime + $this->expire + 604800, '/', $domain, 0) && setcookie('u_auth', $this->auth, $e, '/', $domain, 0, true))
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
	 * @return array/boolean 用户数组(array('id'=>,'name'=>,'nick'=>))或者false
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
			$db = Loader::load('Database');
			$res = $db->query($sql);
			
			if($res[0]) {
				self::$users[$res['id']] = $res;
				return $res;
			} else
				return false;
		}
	}
	/*
	 * 设置用户
	 *
	 * @param array $user array('id'=>int, 'name'=>string, 'nick'=>string, 'auth'=>string)
	 *
	 * @return boolean
	 */
	public function setUser($user = array()) {
		if(!is_int($user['id']) || empty($user['name']) || !preg_match(RegExp::USERNAME, $user['name']) || empty($user['auth']) || empty($user['nick']) || !($res = $this->verify($user['id'], $user['name'], $user['auth'])))
			return false;
		
		$this->auth = $res[0];
		$this->expire = $res[2];
		$this->user['id'] = $user['id'];
		$this->user['name'] = $user['name'];
		$this->user['nick'] = $user['nick'];
		return true;
	}
	/*
	 * 密码加密方法
	 */
	public static function encode($password, $index = false) {
		if(empty($password))
			return false;
			
		$newPassword = '';
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
			$newPassword = self::MY_SALT.$password;
		} else if($index === $len) {
			//$index不可能>$len
			$newPassword = $password.self::MY_SALT;
		} else {
			$newPassword = substr($password, 0, $index).self::MY_SALT.substr($password, $index);
		}
		return $index.md5($newPassword);
	}
	/*
	 * 设置用户登陆cookie过期时间
	 *
	 * @param string $expire cookie失效时间，可以传入负数
	 */
	public function setExpire($expire = 0) {
		if(!is_int($expire))
			return false;
		
		$this->expire = $expire;
	}
	/*
	 * 关键词过滤
	 *
	 * @param string $word 要过滤的词
	 *
	 * @return string
	 */
	public function filter($word) {
		$sensitiveWords = Loader::loadVar(APP_PATH.'/config/words.php', 'sensitiveWords');
		return in_array($word, $sensitiveWords);
	}
	/*
	 * 管理后台登录
	 *
	 * @param string $password 密码
	 *
	 * @return boolean
	 */
	public function adminLogin($password = 0) {
		if(empty($password))
			return $this->error(1, '密码不能为空');
		if(empty($this->user))
			return $this->error(2, '请先登录前端页面');
		$password = trim($password);
		
		$db = Loader::load('Database');
		$sql = "SELECT `code`,`grade`,`password` FROM `user_admin` WHERE `user_id`={$this->user['id']} LIMIT 1";
		$res = $db->query($sql);
		//管理员不存在
		if(empty($res))
			return $this->error(3, '管理员不存在');
		//密码错误
		if(md5($res[0]['code'].$password) != $res[0]['password'])
			return false;
		
		if(setcookie('a_code', $res[0]['code'], 0, '/') && setcookie('a_verify', md5($this->user['id'].(defined('SALT')?SALT:'').$res[0]['code'].$res[0]['grade']), 0, '/', NULL, 0, true) && setcookie('a_grade', (int)$res[0]['grade'], 0, '/')){
			$this->admin = array('grade' => $res[0]['grade'], 'code' => $res[0]['code']);
			return true;
		} else
			return false;
	}
	/*
	 * 管理后台退出
	 *
	 * @return boolean
	 */
	public function adminLogout() {
		return setcookie('a_code', '', time()-600, '/') && setcookie('a_verify', '', time()-600, '/') && setcookie('a_grade', '', time()-600, '/');
	}
	/*
	 * 修改后台密码
	 *
	 * @param string $password 密码
	 * @param string $oldPassword 旧密码
	 *
	 * @return boolean
	 */
	public function adminModify($password = '', $oldPassword = '') {
		if(empty($this->user))
			return $this->error(1, '请先登录前端页面');
		
		$password = addslashes(trim($password));
		$oldPassword = addslashes(trim($oldPassword));
		if($password == $oldPassword)
			return $this->error(2, '新旧密码一致');
		
		$code = Util::randCode(4, '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ,./?#:@~[]{}-_=+)(*%$');
		$sql = "UPDATE `user_admin` SET `code`='{$code}',`password`=MD5('{$code}{$password}') WHERE `user_id`={$this->user['id']} AND EXISTS (SELECT * FROM (SELECT `code` FROM `user_admin` WHERE `user_id`={$this->user['id']} AND `password`=MD5('{$_COOKIE['a_code']}{$oldPassword}') LIMIT 1) AS `tmp`) LIMIT 1";
		$db = Loader::load('Database');
		return $db->execute($sql) && setcookie('a_code', $code, 0, '/') && setcookie('a_verify', md5($this->user['id'].(defined('SALT')?SALT:'').$code.$_COOKIE['a_grade']), 0, '/', NULL, 0, true);
	}
	/*
	 * 判断是否为管理员
	 *
	 * @return boolean
	 */
	public function isAdmin() {
		$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
		return $this->user && (!$profile['admin_relogin'] || (!empty($_COOKIE['a_code']) && isset($_COOKIE['a_grade']) && !empty($_COOKIE['a_verify']) && $_COOKIE['a_verify'] == md5($_COOKIE['u_id'].(defined('SALT')?SALT:'').$_COOKIE['a_code'].$_COOKIE['a_grade'])));
	}
	/*
	 * 获取单个用户详细信息
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function selectOne($id = 0) {
		if(!is_numeric($id) || $id < 1)
			return array();
		
		$sql = "SELECT * FROM `{$this->table}` WHERE `id`={$id} LIMIT 1";
		$db = Loader::load('Database');
		$res = $db->query($sql);
		return $res[0];
	}
	/*
	 * 插入一个用户
	 */
	public function insert($data) {
		if(empty($data['name']) || !preg_match(RegExp::USERNAME, $data['name']))
			return $this->error(1, '请输入用户名');
		if(empty($data['nick']))
			return $this->error(2, '请输入昵称');
		if(empty($data['email']) || !preg_match(RegExp::EMAIL, $data['email']))
			return $this->error(3, '邮箱格式错误');
		if(empty($data['password']))
			return $this->error(4, '请输入密码');
		
		$data['password'] = self::encode($data['password']);
		return parent::insert($data);
	}
	/*
	 * 更新一个用户
	 */
	public function update($data = array()) {
		$id = (int)$data['where'];
		if($id < 1)
			return $this->error(1, '请输入id');
		if($data['data']['name'] && !preg_match(RegExp::USERNAME, $data['data']['name']))
			return $this->error(2, '用户名格式错误');
		if($data['data']['email'] && !preg_match(RegExp::EMAIL, $data['data']['email']))
			return $this->error(3, '邮箱格式错误');
		if($data['data']['password'])
			$data['data']['password'] = self::encode($data['data']['password']);
		
		$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
		if($profile['admin_relogin'])
			$data['where'] = "`id`={$id} AND EXISTS (SELECT `g1` FROM (SELECT `tmp1`.`grade` AS `g1`,`tmp2`.`grade` AS `g2` FROM (SELECT `grade` FROM `user_admin` WHERE `user_id`={$this->user['id']} LIMIT 1) AS `tmp1` LEFT JOIN (SELECT `grade` FROM `user_admin` WHERE `user_id`={$id} LIMIT 1) AS `tmp2` ON 1=1) AS `tmp` WHERE `g2` IS NULL OR `g1`<`g2`)";
		else
			$data['where'] = "`id`={$id}";
		
		return parent::update($data);
	}
	/*
	 * 删除用户
	 *
	 * @param array/int/string $ids
	 *
	 * @return int
	 */
	public function delete($ids = array()) {
		if(empty($ids))
			return false;
		
		$cfg = array();
		if(is_numeric($ids)) {
			$cfg = array('where'=>'`id`='.(int)$ids, 'limit'=>1);
		} else {
			if(is_string($ids))
				$ids = explode(',', $ids);
			
			while(list($k, $v) = each($ids)) {
				if(!is_numeric($v))
					unset($ids[$k]);
			}
			$cfg['limit'] = count($ids);
			$ids = implode(',', $ids);
			$cfg['where'] = '`id` IN ('.$ids.')';
		}
		//不能删除管理员
		$profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
		if($profile['admin_relogin'])
			$cfg['where'] .= ' AND `id` NOT IN (SELECT `user_id` FROM `user_admin` WHERE `user_id` IN ('.$ids.')) AND EXISTS (SELECT `grade` FROM `user_admin` WHERE `user_id`='.$this->user['id'].' LIMIT 1)';
		
		return parent::delete($cfg);
	}
	/*
	 * 管理员列表
	 */
	public function selectAdmin($cfg) {
		$cfg['field'] = array(array('table'=>$this->table,'column'=>'*'),array('table'=>'user_admin','column'=>'grade'));
		$cfg['table'] = array(array('name'=>'user_admin','type'=>'RIGHT JOIN', 'on'=>'`user_admin`.`user_id`=`'.$this->table.'`.`id`'));
		
		return parent::select($cfg);
	}
	/*
	 * 添加/修改后台管理员
	 *
	 * @param int $userId
	 * @param string $password 密码
	 *
	 * @return boolean
	 */
	public function updateAdmin($userId, $password = 0) {
		if(empty($password))
			return $this->error(1, '密码不能为空');
			
		$userId = (int)$userId;
		
		$code = Util::randCode(4, '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ,./?#:@~[]{}-_=+)(*%$');
		$password = md5($code.trim($password));
		$db = Loader::load('Database');
		$sql = "INSERT `user_admin` (`user_id`,`password`,`code`,`grade`) VALUES ((SELECT `id` FROM `user` WHERE `id`={$userId} LIMIT 1),'{$password}','{$code}',(SELECT * FROM (SELECT `grade`+1 FROM `user_admin` WHERE `user_id`={$this->user['id']} LIMIT 1) AS `tmp`))";
		$res = $db->execute($sql);
		if(!$res) {
			$sql = "UPDATE `user_admin` SET `password`='{$password}',`code`='{$code}' WHERE `user_id`={$userId} AND `grade`>(SELECT * FROM (SELECT `grade` FROM `user_admin` WHERE `user_id`={$this->user['id']} LIMIT 1) AS `tmp`)";
			$res = $db->execute($sql);
		}
		
		return $res;
	}
	/*
	 * 删除后台管理员
	 *
	 * @param string $ids 密码
	 *
	 * @return int
	 */
	public function deleteAdmin($ids = array()) {
		if(empty($ids))
			return false;
		
		$cfg = array();
		if(is_numeric($ids)) {
			$cfg = array('where'=>'`user_id`='.(int)$ids, 'limit'=>1);
		} else {
			if(is_string($ids))
				$ids = explode(',', $ids);
			
			while(list($k, $v) = each($ids)) {
				if(!is_numeric($v))
					unset($ids[$k]);
			}
			$cfg['limit'] = count($ids);
			$ids = implode(',', $ids);
			$cfg['where'] = '`user_id` IN ('.$ids.')';
		}
		
		$cfg['where'] .= " AND `grade`>(SELECT * FROM (SELECT `grade` FROM `user_admin` WHERE `user_id`={$this->user['id']} LIMIT 1) AS `tmp`)";
		$table = $this->table;
		$this->table = 'user_admin';
		$res = parent::delete($cfg);
		$this->table = $table;
		return $res;
	}
}
