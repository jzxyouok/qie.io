<?php
/*
 * 文件上传类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2016/04/29
 * 更新时间：2016/04/29
 * 
 */
 /*
  * database
CREATE TABLE `file` (
  `md5` char(32) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`md5`);
  */

class FileException extends Exception {}

class File extends Model {
	public $table = 'file';
	public $name = ''; //文件名
	public $extension = ''; //文件扩展名
	public $mime = ''; //文件mime
	public $size = 0; //文件大小
	public $mimes = array(); //允许的extension(key)和mime(value)
	public $maxSize = 5242880; //5M
	public $timeout = 600; //上传超时时间
	public $dir = '/user_files/upload/';
	
	/*
	 * 上传文件
	 * 为了加快速度，不做真实文件头判断
	 *
	 * @param object $file 需要上传的文件resource，可以是$_FILES，在线文件或者二进制流
	 *
	 * @return array
	 */
	public function transfer($file = NULL) {
		if(empty($file))
			return false;

		//加载mime
		if(empty($this->mimes))
			$this->mimes = Loader::loadVar(APP_PATH.'/config/mime.php', 'mime');
		//判断数据源类型 0:post文件类型;1:远程文件;2:base64;3:二进制流
		if(is_string($file)) {
			$file = trim($file);
			$switch = (0 === strpos($file, 'http://') || 0 === strpos($file, 'https://') ? 1 : (false !== preg_match("/data:([a-z]+\/[a-z0-9.-]+);base64,/i", $file, $res)? 2 : 3));
		} else
			$switch = 0;
		
		ini_set('max_execution_time', $this->timeout);
		
		switch($switch) {
			case 0: { //post方式
				if(!is_uploaded_file($file['tmp_name']))
					throw new FileException('File::transfer: post上传方式错误');
				if($file['error'])
					throw new FileException('File::transfer: 上传时发生错误 '.$file['error']);
				//获取文件mime
				if(function_exists('finfo_open')) {
					$finfo = finfo_open(FILEINFO_MIME);
					$this->mime = strtolower(finfo_file($finfo, $file['tmp_name']));
					finfo_close($finfo);
					$this->mime = substr($this->mime, 0, strpos($this->mime, ';'));
					if(empty($this->mime))
						$this->mime = strtolower($file['type']);
				} else
					$this->mime = strtolower($file['type']);
				//获取文件大小
				$this->size = $file['size'];
				//获取扩展名
				if(empty($this->extension) && ($res = strrpos($file['name'], '.')))
					$this->extension = strtolower(substr($file['name'], $res+1));
			}
			break;
			case 1: { //在线文件
				$res = get_headers($file, true);
				//直接判断$res[0]
				if(empty($res) && !($res = Util::curlGetHeaders($file, true))) {
					//改用curl方式获取
					throw new FileException('File::transfer: 远程文件不存在');
				}
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
					throw new FileException('File::transfer: 被禁止获取远程文件');
				//获取的mime不一定正确
				$this->mime = strtolower(is_array($res['Content-Type'])?$res['Content-Type'][1]:$res['Content-Type']);
				//获取扩展名，有些在线资源可能没有扩展名
				if(empty($this->extension) && ($res = strrpos($file, '.')))
					$this->extension = strtolower(substr($file, $res+1));
				$res = $file; //用curl获取的时候需要地址
				$file = file_get_contents($file);
				if(empty($file) && !($file = Util::curlGetContents($res))) {
					//改用curl方式获取
					throw new FileException('File::transfer: 读取远程文件失败');
				}
				$this->size = strlen($file); //获取文件大小
			}
			break;
			case 2: { //base64
				$this->mime = strtolower($res[1]);
				$file = base64_decode(substr($file, strlen($res[0])), true);
				$this->size = strlen($file); //获取文件大小
			}
			break;
			case 3: { //二进制流
				if(empty($this->mime))
					throw new FileException('File::transfer: 丢失文件类型');
				else
					$this->mime = strtolower($this->mime);
				//提取文件信息
				$this->size = strlen($file); //获取文件大小
			}
			break;
			default: return false;
		}
		
		//文件安全检查
		$this->verify();
		
		//获取文件md5
		if($switch == 0) {
			if(!is_readable($file['tmp_name']) || !($md5 = md5_file($file['tmp_name'])))
				throw new FileException('File::transfer: 读取临时文件失败');
		} else
			$md5 = md5($file);
		
		//检查文件状态（是否存在）
		$res = $this->exists($md5);
		//处理文件
		if(!$res || !$res['exists']) {
			// echo 'not exists '.$md5;
			$exists = false; //文件信息没在数据库
			//如果已经上传过，提取信息
			if(!empty($res['path'])) {
				$exists = true; //文件信息存在数据库
				$res = pathinfo($res['path']);
				$this->dir = $res['dirname'];
				$res = explode('.', $res['basename']);
				$this->name = $res[0];
				$this->extension = $res[1];
			}
			//如果文件不存在数据库
			if(!Util::makeDir($this->dir))
				throw new FileException('File::transfer: 创建文件夹失败');
			$path = $this->dir.'/'.$this->name.'.'.$this->extension;
			// 保存文件
			switch($switch) {
				case 0: {
							if(!($res = move_uploaded_file($file['tmp_name'], DOCUMENT_ROOT.$path)))
								throw new FileException('File::transfer: 保存文件失败case_0');
						}
						break;
				default: {
							if(!($res = file_put_contents(DOCUMENT_ROOT.$path, $file)))
								throw new FileException('File::transfer: 保存文件失败case_default');
						}
						break;
			}
			unset($file);
			if(!$exists) {
				if(in_array($this->extension, array('jpg','png','gif','bmp','jpeg'))) {
					//为图片判断高宽
					$orientation = 0;
					if(function_exists('exif_read_data')) {
						$res = exif_read_data(DOCUMENT_ROOT . $path);
						$width = $res['COMPUTED']['Width'];
						$height = $res['COMPUTED']['Height'];
						$orientation = (int)$res['Orientation']; //1:0°，6:顺时针90°，8:逆时针90°，3:180°
					}
					if(empty($width) || empty($height)) {
						$res = getimagesize(DOCUMENT_ROOT . $path);
						$width = $res[0];
						$height = $res[1];
					}
			
					$name = "{$this->name}_{$width}x{$height}x{$orientation}";
					if(rename(DOCUMENT_ROOT . $path, DOCUMENT_ROOT . $this->dir . '/' . $name . '.' . $this->extension)) {
						$this->name = $name;
						$path = $this->dir . '/' . $this->name . '.' . $this->extension;
					}
				}
				
				$db = Loader::load('Database');
				$sql = "INSERT INTO `{$this->table}` (`md5`,`path`) VALUES ('{$md5}','{$path}')";
				$res = $db->execute($sql);
			}
			
			if($res)
				return array('md5'=>$md5, 'path'=>$path);
			else
				return false;
		}
		// echo 'exists '.$md5;
		return array('md5'=>$md5, 'path'=>$res['path']);
	}
	/*
	 * 安全检查
	 */
	protected function verify() {
		//如果新文件名为空，生成新文件名
		if(empty($this->name))
			$this->name = $_SERVER['REQUEST_TIME'] . rand(10, 99);
		//如果名字重复
		$counter = 0;
		while(file_exists(DOCUMENT_ROOT.$this->dir . '/' . $this->name . '.' . $this->extension)) {
			$this->name = $this->name.$counter++;
			if($counter >= 100)
				throw new FileException('File::verify: 生成文件名失败');
		}
		
		//判断文件大小
		if($this->size > $this->maxSize)
			throw new FileException('File::verify: 文件大小超出范围');
		//如果存在文件扩展名
		if(!empty($this->extension) && empty($this->mimes[$this->extension]))
			throw new FileException('File::verify: 不支持的文件类型');
		//判断文件类型 并修正类型(针对本地文件)
		$extension = '';
		foreach($this->mimes as $k => $v) {
			if((is_array($v) && in_array($this->mime, $v)) || $this->mime == $v) {
				$extension = $k;
				break;
			}
		}
		if(empty($extension))
			throw new FileException('File::verify: 不安全的文件类型');
		//判断文件扩展名
		if(empty($this->extension))
			$this->extension = strtolower($extension);
	}
	/*
	 * 检查文件是否存在
	 *
	 * @param string $md5
	 *
	 * @return array;
	 *
	 */
	public function exists($md5 = '') {
		if(empty($md5))
			return false;

		$db = Loader::load('Database');
		$sql = "SELECT `path` FROM `{$this->table}` WHERE `md5`='{$md5}' LIMIT 1";
		$res = $db->query($sql);
		if(empty($res[0]['path']))
			return false;

		$res[0]['exists'] = file_exists(DOCUMENT_ROOT.$res[0]['path']);
		return $res[0];
	}
}
