<?php

class SettingCtrl extends Controller {
	private $passport = null;
	
	function __construct() {
		parent::__construct();
		$this->passport = Loader::load('Passport');
		
	}
	//管理界面首页
	function index() {
		$this->vars['profile'] = $this->config['profile'];
		$this->loadView('setting');
	}
	//保存
	public function save() {
		$data = array();
		
		if(!empty($_POST['admin_relogin']))
			$data['admin_relogin'] = ($_POST['admin_relogin'] == 'true'?true:false);
		if(isset($_POST['domain']))
			$data['domain'] = $_POST['domain'];
		if(isset($_POST['homepage']))
			$data['homepage'] = $_POST['homepage'];
		if(!empty($_POST['theme']))
			$data['theme'] = $_POST['theme'];
		if(isset($_POST['title']))
			$data['title'] = $_POST['title'];
		if(isset($_POST['keywords']))
			$data['keywords'] = $_POST['keywords'];
		if(isset($_POST['description']))
			$data['description'] = $_POST['description'];
		if(isset($_POST['analytics']))
			$data['analytics'] = $_POST['analytics'];
		if(isset($_POST['icp']))
			$data['icp'] = $_POST['icp'];
		
		$setting = Loader::load('Setting');
		$res = $setting->update($data);
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1);
		} else {
			$this->message(0,'更新失败');
		}
	}
}