<?php
/*
 * 后台网站设定管理
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class SettingCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//首页
	function index() {
		$setting = Loader::load('Setting');
		$this->vars['profile'] = $setting->getProfile();
		$this->vars['database'] = $setting->getDatabase();
		$this->vars['db_profile'] = $_GET['db_profile'] && isset($this->vars['database'][$_GET['db_profile']])?$_GET['db_profile']:'default';
		$this->loadView('setting');
	}
	//更新profile
	public function update() {
		$data = array();
		
		if(!empty($_POST['admin_relogin']))
			$data['admin_relogin'] = ($_POST['admin_relogin'] == 'true'?true:false);
		if(isset($_POST['manage_dir']))
			$data['manage_dir'] = $_POST['manage_dir'];
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
		$res = $setting->setProfile($data);
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1);
		} else {
			$this->message(0,'更新失败');
		}
	}
	//更新database
	public function update_db() {
		$setting = Loader::load('Setting');
		$db = $setting->getDatabase();
		/*
		if(!isset($db[$_POST['db_profile']]))
			$this->message(-1, '数据库配置不存在', 1);
		*/
		
		$db[$_POST['db_profile']]['user'] = trim($_POST['user']);
		$db[$_POST['db_profile']]['password'] = trim($_POST['password']);
		$db[$_POST['db_profile']]['host'] = trim($_POST['host']);
		$db[$_POST['db_profile']]['db'] = trim($_POST['db']);
		$db[$_POST['db_profile']]['port'] = (int)$_POST['port'];
		$db[$_POST['db_profile']]['charset'] = trim($_POST['charset']);
		
		$res = $setting->setDatabase($db);
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1);
		} else {
			$this->message(0,'更新失败');
		}
	}
	//测试database
	public function check_db() {
		try {
			$db = Loader::load('Database', array(''));
			$db->connect(array(
					'user'=>trim($_POST['user']),
					'password'=>trim($_POST['password']),
					'host'=>trim($_POST['host']),
					'db'=>trim($_POST['db']),
					'port'=>(int)$_POST['port'],
					'charset'=>trim($_POST['charset'])
			));
			$this->message(1);
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//添加database
	public function add_db() {
		
	}
	//添加database
	public function delete_db() {
		
	}
}