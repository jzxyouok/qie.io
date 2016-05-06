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
	
	/*
	 * page
	 */
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = ($_GET['type'] == 'title'?'`title` LIKE "'.($_GET['fuzzy']?'%':'').$_GET['word'].'%"':'MATCH (`content`) AGAINST ("'.addslashes($_GET['word']).'" IN NATURAL LANGUAGE MODE)');
		}
		if($_GET['tag_id']) {
			$where = '`tag_id`='.(int)$_GET['tag_id'];
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
	//添加文章
	function add() {
		$category = Loader::load('model/Category');
		$this->vars['category'] = $category->select(array('row'=>0, 'order'=>'`root_id` ASC'));
		$this->vars['category']['result'] = Category::makeSelectList($this->vars['category']['result'], 0);
		$this->loadView('article_add');
	}
	//修改文章
	function edit($id = 0) {
		$article = Loader::load('model/Article');
		$this->vars['data'] = $article->selectOne($id);
		$category = Loader::load('model/Category');
		$this->vars['category'] = $category->select(array('row'=>0, 'order'=>'`depth` ASC'));
		$this->vars['category']['result'] = Category::makeSelectList($this->vars['category']['result'], 0);
		$this->loadView('article_edit');
	}
	/*
	 * api
	 */
	//插入文章
	function insert() {
		$data = array();
		$data['title'] = $_POST['title'];
		$data['content'] = $_POST['content'];
		$data['category_id'] = (int)$_POST['category_id'];
		$data['keywords'] = $_POST['keywords'];
		$data['excerpt'] = $_POST['excerpt'];
		$data['author'] = $_POST['author'];
		$data['from'] = $_POST['from'];
		$data['href'] = $_POST['href'];
		$data['cover'] = $_POST['cover'];
		
		if(empty($data['title']))
			$this->message(-1, '请输入文章标题', 1);
		if(empty($data['content']))
			$this->message(-1, '请输入正文内容', 2);
		
		try {
			$article = Loader::load('model/Article');
			$res = $article->insert($data);
		
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
	//更新文章
	function update($id = 0) {
		$data = array();
		if($_POST['title'])
			$data['title'] = $_POST['title'];
		if($_POST['content'])
			$data['content'] = $_POST['content'];
		if(isset($_POST['category_id']))
			$data['category_id'] = (int)$_POST['category_id'];
		if(isset($_POST['keywords']))
			$data['keywords'] = $_POST['keywords'];
		if(isset($_POST['excerpt']))
			$data['excerpt'] = $_POST['excerpt'];
		if(isset($_POST['author']))
			$data['author'] = $_POST['author'];
		if(isset($_POST['from']))
			$data['from'] = $_POST['from'];
		if(isset($_POST['href']))
			$data['href'] = $_POST['href'];
		if(isset($_POST['cover']))
			$data['cover'] = $_POST['cover'];
		if(isset($_POST['counter']))
			$data['counter'] = $_POST['counter'];
		if(isset($_POST['order']))
			$data['order'] = $_POST['order'];
		if($_POST['field'] && in_array($_POST['field'], array('title', 'keywords', 'excerpt', 'author', 'from', 'href', 'cover', 'order', 'counter'))) {
			$data[$_POST['field']] = $_POST['value'];
		}
		
		try {
			$article = Loader::load('model/Article');	
			$res = $article->update(array('data'=>$data,'where'=>$id, 'limit'=>1));
		
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
	//删除文章
	function delete($ids = 0) {
		if(empty($ids))
			$ids = $_POST['ids'];
		
		if(empty($ids))
			$this->message(-1, '没有修改的内容', 1);
		
		try {
			$article = Loader::load('model/Article');
			$res = $article->delete($ids);
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
	//修复文章tag
	function fix_tag($id = 0) {
		$words = urldecode($this->segments[$this->paramPos+1]);
		if(empty($id) || empty($words))
			$this->message(-1, '没有修改的内容', 1);
		
		try {
			$article = Loader::load('model/Article');
			$res = $article->fixTag($words,$id);
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