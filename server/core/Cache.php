<?php
/* 
 * 缓存类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/06/25
 * 修改时间：2012/06/25
 */
class CacheException extends Exception {}

class Cache {
	private $expire = 60;
	private $isMemcache = false;
	private $memcache = null;
	private $store = null;
	private $memcacheFlag = 0;
	const PREFIX = 'c_';
	const DIR = DOCUMENT_ROOT.'/user_files/cache/';
	/*
	 * 
	 */
	function __construct($exp = 0, $flag = 0) {
		if(!empty($exp) && is_int($exp))
			$this->expire = $exp;
		//如果memcache存在，默认使用memcache
		if(class_exists('Memcache')) {
			$this->memcache = new Memcache();
			if($this->memcache->connect('localhost')) {
				$this->isMemcache = true;
				$this->memcacheFlag = $flag;
			} else
				$this->isMemcache = false;
		}
		
		Util::makeDir(self::DIR);
		$this->store = Loader::load('store');
	}
	/* 设置缓存过期时间
	 *
	 * @param int $exp 缓存过期时间
	 *
	 */
	 public function setExpire($exp = 0) {
		$this->expire = (int)$exp;
	}
	/* 使用memcache缓存
	 *
	 * @param int $use 是否使用memcache
	 * @param int $flag 是否使用压缩
	 *
	 */
	 public function useMemcache($use = true, $flag = 0) {
		 if($use && class_exists('Memcache')) {
			if(!$this->memcache) {
				$this->memcache = new Memcache();
			}
			
			if($this->memcache->connect('localhost')) {
				$this->isMemcache = true;
				$this->memcacheFlag = $flag;
			} else
				$this->isMemcache = false;
		 } else {
			 $this->memcache = null;
			 $this->isMemcache = false;
			 $this->memcacheFlag = $flag;
		 }
	}
	/*
	 * 设置缓存
	 * 
	 * @param string $key 缓存键值
	 * @param array/string $value 缓存内容
	 *
	 * @return boolean
	 */
	public function set($key, $value) {
		//$isNew = false;
		if(empty($value) || !($path = $this->getPath($key)))
			return false;
		
		if($this->isMemcache) {
			if(!is_string($value))
				$value = serialize($value);
			$res = $this->memcache->add($key, $value, $this->memcacheFlag, $this->expire);
		} else {
			$res = false;
			$counter = 0; //如果加锁6次失败，就不尝试了
			
			//写文件
			$data = array('create_time'=>$_SERVER['REQUEST_TIME'],'update_time'=>$_SERVER['REQUEST_TIME'],'expire'=>$this->expire,'value'=>$value);
			
			$res = $this->store->write($path, $data);
		}
		return $res;
	}
	/*
	 * 获取缓存内容
	 * 
	 * @param string $key 缓存键值
	 *
	 * @return string
	 */
	public function get($key) {
		if(!($path = $this->getPath($key)))
			return false;
			
		if($this->isMemcache) {
			return $this->memcache->get($key, $this->memcacheFlag);
		} else {
			$res = $this->store->read($path, $data);
			if(!empty($res)) {
				if((int)$res['create_time']+$this->expire < $_SERVER['REQUEST_TIME']) {
					//删除过期文件
					unlink($path);
					clearstatcache();
					return false;
				} else
					return $res['value'];
			} else
				return false;
		}
	}
	/*
	 * 更新缓存内容
	 * 
	 * @param string $key 缓存键值
	 *
	 * @return string
	 */
	public function update($key, $value) {
		//$isNew = false;
		if(!isset($value) || !($path = $this->getPath($key)))
			return false;
		
		if($this->isMemcache) {
			if(!is_string($value))
				$value = serialize($value);
			$res = $this->memcache->replace($key, $value, $this->memcacheFlag, $this->expire);
		} else {
			if($res = $this->store->read($path)) {
				$res['value'] = $value;
				$res['update_time'] = $_SERVER['REQUEST_TIME'];
				$res['expire'] = $this->expire;
			
				$res = $this->store->write($path, $res);
			}
		}
		
		if($res)
			return true;
		else
			return false;
	}
	/*
	 * 删除缓存
	 * 
	 * @param string $key 缓存键值
	 *
	 * @return boolean
	 */
	public function delete($key) {
		if(!($path = $this->getPath($key)))
			return false;
			
		if($this->isMemcache)
			return $this->memcache->delete($key);
		else {
			return $this->store->delete($path);
		}
	}
	/*
	 * 清理过期的缓存
	 *
	 * @return boolean
	 */
	public function clear() {
		$count = 0;
		if($handle = opendir(self::DIR)) {
			while(false !== ($res = readdir($handle))) {
				if($res === '.' || $res === '..' || is_dir(self::DIR . $res)) continue;
				
				$tmp = file_get_contents(self::DIR . $res);
				$tmp = explode("\r\n", $tmp);
				$tmp = unserialize($tmp[1]);
				if(((int)$tmp['create_time'] + (int)$tmp['expire'] < $_SERVER['REQUEST_TIME']) && unlink(self::DIR . $res)) {
					//删除过期缓存文件
					$count++;
				}
					
			}
			closedir($handle);
			unset($handle);
		}
		return $count;
	}
	/*
	 * 清理所有缓存
	 *
	 * @return boolean
	 */
	public function flush() {
		$count = 0;
		if($handle = opendir(self::DIR)) {
			while(false !== ($res = readdir($handle))) {
				if($res === '.' || $res === '..' || is_dir(self::DIR . $res)) continue;
				if(unlink(self::DIR . $res)) $count++;
			}
			closedir($handle);
			unset($handle);
		}
		return $count;
	}
	/*
	 * 获取缓存文件数量
	 *
	 * @return int
	 */
	public function sum() {
		$c = 0;
		if($handle = opendir(self::DIR)) {
			while(false !== ($res = readdir($handle))) {
				if($res === '.' || $res === '..' || is_dir(self::DIR . $res)) continue;
				$c++;
			}
			closedir($handle);
			unset($handle);
		}
		return $c;
	}
	/*
	 * 获取缓存文件路径
	 *
	 * @param string $key 缓存键
	 *
	 * @return string
	 */
	public function getPath($key) {
		if(empty($key) || !preg_match('/^[a-zA-Z0-9_$]+$/', $key))
			return false;
		if($this->isMemcache)
			return true;
			
		$key = self::PREFIX . strtolower($key);
		if(250 < strlen($key))
			$key = substr($key, 0, 250);
		$path = self::DIR . $key;
		return $path;
	}
	/*
	 * 返回缓存状态：memcache OR file
	 */
	public function getStatus() {
		return $this->isMemcache;	
	}
}
