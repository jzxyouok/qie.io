<?php
/*
 * 控制器
 * Controller class
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/03/21
 *
 */
class Controller {
	const VERSION = '0.9';
	protected $user = array();
	protected $paramPos = 1; //自动调用方法的参数uri位置
	protected $vars = array(); //页面模板需要的变量
	protected $funcs = array(); //页面模板需要的函数
	protected $classes = array(); //页面模板需要的类
	protected $dynamicCode = "I'm Bill Chen(48838096@qq.com).(a%^&dream@#df$%fj&?<L#%25SWJfdsafsadf"; //生成页面token
	protected $profile = array(); //config/profile
	protected $autoload = array(); //自动执行,array('this'=>'isAdmin','Passport'=>'isAdmin');
	protected $autoloadResult = array();
	public $processStart = ''; //控制器加载开始时间
	public $dir = '';
	public $segments = array();
	
	function __construct() {
		//获取注册用户信息
		$this->user = Loader::load('Passport')->getUser();
		//加载网站配置文件
		$this->profile = Loader::loadVar(APP_PATH.'/config/profile.php', 'profile');
		$autoload = Loader::loadVar(APP_PATH.'/config/autoload.php', 'autoload');
		if($autoload && is_array($autoload))
			$this->autoload = array_merge($this->autoload, $autoload);
		//autoload
		foreach($this->autoload as $k => $v) {
			$obj = $k == 'this'?$this:Loader::load($k);
			$this->autoloadResult[$k][$v] = $obj->$v();
		}
	}
	/*
	 * 加载视图文件
	 * 
	 * @param string $tpl 视图文件地址 
	 */
	public function view($tpl = '', $type = 'view') {
		switch($type) {
			case 'theme': {
				//确定用户主题文件夹
				if(!empty($_COOKIE['theme']) && is_dir(DOCUMENT_ROOT."/theme/{$_COOKIE['theme']}"))
					$this->profile['theme'] = $_COOKIE['theme'];
		
				//assign
				$this->vars['icp'] = $this->profile['icp'];
				$this->vars['analytics'] = $this->profile['analytics'];
				$tpl = DOCUMENT_ROOT.'/theme/'.$this->profile['theme'].'/'.$tpl.'.tpl';
			}
			break;
			default: {
				//assign
				$this->vars['DOCUMENT_ROOT'] = DOCUMENT_ROOT;
				$this->vars['theme'] = $this->profile['theme'];
				$this->vars['admin_dir'] = $this->profile['admin_dir'];
				$this->vars['admin_relogin'] = $this->profile['admin_relogin'];
				$tpl = APP_PATH.'/view'.$this->dir.'/'.$tpl.'.tpl';
			}
		}
		if(!file_exists($tpl))
			return false;
			
		$this->vars['title'] = $this->profile['title']?$this->profile['title']:'默认网站';
		$this->vars['meta'] = $this->profile['meta'];
		$this->vars['homepage'] = $this->profile['homepage'];
		$this->vars['user'] = $this->user;
		$this->vars['token'] = $_SERVER['REQUEST_TIME'].Crypt::encrypt($_SERVER['REQUEST_TIME'], $this->dynamicCode); //系统安全码
		$this->vars['version'] = self::VERSION;
		
		$view = Loader::load('View');
		
		//assign函数
		foreach($this->funcs as $k => $v)
			$view->registerPlugin("function", $k, $v);
		//assign类
		foreach($this->classes as $k => $v)
			$view->registerClass($k, $v);
		//assign变量
		foreach($this->vars as $k => $v)
			$view->assign($k, $v);
		
		$view->assign('memory_usage', round(memory_get_usage()/1048576, 3)); //运行时间
		if($this->processStart) {
			list($msec1, $sec1) = explode(' ', $this->processStart);
			list($msec2, $sec2) = explode(' ', microtime());
			$view->assign('elapsed_time', round(((float)$msec2 + (float)$sec2) - ((float)$msec1 + (float)$sec1), 3));
		}
		//显示模板
		$view->display($tpl);
	}
	/*
	 * 根据token检查安全性
	 * 
	 * @param string $timeout 过期时间
	 *
	 * @return boolean true:pass,false:fail
	 */
	public function verify($timeout = 0) {
		if(empty($timeout))
			return true;
		
		$token = $_POST['token'] or $token = $_GET['token'];
		if(isset($token))
			$timestamp = substr($token, 0, 10);
		else
			return false;
		
		$token = substr($token, 10);
		
		$token_ = Crypt::encrypt($timestamp, $this->dynamicCode);
		if($token_ === $token) {
			if(empty($timeout))
				return true;
			else if((time() - $timestamp) <= $timeout)
				return true;
			else
				return false;
		} else
			return false;
	}
	/*
	 * 前后端数据交换
	 *
	 * @param int $status 状态码
	 * @param mix $result 结果
	 * @param string $error 错误代码
	 *
	 * @return void
	 */
	public function message($status, $result = '', $error = '') {
		if(empty($_GET['jsoncallback'])) {
			if(isset($_GET['x']))
				echo '<script>document.domain = "'.$_SERVER['HTTP_HOST'].'";</script>'; //javascript cross domain
			exit(json_encode(array('status'=>$status,'result'=>$result, 'error'=>$error)));
		} else
			exit($_GET['jsoncallback'].'('.(json_encode(array('status'=>$status,'result'=>$result, 'error'=>$error))).')');
	}
	/*
	 * 判断是否为管理员
	 *
	 * @param boolean $redirect 未登录状态下是否跳转到登录页面
	 *
	 * @return boolean
	 */
	public function hasAdminLogin($redirect = true) {
		if(!Loader::load('Passport')->isAdmin()) {
			//exit('need login');
			if($redirect)
				header('Location: '.($this->profile['admin_relogin']?$this->profile['admin_dir'].'/':'/index.php/user/'));
			else
				return false;
		}
		
		return true;
	}
	/*
	 * 设置自动调用方法的参数uri位置
	 * 
	 * @param int $pos uri位置
	 * 
	 */
	public function setParamPos($pos) {
		$this->paramPos = $pos;
	}
	/*
	 * 返回自动调用方法的参数uri位置
	 */
	public function getParamPos() {
		return $this->paramPos;
	}
	/*
	 * 获取segment
	 *
	 * @param int $pos
	 */
	public function getSegment($pos) {
		return isset($this->segments[$pos])?$this->segments[$pos]:false;
	}
}