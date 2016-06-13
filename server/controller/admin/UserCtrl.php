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
	
	/*
	 * page
	 */
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = ($_GET['type'] == 'name'?'name':'nick').' LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'].'%"';
		}
		$orderBy = 'id_desc';
		if($_GET['orderby']) {
			$orderBy = $_GET['orderby'];
		}
		$orderBy = strtr($orderBy, '_', ' ');
		$psp = Loader::load('Passport');
		$this->vars['data'] = $psp->select(array('where'=>$where, 'current'=>$now, 'size'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'total'=>$this->vars['data']['total'],
			'size'=>$this->vars['data']['size'],
			'current'=>$this->vars['data']['current'],
			'uri'=>$this->profile['admin_dir'].'/index.php/user/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->display('user');
	}
	//添加用户
	function add() {
		$this->display('user_add');
	}
	//编辑用户
	function edit($id= 0) {
		$psp = Loader::load('Passport');
		$this->vars['data'] = $psp->selectOne($id);
		
		$this->display('user_edit');
	}
	//管理员列表
	function admin($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		$orderBy = 'grade_desc';
		if($_GET['orderby']) {
			$orderBy = $_GET['orderby'];
		}
		$orderBy = strtr($orderBy, '_', ' ');
		
		$psp = Loader::load('Passport');
		$this->vars['data'] = $psp->selectAdmin(array('now'=>$now, 'row'=>$row, 'order'=>$orderBy));
		
		$pagination = Loader::load('Pagination', array(array(
			'sum'=>$this->vars['data']['sum'],
			'row'=>$this->vars['data']['row'],
			'now'=>$this->vars['data']['now'],
			'uri'=>$this->profile['admin_dir'].'/index.php/user/admin/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->display('user_admin');
	}
	//编辑管理员
	function admin_edit($id = 1) {
		$this->vars['id'] = (int)$id;
		
		$this->display('user_admin_edit');
	}
	/*
	 * api
	 */
	//添加用户
	function insert() {
		if($_POST['user_name'])
			$data['name'] = $_POST['user_name'];
		if($_POST['nick'])
			$data['nick'] = $_POST['nick'];
		if($_POST['email'])
			$data['email'] = $_POST['email'];
		if($_POST['pwd']) {
			$data['password'] = $_POST['pwd'];
		}
			
		try {
			$psp = Loader::load('Passport');
			$res = $psp->insert($data);
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res) {
				$this->message(1, '操作成功');
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//更新用户
	function update($id = 0) {
		if($_POST['user_name'])
			$data['name'] = $_POST['user_name'];
		if($_POST['nick'])
			$data['nick'] = $_POST['nick'];
		if($_POST['email'])
			$data['email'] = $_POST['email'];
		if($_POST['pwd']) {
			$data['password'] = $_POST['pwd'];
		}
		if($_POST['field'] && in_array($_POST['field'], array('name', 'nick', 'email'))) {
			$data[$_POST['field']] = $_POST['value'];
		}
		if(empty($data))
			$this->message(-1, '没有修改的内容', 1);
		
		try {
			$psp = Loader::load('Passport');
			$res = $psp->update(array('data'=>$data, 'where'=>$id, 'limit'=>1));
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res) {
				$this->message(1, '操作成功');
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//删除用户
	function delete($ids = 0) {
		if(empty($ids))
			$ids = $_POST['ids'];
		
		if(empty($ids))
			$this->message(-1, '没有修改的内容', 1);
		
		try {
			$psp = Loader::load('Passport');
			$res = $psp->delete($ids);
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res) {
				$this->message(1, $res);
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//添加/更新管理员
	function admin_update($id = 0) {
		$password = $_POST['pwd'];
		if(empty($password))
			$this->message(-1, '没有修改的内容', 1);
		
		try {
			$psp = Loader::load('Passport');
			$res = $psp->updateAdmin($id, $password);
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res) {
				$this->message(1, '操作成功');
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//删除管理员
	function admin_delete($ids = 0) {
		if(empty($ids))
			$ids = $_POST['ids'];
		
		if(empty($ids))
			$this->message(-1, '没有修改的内容', 1);
		
		try {	
			$psp = Loader::load('Passport');
			$res = $psp->deleteAdmin($ids);
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res) {
				$this->message(1, $res);
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
}