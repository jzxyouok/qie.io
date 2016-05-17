<?php
/*
 * 文件管理
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/04/14
 *
 */
class UploadCtrl extends Controller {
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
		$file = Loader::load('model/File');
		$this->vars['data'] = $file->select(array('where'=>$where, 'now'=>$now, 'row'=>$row, 'order'=>$orderBy));
		$pagination = Loader::load('Pagination', array(array(
			'sum'=>$this->vars['data']['sum'],
			'row'=>$this->vars['data']['row'],
			'now'=>$this->vars['data']['now'],
			'uri'=>$this->profile['admin_dir'].'/index.php/upload/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->view('upload');
	}
	function add() {
		$this->view('upload_add');
	}
	/*
	 * api
	 */
	//上传所有类型文件
	function insert() {
		if(empty($_FILES['local_file']))
			$this->message(-1, '请选择文件', 1);
		try {
			$tag = Loader::load('model/File');
			$res = $tag->transfer($_FILES['local_file']);
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
	//上传图片
	function insert_image($t = 'local') {
		try {
			$file = NULL;
			$image = Loader::load('model/Image');
			//处理图片信息
			switch($t) {
				case 'flash': {
					//flash方式上传
					switch($ext) {
						case 'jpg': $_FILES['local_file']['type'] = 'image/jpeg';
						break;
						case 'png': $_FILES['local_file']['type'] = 'image/png';
						break;
						case 'gif': $_FILES['local_file']['type'] = 'image/gif';
						break;
						case 'bmp': $_FILES['local_file']['type'] = 'image/bmp';
						break;
						default:break;
					}
					$file = $_FILES['local_file'];
					unset($_FILES['local_file']);
				}
				break;
				case 'base64': {
					//base64方式上传
					$file = $_POST['local_file'];
				}
				break;
				case 'online': {
					//在线图片上传
					$file = $_POST['file_url'];
					if(!preg_match('/^(?:https?:)\/\/.+/i', $file))
						$this->message(-1,'','请提交一个正确的url地址');
				}
				break;
				default: {
					//默认本地上传
					$file = $_FILES['local_file'];
					unset($_FILES['local_file']);
				}
			}
			$res = $image->upload($file);
			
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
	//根据md5判断文件是否存在
	function file_exists($md5 = '') {
		if(empty($md5) || strlen($md5) != 32)
			$this->message(-1, '请输入md5', 1);
			
		try {
			$file = Loader::load('model/File');
			$res = $file->exists($md5);
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
			$tag = Loader::load('model/Tag');
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
			$tag = Loader::load('model/Tag');
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