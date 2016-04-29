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
	function index() {
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
		$article = Loader::load('Article');
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
		$article = Loader::load('Article');
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