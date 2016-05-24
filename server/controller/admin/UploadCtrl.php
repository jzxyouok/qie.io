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
		
		$file = Loader::load('model/File');
		$this->vars['data'] = $file->select(array('where'=>$where, 'current'=>$now, 'size'=>$row));
		$pagination = Loader::load('Pagination', array(array(
			'total'=>$this->vars['data']['total'],
			'size'=>$this->vars['data']['size'],
			'current'=>$this->vars['data']['current'],
			'uri'=>$this->profile['admin_dir'].'/index.php/upload/'
		)));
		$this->vars['pagination'] = $pagination->get();
		
		$this->view('upload');
	}
	//图片上传页
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
	//上传图片，带缩略图
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
				// sleep(5);
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
		if(empty($md5))
			$this->message(-1, '请输入md5', 1);
			
		try {
			$file = Loader::load('model/File');
			$res = $file->exists($md5);
			if(!empty($res['code'])) {
				$this->message(-1, $res['msg'], 10+$res['code']);
			} else if($res) {
				if($res['path']) {
					$extension = substr($res['path'], strrpos($res['path'], '.')+1);
					if(in_array($extension, array('jpg', 'png', 'gif', 'bmp', 'jpeg')))
						$res['thumb'] = file_exists(DOCUMENT_ROOT.$res['path'].'.'.$extension);
				}
				
				$this->message(1, $res);
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//删除
	function delete($md5 = '') {
		if(empty($md5))
			$md5 = $_POST['md5'];
		if(empty($md5))
			$this->message(-1, '请输入md5', 1);
		
		$md5 = explode(',', $md5);
		$counter = 0;
		
		try {
			$file = Loader::load('model/File');
			$image = Loader::load('model/Image');
			foreach($md5 as $v) {
				$res = $file->delete($v);
				if($res['path']) {
					$counter++;
					//删除缩略图
					$extension = substr($res['path'], strrpos($res['path'], '.')+1);
					if(in_array($extension, array('jpg', 'png', 'gif', 'bmp', 'jpeg')))
						unlink(DOCUMENT_ROOT.$res['path'].'.'.$extension);
					$image->delete($v);
				}
				//一次最多只能删100个文件
				if($counter>99)
					break;
			}
			if($counter) {
				$this->message(1, $counter);
			} else {
				$this->message(0, '操作失败');
			}
		} catch(Exception $e) {
			$this->message(-1, $e->getMessage(), $e->getCode());
		}
	}
	//image list 
	function image_list($now = 1) {
		$row = (int)$_GET['row'] or $row = 20;
		
		$file = Loader::load('model/Image');
		$res = $file->select(array('now'=>$now, 'row'=>$row));
		$this->message(1, $res);
	}
}