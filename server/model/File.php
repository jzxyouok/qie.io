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
	public $dir = DOCUMENT_ROOT.'/user_files/upload/';
	
	/*
	 * 上传所有文件
	 * 为了加快速度，不做真实文件头判断
	 *
	 * @param object $file 需要上传的文件resource，可以是$_FILES，在线文件或者二进制流
	 *
	 * @return array
	 */
	public function transfer($file = null, $md5 = '') {
		//加载mime
		if(empty($this->mimes))
			$this->mimes = Loader::loadVar(APP_PATH.'/config/mime.php', 'mime');
		
		if(is_string($file)) {
			$file = trim($file);
			$switch = (0 === strpos($file, 'http://') || 0 === strpos($file, 'https://') ? 1 : (false !== preg_match("/data:([a-z]+\/[a-z0-9.-]+);base64,/i", $file, $res)? 2 : 3));
		} else
			$switch = 0; //0:post文件类型,1:远程文件,2:base64,3:二进制流
		
		ini_set('max_execution_time', $this->timeout);
		switch($switch) {
			case 0: { //post方式
				if(!is_uploaded_file($file['tmp_name']))
					throw new FileException('post上传方式错误');
				if($file['error'])
					throw new FileException('上传时发生错误:'.$file['error']);
				//获取扩展名
				if(empty($this->extension) && ($res = strrpos($file['name'], '.')))
					$this->extension = strtolower(substr($file['name'], $res+1));
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
						throw new FileException('远程文件不存在');
				}
				//if(false === strpos($res[0], '200'))
				//	throw new FileException('获取文件被禁止');
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
					throw new FileException('被禁止获取远程文件');
				/* 获取的mime不一定正确 */
				if(is_array($res['Content-Type'])) {
					$this->mime = $res['Content-Type'][1];
				} else
					$this->mime = strtolower($res['Content-Type']);
				
				//提取文件信息
				//$res = explode('.', basename($file));
				//$this->extension = $res[1];
				if(empty($this->extension))
					$this->extension = strtolower(substr($file, strrpos($file, '.')+1)); //获取扩展名，有些在线资源可能没有扩展名
				$file_ = $file; //用curl获取的时候需要
				$file = file_get_contents($file);
				if(empty($file)) {
					//改用curl方式获取
					if(!($file = Utils::curlGetContents($file_)))
						throw new FileException('读取远程文件失败');
				}
				unset($file_);
				$this->size = strlen($file); //获取文件大小
			}
			break;
			case 2: { //base64，需提前设置ext
				if(empty($this->extension))
					throw new FileException('丢失文件扩展名');
				$this->mime = $res[1];
				$file = base64_decode(substr($file, strlen($res[0])), true);
				$this->size = strlen($file); //获取文件大小
			}
			break;
			case 3: { //二进制流
				if(empty($this->extension))
					throw new FileException('丢失文件扩展名');
				else
					$this->extension = strtolower($this->extension);
				if(empty($this->mime))
					throw new FileException('丢失文件类型');
				else
					$this->mime = strtolower($this->mime);
				//提取文件信息
				$this->size = strlen($file); //获取文件大小
			}
			break;
			default: return false;
		}
		//文件检查
		$this->verify();
		//获取文件md5
		if(empty($md5)) {
			if($switch == 0) {
			if(is_readable($file['tmp_name']) && ($res = file_get_contents($file['tmp_name']))) {
				$md5 = md5($res);
				$res = NULL;
			} else
				throw new FileException('读取临时文件失败');
		} else
			$md5 = md5($file);
		}
		
		$db = Loader::load('Database');
		$sql = "SELECT `path` FROM `{$this->table}` WHERE `md5`='{$md5}' LIMIT 1";
		$res = $db->query($sql);
		if(!empty($res[0]['path']) && file_exists(DOCUMENT_ROOT.$res[0]['path'])) {
			return $res[0];
		} else {
			//如果已经上传过，提取信息
			if(!empty($res[0]['path'])) {
				$res = pathinfo($res[0]['path']);
				$this->dir = $res['dirname'];
				$res = explode('.', $res['basename']);
				$this->name = $res[0];
				$this->extension = $res[1];
			}
			
		}
	}
	/*
	 * 做安全检查，并且重命名文件
	 */
	private function verify() {
		//如果新文件名为空，生成新文件名
		if(empty($this->name))
			$this->name = $_SERVER['REQUEST_TIME'] . rand(10, 99);
		//如果名字重复
		$counter = 0;
		while(file_exists($this->dir . '/' . $this->name . '.' . $this->extension)) {
			$this->name = $this->name.$counter++;
			if($counter >= 100)
				throw new FileException('File::verify: 生成文件名失败');
		}
		//判断文件大小
		if($this->size > $this->maxSize)
			throw new FileException('File::verify: 文件大小超出范围');
		//判断文件扩展名
		$this->extension = strtolower($this->extension);
		if(empty($this->extension) || empty($this->mimes[$this->extension]))
			throw new FileException('File::verify: 不支持的文件类型');
		//判断文件类型 并修正类型(针对本地文件)
		$ext = '';
		foreach($this->mimes as $k => $v) {
			if(is_array($v)) {
				if(in_array($this->mime, $v)) {
					$ext = $k;
					break;
				}
			} else {
				if($this->mime == $v) {
					$ext = $k;
					break;
				}
			}
		}
		
		if(empty($ext))
			throw new FileException('File::verify: 不安全的文件类型');
		else
			$this->extension = $ext;
	}
}
