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
		
		$user = Loader::load('model/User');
		$this->vars['data'] = $user->select(array('now'=>$now, 'row'=>$row));
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
	/*
	 * API
	 */
	function insert() {
		
	}
	function update() {
		if($_POST['name'])
			$data['name'] = $_POST['name'];
		if($_POST['nick'])
			$data['nick'] = $_POST['nick'];
		if($_POST['email'])
			$data['email'] = $_POST['email'];
		if($_POST['pwd'])
			$data['password'] = $_POST['pwd'];
		
		if(empty($data))
			$this->message(-1, '没有修改的内容', 1);
		
		$user = Loader::load('model/User');
		$res = $user->update($data);
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, '操作成功');
		} else {
			$this->message(0, '操作失败');
		}
	}
	function delete() {
		
	}
}