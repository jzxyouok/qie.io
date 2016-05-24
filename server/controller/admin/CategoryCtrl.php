<?php
/*
 * 分类管理
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class CategoryCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	/*
	 * page
	 */
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = '`name` LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'].'%"';
		}
		
		$orderBy = 'id_desc';
		if($_GET['orderby']) {
			$orderBy = $_GET['orderby'];
		}
		$orderBy = strtr($orderBy, '_', ' ');
		$category = Loader::load('model/Category');
		$this->vars['data'] = $category->select(array('where'=>$where, 'current'=>$now, 'size'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'total'=>$this->vars['data']['total'],
			'size'=>$this->vars['data']['size'],
			'current'=>$this->vars['data']['current'],
			'uri'=>$this->profile['admin_dir'].'/index.php/category/'
		)));
		$this->vars['pagination'] = $pagination->get();
		//$category->fix();
		$this->view('category');
	}
	//添加分类
	function add() {
		$orderBy = '`root_id` ASC';
		$category = Loader::load('model/Category');
		$this->vars['category'] = $category->select(array('where'=>$where, 'row'=>0, 'order'=>$orderBy));
		$this->vars['category']['result'] = Category::makeSelectList($this->vars['category']['result'], 0);
		$this->view('category_add');
	}
	//修改分类
	function edit($id = 0) {
		$orderBy = '`root_id` ASC';
		$category = Loader::load('model/Category');
		$this->vars['data'] = $category->selectOne($id);
		$this->vars['category'] = $category->select(array('where'=>$where, 'row'=>0, 'order'=>$orderBy));
		$this->vars['category']['result'] = Category::makeSelectList($this->vars['category']['result'], 0);
		$this->view('category_edit');
	}
	/*
	 * api
	 */
	//插入分类
	function insert() {
		if(empty($_POST['name']))
			$this->message(-1, '请输入名称', 1);
		
		try {
			$category = Loader::load('model/Category');
			$res = $category->insert(array('name'=>$_POST['name'], 'description'=>$_POST['description'], 'parent_id'=>$_POST['parent_id']));
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
	//更新分类
	function update($id = 0) {
		if($_POST['name'])
			$data['name'] = $_POST['name'];
		if(isset($_POST['description']))
			$data['description'] = $_POST['description'];
		if(isset($_POST['parent_id']))
			$data['parent_id'] = $_POST['parent_id'];
		if($_POST['field'] && in_array($_POST['field'], array('name', 'description', 'parent_id'))) {
			$data[$_POST['field']] = $_POST['value'];
		}
		if(empty($data['name']))
			$this->message(-1, '请输入名称', 1);
		
		try {
			$category = Loader::load('model/Category');
			$res = $category->update(array('where'=>$id, 'limit'=>1, 'data'=>$data));
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
	//删除分类
	function delete($ids = 0) {
		if(empty($ids))
			$ids = $_POST['ids'];
		
		if(empty($ids))
			$this->message(-1, '没有修改的内容', 1);
		
		try {
			$category = Loader::load('model/Category');
			$res = $category->delete($ids);
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