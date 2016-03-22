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
	protected $request = null;
	protected $paramPos = 1; //自动调用方法的参数uri位置
	protected $processStart = ''; //控制器加载开始时间
	protected $vars = array(); //页面模板需要的变量
	protected $funcs = array(); //页面模板需要的函数
	protected $classes = array(); //页面模板需要的函数
	protected $dynamicCode = "I'm Bill Chen(48838096@qq.com).(a%^&dream@#df$%fj&?<L#%25SWJfdsafsadf";
	
	function __construct($startTime = 0) {
		$this->processStart = empty($process_start)?microtime():$process_start; //计算性能
		$this->request = Loader::load('Request');
	}
	/*
	 * 加载view视图文件
	 * 
	 */
	protected function loadView($tpl = '') {
		if(empty($tpl))
			return false;
		$tpl = APP_PATH.'/view/'.$tpl.'.tpl';
		$this->view($tpl);
	}
	/*
	 * 加载theme视图(用户主题)文件
	 * 
	 */
	protected function loadTheme($tpl = '') {
		if(empty($tpl))
			return false;
		
		//确定用户主题文件夹
		$this->vars['theme'] = 'default';
		if($_COOKIE['theme'] && is_dir(DOCUMENT_ROOT."/theme/{$_COOKIE['theme']}"))
			$this->vars['theme'] = $_COOKIE['theme'];
		
		$tpl = DOCUMENT_ROOT.'/theme/'.$this->vars['theme'].'/'.$tpl.'.tpl';
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
		//assign变量
		foreach($this->vars as $k => $v)
			$view->assign($k, $v);
		//assign函数
		foreach($this->funcs as $k => $v)
			$view->registerPlugin("function", $k, $v);
		//assign类
		foreach($this->classes as $k => $v)
			$view->registerClass($k, $v);
		
		//assign
		$view->assign('token', $_SERVER['REQUEST_TIME'].Crypt::encrypt($_SERVER['REQUEST_TIME'], $this->dynamicCode)); //系统安全码
		$view->assign('memory_usage', round(memory_get_usage()/1048576, 3)); //运行时间
		list($msec1, $sec1) = explode(' ', $this->processStart);
		list($msec2, $sec2) = explode(' ', microtime());
		$view->assign('elapsed_time', round(((float)$msec2 + (float)$sec2) - ((float)$msec1 + (float)$sec1), 3));
		//显示模板
		$view->display($tpl);
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
	public function error($msg = '') {
		
	}
}