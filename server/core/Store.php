<?php
/* 
 * 服务端数据存储类
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2012/06/25
 * 修改时间：2012/06/25
 */

class StoreException extends Exception {}

class Store {
	const EXP = '.php';
	const MAX_SIZE = 10485760; //文件最大不能超过10M
	
	/*
	 * 写入文件
	 * 
	 * @param string $path 存储路径，不需要文件后缀
	 * @param array/string $value 存储内容
	 *
	 * @return boolean
	 */
	public function write($path, $value) {
		//$isNew = false;
		if(empty($path) || (empty($value) || (!is_string($value) && !is_array($value))))
			return false;
		$res = false;
		$counter = 0; //如果加锁6次失败，就不尝试了
		
		$value = serialize($value);
		if(self::MAX_SIZE < strlen($value))
			return false;
		//写文件
		$path = ((false === strpos($path, DOCUMENT_ROOT) && strpos($path, DIRECTORY_SEPARATOR) === 0) ? DOCUMENT_ROOT . $path : $path) . self::EXP;
		if(!$fp = fopen($path, "w+"))
			throw new StoreException('Store::write: 打开文件失败');
		$value = "<?php\r\n".$value;
		do {
			$counter++;
			if(!flock($fp, LOCK_EX|LOCK_NB)) {
				usleep(1000);
				continue;
			}
			$res = fwrite($fp, $value);
			flock($fp, LOCK_UN);
			break;
			/* 测试
			sleep(5);
			echo flock($fp, LOCK_SH);*/
		} while($counter < 6);
		fclose($fp);
		unset($fp);
		//if($isNew)
		//	touch($path, time());
		return $res;
	}
	/*
	 * 获取缓存内容
	 * 
	 * @param string $path 文件路径，不需要文件后缀
	 *
	 * @return string
	 */
	public function read($path) {
		if(empty($path))
			return false;
		
		$path = ((false === strpos($path, DOCUMENT_ROOT) && strpos($path, DIRECTORY_SEPARATOR) === 0) ? DOCUMENT_ROOT . $path : $path) . self::EXP;
		if(!file_exists($path))
			return false;
		$res = file_get_contents($path);
		if(!empty($res)) {
			$res = explode("\r\n", $res);
			$res = unserialize($res[1]);
		}
		return $res;
	}
	/*
	 * 删除文件
	 * 
	 * @param string $path 文件路径，不需要文件后缀
	 *
	 * @return boolean
	 */
	public function delete($path) {
		if(empty($path))
			return false;
			
		$path = ((false === strpos($path, DOCUMENT_ROOT) && strpos($path, DIRECTORY_SEPARATOR) === 0) ? DOCUMENT_ROOT . $path : $path) . self::EXP;
		if(file_exists($path))
			return unlink($path);
		else
			return false;
	}
}
