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
	protected $user = array();
	protected $request = null;
	protected $paramPos = 1; //自动调用方法的参数uri位置
	protected $processStart = ''; //控制器加载开始时间
	protected $vars = array(); //页面模板需要的变量
	protected $funcs = array(); //页面模板需要的函数
	protected $classes = array(); //页面模板需要的类
	protected $dynamicCode = "I'm Bill Chen(48838096@qq.com).(a%^&dream@#df$%fj&?<L#%25SWJfdsafsadf";
	protected $profile = array(); //网站配置(title,keywords,js,css...)
	protected $config = array();
	
	function __construct($startTime = 0) {
		$this->processStart = empty($startTime)?microtime():$startTime; //计算性能
		$this->request = Loader::load('request');
		$this->user = Loader::load('passport')->getUser();
		
		//初始化网站配置
		$this->profile['css'] = array('<link type="text/css" rel="stylesheet" href="/static/css/reset.css">');
		$this->profile['js'] = array('<script src="/static/js/jquery.min.js"></script><script src="/static/js/util.js"></script>');
		//加载网站配置文件
		$this->loadConfig('profile');
		$this->profile['theme'] = $this->config['profile']['theme'];
	}
	/*
	 * 加载view视图文件
	 * 
	 */
	public function loadView($tpl = '') {
		if(empty($tpl))
			return false;
		
		$tpl = APP_PATH.'/view/'.$tpl.'.tpl';
		$this->view($tpl);
	}
	/*
	 * 加载theme视图(用户主题)文件
	 * 
	 */
	public function loadTheme($tpl = '') {
		if(empty($tpl))
			return false;
		
		//确定用户主题文件夹
		if(!empty($_COOKIE['theme']) && is_dir(DOCUMENT_ROOT."/theme/{$_COOKIE['theme']}"))
			$this->profile['theme'] = $_COOKIE['theme'];
		//引入js和css
		$this->profile['css'] = array_merge($this->profile['css'] , $this->config['profile']['css']);
		$this->profile['js'] = array_merge($this->profile['js'] , $this->config['profile']['js']);
		
		$tpl = DOCUMENT_ROOT.'/theme/'.$this->profile['theme'].'/'.$tpl.'.tpl';
		$this->view($tpl);
	}
	/*
	 * 加载theme视图(用户主题)文件
	 * 
	 */
	protected function view($tpl = '') {
		if(!file_exists($tpl))
			return false;
		
		
		$view = Loader::load('view');
		
		//assign函数
		foreach($this->funcs as $k => $v)
			$view->registerPlugin("function", $k, $v);
		//assign类
		foreach($this->classes as $k => $v)
			$view->registerClass($k, $v);
		
		//assign变量
		$this->vars['theme'] = $this->profile['theme'];
		$this->vars['title'] = $this->config['profile']['title'];
		$this->vars['meta'] = $this->config['profile']['meta'];
		$this->vars['icp'] = $this->config['profile']['icp'];
		$this->vars['analytics'] = $this->config['profile']['analytics'];
		$this->vars['js'] = implode('', $this->profile['js']);
		$this->vars['css'] = implode('', $this->profile['css']);
		$this->vars['user'] = $this->user;
		$this->vars['token'] = $_SERVER['REQUEST_TIME'].Crypt::encrypt($_SERVER['REQUEST_TIME'], $this->dynamicCode); //系统安全码
		
		foreach($this->vars as $k => $v)
			$view->assign($k, $v);
		
		$view->assign('memory_usage', round(memory_get_usage()/1048576, 3)); //运行时间
		list($msec1, $sec1) = explode(' ', $this->processStart);
		list($msec2, $sec2) = explode(' ', microtime());
		$view->assign('elapsed_time', round(((float)$msec2 + (float)$sec2) - ((float)$msec1 + (float)$sec1), 3));
		//显示模板
		$view->display($tpl);
	}
	protected function loadConfig($name) {
		if(!empty($this->config[$name]))
			return $this->config[$name];
		
		$this->config[$name] = Loader::loadVar(APP_PATH.'/config/'.$name.'.php');
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
	 * 校验验证码
	 *
	 * @param int $status 状态码
	 *
	 * @return int 返回值>=0
	 */
	/*
	 * 检查安全性
	 * @param string $code 加密的字符串
	 * @param string $timeout 过期时间
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
}