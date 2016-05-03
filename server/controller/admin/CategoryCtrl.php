<?php
/*
 * 后台文章管理
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class CategoryCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = '`title` LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'];
		}
		
		$orderBy = 'id_desc';
		if($_GET['orderby']) {
			$orderBy = $_GET['orderby'];
		}
		$orderBy = strtr($orderBy, '_', ' ');
		$category = Loader::load('model/Category');
		$this->vars['data'] = $category->select(array('where'=>$where, 'now'=>$now, 'row'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'sum'=>$this->vars['data']['sum'],
			'row'=>$this->vars['data']['row'],
			'now'=>$this->vars['data']['now'],
			'uri'=>$this->profile['admin_dir'].'/index.php/category/'
		)));
		$this->vars['pagination'] = $pagination->get();
		$category->fix();
		$this->loadView('category');
	}
	function add() {
		$orderBy = '`depth` ASC';
		$category = Loader::load('model/Category');
		$this->vars['data'] = $category->select(array('where'=>$where, 'row'=>0, 'order'=>$orderBy));
		$this->loadView('category_add');
	}
	function edit($id = 0) {
		$orderBy = '`depth` ASC';
		$category = Loader::load('model/Category');
		$this->vars['category'] = $category->selectOne($id);
		$this->vars['data'] = $category->select(array('where'=>$where, 'row'=>0, 'order'=>$orderBy));
		$this->loadView('category_edit');
	}
	/*
	 * API
	 */
	function insert() {
		if(empty($_POST['name']))
			$this->message(-1, '请输入名称', 1);
		
		$category = Loader::load('model/Category');
		$res = $category->insert(array('name'=>$_POST['name'], 'description'=>$_POST['description'], 'parent_id'=>$_POST['parent_id']));
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, $res);
		} else {
			$this->message(0, '操作失败');
		}
	}
	function update($id = 0) {
		
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, $res);
		} else {
			$this->message(0, '操作失败');
		}
	}
	//删除
	function delete($ids = 0) {
		
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, $res);
		} else {
			$this->message(0, '操作失败');
		}
	}
}