<?php
/*
 * 后台用户管理
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class UserCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = ($_GET['type'] == 'name'?'name':'nick').' LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'].'%"';
		}
		$orderBy = '';
		if($_GET['orderby']) {
			$orderBy = strtr($_GET['orderby'], '_', ' ');
		}
		
		$user = Loader::load('model/User');
		$this->vars['data'] = $user->select(array('where'=>$where, 'now'=>$now, 'row'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'sum'=>$this->vars['data']['sum'],
			'row'=>$this->vars['data']['row'],
			'now'=>$this->vars['data']['now'],
			'uri'=>$this->profile['admin_dir'].'/index.php/user/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->loadView('user');
	}
	//添加
	function add() {
		$this->loadView('user_add');
	}
	//编辑
	function edit($id= 0) {
		$user = Loader::load('model/User');
		$this->vars['data'] = $user->selectOne($id);
		
		$this->loadView('user_edit');
	}
	//编辑
	function admin($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = ($_GET['type'] == 'name'?'name':'nick').' LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'].'%"';
		}
		$orderBy = '';
		if($_GET['orderby']) {
			$orderBy = strtr($_GET['orderby'], '_', ' ');
		}
		
		$user = Loader::load('model/User');
		$this->vars['data'] = $user->selectAdmin(array('where'=>$where, 'now'=>$now, 'row'=>$row, 'order'=>$orderBy));
		
		$pagination = Loader::load('Pagination', array(array(
			'sum'=>$this->vars['data']['sum'],
			'row'=>$this->vars['data']['row'],
			'now'=>$this->vars['data']['now'],
			'uri'=>$this->profile['admin_dir'].'/index.php/user/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->loadView('user_admin');
	}
	function admin_edit($id = 1) {
		$this->vars['id'] = (int)$id;
		
		$this->loadView('user_admin_edit');
	}
	/*
	 * API
	 */
	function insert() {
		if($_POST['user_name'] && preg_match(RegExp::USERNAME, $_POST['user_name']))
			$data['name'] = $_POST['user_name'];
		if($_POST['nick'])
			$data['nick'] = $_POST['nick'];
		if($_POST['email'])
			$data['email'] = $_POST['email'];
		if($_POST['pwd']) {
			$psp = Loader::load('Passport');
			$data['password'] = Passport::encode($_POST['pwd']);
		}
		if(empty($data['name']) || empty($data['nick']) || empty($data['email']) || empty($data['password']))
			$this->message(-1, '信息不完整', 1);
			
		$user = Loader::load('model/User');
		$res = $user->insert($data);
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, '操作成功');
		} else {
			$this->message(0, '操作失败');
		}
	}
	function update($id = 0) {
		if($_POST['user_name'])
			$data['name'] = $_POST['user_name'];
		if($_POST['nick'])
			$data['nick'] = $_POST['nick'];
		if($_POST['email'])
			$data['email'] = $_POST['email'];
		if($_POST['pwd']) {
			$psp = Loader::load('Passport');
			$data['password'] = Passport::encode($_POST['pwd']);
		}
		if($_POST['field'] && in_array($_POST['field'], array('name', 'nick', 'email'))) {
			$data[$_POST['field']] = $_POST['value'];
		}
		if($data['name'] && !preg_match(RegExp::USERNAME, $data['name'])) {
			unset($data['name']);
		}
		if(empty($data))
			$this->message(-1, '没有修改的内容', 1);
		
		$user = Loader::load('model/User');
		$res = $user->update(array('data'=>$data, 'where'=>'`id`='.(int)$id, 'limit'=>1));
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, '操作成功');
		} else {
			$this->message(0, '操作失败');
		}
	}
	function delete($ids = 0) {
		if(empty($ids))
			$ids = $_POST['ids'];
		
		if(empty($ids))
			$this->message(-1, '没有修改的内容', 1);
		
		$user = Loader::load('model/User');
		$res = $user->delete($ids);
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, $res);
		} else {
			$this->message(0, '操作失败');
		}
	}
	function admin_update($id = 0) {
		$password = $_POST['pwd'];
		if(empty($password))
			$this->message(-1, '没有修改的内容', 1);
		
		$user = Loader::load('model/User');
		$res = $user->updateAdmin($id, $password);
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, '操作成功');
		} else {
			$this->message(0, '操作失败');
		}
	}
	function admin_delete($id = 0) {
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, '操作成功');
		} else {
			$this->message(0, '操作失败');
		}
	}
}