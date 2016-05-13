<?php
/* 
 * 文件储存类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/02/18
 * 修改时间：2012/06/03
 *
CREATE TABLE IF NOT EXISTS `upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `md5` char(32) NOT NULL DEFAULT '',
  `property` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12273 ;
 *
 */
require_once(__LIB . '/config/Mime.php'); // 引入文件头定义文件
class UploadException extends Exception {}

class Upload extends Model {
	protected $name = ''; //文件名
	protected $size = 0; //文件大小
	protected $ext = ''; //文件扩展名
	protected $mime = ''; //文件mime
	protected $maxSize = 8388608; //最大文件大小 8M
	protected $dir = ''; //文件保存路径
	protected $timeout = 600; //上传超时时间
	protected static $safeExt = array('jpg','jpeg','gif','bmp','png','rar','zip','doc','docx','xls','xlsx','ppt','pptx','mp3','f4v','mp4','avi'); //安全类型的扩展名
	
	function __construct() {
		parent::__construct();
		$this->dir = __SPACE . '/file/' . date("Ymd");
	}
	/*
	 * 设置上传文件扩展名
	 * 
	 * @param string $e
	 *
	 */
	public function setExt($e) {
		$this->ext = $e;
	}
	/*
	 * 设置上传文件mime
	 * 
	 * @param string $e
	 *
	 */
	public function setMime($m) {
		$this->mime = $m;
	}
	/*
	 * 设置存储路径
	 * 
	 * @param string $d 路径入
	 *
	 * @return array
	 */
	public function setDir($d) {
		if(false !== strpos($d, __SPACE))
			$this->dir = $d;
		else
			return false;
	}
	/*
	 * 上传所有文件
	 * 为了加快速度，不做真实文件头判断
	 *
	 * @param object $file 需要上传的文件resource，可以是$_FILES，在线文件或者二进制流
	 *
	 * @return array
	 */
	final protected function handle($file = null) {
		if(empty($file))
			throw new UploadException('上传文件丢失');
		
		$id = 0; //文件id
		$path = ''; //保存路径
		$property = ''; //文件属性
		$md5 = ''; //文件字符串md5值
		$exists = false; //文件是否已经上传过
		$res = ''; //结果
		$width = 0; //图片宽度
		$height = 0; //图片高度
		$orientation = 0; //图片方向
		$img_array = array('jpg','jpeg','bmp','png','gif'); //对图片进行特殊处理
		$mysql = null; 
		$sql = '';
		
		//初始化一些信息
		if(is_string($file)) {
			$file = trim($file);
			$switch = (0 === strpos($file, 'http://') || 0 === strpos($file, 'https://') ? 1 : (false !== preg_match("/data:([a-z]+\/[a-z0-9.-]+);base64,/i", $file, $res)? 2 : 3));
		} else
			$switch = 0; //0:post文件类型,1:远程文件,2:base64,3:二进制流
		
		ini_set('max_execution_time', $this->timeout);
		switch($switch) {
			case 0: { //post方式
				if(!is_uploaded_file($file['tmp_name']))
					throw new UploadException('post上传方式错误');
				if($file['error'])
					throw new UploadException('上传时发生错误:'.$file['error']);
				//提取文件信息
				//$res = explode('.', $file['name']);
				//$this->ext = $res[1];
				//获取扩展名
				if(empty($this->ext) && false !== strrpos($file['name'], '.'))
					$this->ext = strtolower(substr($file['name'], strrpos($file['name'], '.')+1));
				//获取文件大小
				$this->size = $file['size'];
				//获取文件类型
				if(function_exists('finfo_open')) {
					$finfo = finfo_open(FILEINFO_MIME);
					$this->mime = strtolower(finfo_file($finfo, $file['tmp_name']));
					finfo_close($finfo);
					$this->mime = substr($this->mime, 0, strpos($this->mime, ';'));
					if(empty($this->mime))
						$this->mime = strtolower($file['type']);
				} else
					$this->mime = strtolower($file['type']);
			}
			break;
			case 1: { //在线文件
				$res = get_headers($file, true);
				//直接判断$res[0]
				if(empty($res)) {
					//改用curl方式获取
					$res = Utils::curlGetHeaders($file, true);
					if(empty($res))
						throw new UploadException('远程文件不存在');
				}
				//if(false === strpos($res[0], '200'))
				//	throw new UploadException('获取文件被禁止');
				$forbid = true;
				if(false !== strpos($res[0], '200'))
					$forbid = false;
				else {
					foreach($res as $v) {
						if(is_string($v) && false !== strpos($v, '200')) {
								$forbid = false;
								break;
							}
					}
				}
				if($forbid)
					throw new UploadException('被禁止获取远程文件');
				/* 获取的mime不一定正确 */
				if(is_array($res['Content-Type'])) {
					$this->mime = $res['Content-Type'][1];
				} else
					$this->mime = strtolower($res['Content-Type']);
				
				//提取文件信息
				//$res = explode('.', basename($file));
				//$this->ext = $res[1];
				if(empty($this->ext))
					$this->ext = strtolower(substr($file, strrpos($file, '.')+1)); //获取扩展名，有些在线资源可能没有扩展名
				$file_ = $file; //用curl获取的时候需要
				$file = file_get_contents($file);
				if(empty($file)) {
					//改用curl方式获取
					if(!($file = Utils::curlGetContents($file_)))
						throw new UploadException('读取远程文件失败');
				}
				unset($file_);
				$this->size = strlen($file); //获取文件大小
			}
			break;
			case 2: { //base64，需提前设置ext
				if(empty($this->ext))
					throw new UploadException('丢失文件扩展名');
				$this->mime = $res[1];
				$file = base64_decode(substr($file, strlen($res[0])), true);
				$this->size = strlen($file); //获取文件大小
			}
			break;
			case 3: { //二进制流
				if(empty($this->ext))
					throw new UploadException('丢失文件扩展名');
				else
					$this->ext = strtolower($this->ext);
				if(empty($this->mime))
					throw new UploadException('丢失文件类型');
				else
					$this->mime = strtolower($this->mime);
				//提取文件信息
				$this->size = strlen($file); //获取文件大小
			}
			break;
			default: return false;
		}
		//检查文件安全性
		$this->verify();
		//检查文件是否已经上传
		if($switch == 0) {
			if(is_readable($file['tmp_name']) && ($res = file_get_contents($file['tmp_name']))) {
				$md5 = md5($res);
				$res = NULL;
			} else
				throw new UploadException('读取临时文件失败');
		} else
			$md5 = md5($file);
		//查询文件是否已经存在
		$mysql = System::create('Mysql');
		$sql = "SELECT `id`,`path`,`property`,`status` FROM `upload` WHERE `md5`='{$md5}' LIMIT 1";
		$res = $mysql->query($sql);
		
		if(!empty($res[0]['path'])) {
			//文件被系统限制
			if($res[0]['status'] < 1)
				throw new UploadException('请不要上传非法文件');
			
			$id = $res[0]['id'];
			$path = $res[0]['path'];
			$property = $res[0]['property'];
			//获取文件的保存属性
			$res = pathinfo($path);
			$this->dir = $res['dirname'];
			$res = explode('.', $res['basename']);
			$this->name = $res[0];
			$this->ext = $res[1];
			//如果文件存在，直接返回
			if(in_array($this->ext, $img_array)) {
				//如果是图片，返回高度和宽度
				$res = explode('x', substr($this->name, strpos($this->name, '_')+1));
				$width = $res[0];
				$height = $res[1];
				$orientation = $res[2];
			}
			if(file_exists(__ROOT . $path)) {
				return array('id'=>$id, 'path'=>$path, 'property'=>$property, 'md5'=>$md5, 'width'=>$width, 'height'=>$height,'orientation'=>$orientation);
			}
		} else
			$path = $this->dir . '/' . $this->name . '.' . $this->ext;
			
		//如果文件不存在数据库
		if(!Utils::makeDir($this->dir))
			throw new UploadException('创建文件夹失败');
		
		switch($switch) {
			case 0: if(!move_uploaded_file($file['tmp_name'], __ROOT . $path))
						throw new UploadException('保存文件失败,error:upl');
			break;
			default: {
				if(!file_put_contents(__ROOT . $path, $file))
					throw new UploadException('保存文件失败,error:onl');
			}
			break;
		}
		unset($file);
		//如果是文件是图片格式，判断高宽度
		if(in_array($this->ext, $img_array) && (empty($width) || empty($height))) {
			if(function_exists('exif_read_data')) {
				$res = exif_read_data(__ROOT . $path);
				$width = $res['COMPUTED']['Width'];
				$height = $res['COMPUTED']['Height'];
				$orientation = (int)$res['Orientation']; //1:0°，6:顺时针90°，8:逆时针90°，3:180°
			}
			if(empty($width) || empty($height)) {
				$res = getimagesize(__ROOT . $path);
				$width = $res[0];
				$height = $res[1];
			}
			
			$this->name = "{$this->name}_{$width}x{$height}x{$orientation}";
			if(!rename(__ROOT . $path, __ROOT . $this->dir . '/' . $this->name . '.' . $this->ext))
				throw new UploadException('重命名文件失败');
			else
				$path = $this->dir . '/' . $this->name . '.' . $this->ext;
		}
		//保存数据库
		if(0 >= $id) {
			$res = $mysql->execute("INSERT INTO `upload` (`path`,`md5`) VALUES ('{$path}','{$md5}')");
			if($res)
				$id = $res;
		}
		return array('id'=>$id, 'path'=>$path, 'property'=>$property, 'md5'=>$md5, 'width'=>$width, 'height'=>$height,'orientation'=>$orientation, 'mime'=>$this->mime);
	}
	/*
	 * 删除文件
	 */
	final protected function handleDelete($ids) {
		if(is_array($ids)) {
			//去除非数字id
			while(list($k, $v) = each($ids)) {
				if(!is_numeric($v) || $v <= 0)
					unset($ids[$k]);	
			}
			$where = "`id` IN (".implode(',', $ids).")";	
		} else if(is_numeric($ids) && $ids > 0) {
			$where = "`id`=".$ids." LIMIT 1";	
		} else
			return false;
		
		$mysql = System::create('Mysql');
		$res = $mysql->query("SELECT `id`,`path` FROM `upload` WHERE {$where}");
		if(empty($res))
			return false;
		
		$ids = array();
		foreach($res as $v) {
			if(file_exists(__ROOT . $v['path']) && unlink(__ROOT . $v['path'])) {
				$ids[] = $v['id'];
			}
		}
		if(!empty($ids)) {
			$mysql->execute('DELETE FROM `upload` WHERE `id` IN ('.implode(',', $ids).')');	
		}
		
		return count($ids);
	}
	/*
	 * 修改属性
	 */
	final protected function handleUpdate ($data = '', $where, $table = '', $id_label = 'upload_id') {
		if(empty($where) || empty($table) || empty($id_label) || empty($data))
			return false;
		
		$tmp = '';
		
		if(!empty($data['property']))
			$tmp .= ",`property`='{$data['property']}'";
		if(isset($data['status']))
			$tmp .= ',`status`='.(int)$data['status'];
		if(!empty($tmp))
			$tmp = substr($tmp, 1);
		else
			return false;
		
		$mysql = System::create('mysql');
		$sql = "UPDATE `upload` SET {$tmp} WHERE `id` IN (SELECT `{$id_label}` FROM `{$table}` WHERE {$where})";
		
		return $mysql->execute($sql);
	}
	/*
	 * 设置文件属性,不支持重写
	 */
	final protected function setProperty($id, $property) {
		if(empty($id) || !is_numeric($id) || empty($property))
			return false;
		
		$property = addslashes($property);
		$mysql = System::create('Mysql');
		return $mysql->execute("UPDATE `upload` SET `property`='{$property}' WHERE `id`={$id} LIMIT 1");
	}
	/*
	 * 做安全检查，并且重命名文件
	 */
	private function verify() {
		/*如果新文件名为空，生成新文件名*/
		if(empty($this->name))
			$this->name = $_SERVER['REQUEST_TIME'] . rand(10, 99);
		//如果名字重复
		$counter = 0;
		while(file_exists(__ROOT . $this->dir . '/' . $this->name . '.' . $this->ext)) {
			$this->name = $this->name.$counter++;
			if($counter >= 10)
				break;
		}
		//判断文件大小
		if($this->size > $this->maxSize)
			throw new UploadException('文件超出大小');
		//判断文件扩展名
		$this->ext = strtolower($this->ext);
		if(!in_array($this->ext, self::$safeExt))
			throw new UploadException('不支持的文件类型');
		/* 判断文件类型 并修正类型(针对本地文件) */
		$ext = '';
		
		foreach(self::$safeExt as $k => $v) {
			if(is_array(Mime::$mimes[$v])) {
				if(in_array($this->mime, Mime::$mimes[$v])) {
					$ext = $v;
					break;	
				}
			} else {
				if($this->mime == Mime::$mimes[$v]) {
					$ext = $v;
					break;
				}
			}
		}
		
		if(empty($ext))
			throw new UploadException('不安全的文件类型');
		else
			$this->ext = $ext;
	}
}
