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
class ArticleCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = ($_GET['type'] == 'title'?'`title` LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'].'%"':'MATCH (`content`) AGAINST ("'.addslashes($_GET['word']).'" IN NATURAL LANGUAGE MODE)');
		}
		
		$orderBy = 'id_desc';
		if($_GET['orderby']) {
			$orderBy = $_GET['orderby'];
		}
		$orderBy = strtr($orderBy, '_', ' ');
		$article = Loader::load('model/Article');
		$this->vars['data'] = $article->select(array('where'=>$where, 'now'=>$now, 'row'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'sum'=>$this->vars['data']['sum'],
			'row'=>$this->vars['data']['row'],
			'now'=>$this->vars['data']['now'],
			'uri'=>$this->profile['admin_dir'].'/index.php/article/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->loadView('article');
	}
	function add() {
		$this->loadView('article_add');
	}
	function edit($id = 0) {
		$this->loadView('article_edit');
	}
	/*
	 * API
	 */
	function insert() {
		$article = Loader::load('model/Article');
		$data = array();
		if($_POST['title'])
			$data['title'] = $_POST['title'];
		if($_POST['content'])
			$data['content'] = $_POST['content'];
		$data['category_id'] = (int)$_POST['category_id'];
		if(empty($data['title']))
			$this->message(-1, '请输入文章标题', 1);
		if(empty($data['content']))
			$this->message(-1, '请输入正文内容', 2);
			
		$res = $article->insert($data);
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, $res);
		} else {
			$this->message(0, '操作失败');
		}
	}
	function update($id = 0) {
		$article = Loader::load('model/Article');
		$data = array();
		if($_POST['title'])
			$data['title'] = $_POST['title'];
		if($_POST['content'])
			$data['content'] = $_POST['content'];
		$data['category_id'] = (int)$_POST['category_id'];
			
		$res = $article->update(array('data'=>$data,'where'=>$id, 'limit'=>1));
		
		if(!empty($res['code'])) {
			$this->message(-1, $res['msg'], 10+$res['code']);
		} else if($res) {
			$this->message(1, $res);
		} else {
			$this->message(0, '操作失败');
		}
	}
}