<?php
/*
 * 标签管理
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class TagCtrl extends Controller {
	protected $autoload = array('this'=>'hasAdminLogin');
	
	/*
	 * page
	 */
	//首页
	function index($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$where = '';
		if($_GET['word']) {
			$where = '`word` LIKE "'.$_GET['word'].'%"';
		}
		
		$orderBy = 'id_desc';
		if($_GET['orderby']) {
			$orderBy = $_GET['orderby'];
		}
		$orderBy = strtr($orderBy, '_', ' ');
		$tag = Loader::load('Tag');
		$this->vars['data'] = $tag->select(array('where'=>$where, 'current'=>$now, 'size'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'total'=>$this->vars['data']['total'],
			'size'=>$this->vars['data']['size'],
			'current'=>$this->vars['data']['current'],
			'uri'=>$this->profile['admin_dir'].'/index.php/tag/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->display('tag');
	}
	/*
	 * api
	 */
	//删除
	function insert() {
		if(empty($_POST['words']))
			$this->message(-1, '请输入内容', 1);
		try {
			$tag = Loader::load('Tag');
			$res = $tag->insert(array('words'=>$_POST['words'],'format'=>true));
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
	//删除
	function delete($ids = 0) {
		if(empty($ids))
			$ids = $_POST['ids'];
		
		if(empty($ids))
			$this->message(-1, '没有修改的内容', 1);
		try {
			$tag = Loader::load('Tag');
			$res = $tag->delete($ids);
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
	//清理
	function clean($table='article') {
		if(empty($table))
			$this->message(-1, '没有修改的内容', 1);
		try {
			$tag = Loader::load('Tag');
			$res = $tag->clean($table);
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