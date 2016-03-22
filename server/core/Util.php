<?php
/*
 * 工具函数
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2010/10/01
 * 修改时间：2012/06/03
 */
class Util {
	/*
	 * 生成文件夹
	 * 
	 * @param string $path 文件夹路径
	 * @param float $mode 文件夹操作权限
	 * @return boolean
	 */
	public static function makeDir($dir, $mode = 0777) {
		if(false !== strpos($dir, DOCUMENT_ROOT))
			$dir = substr($dir, strlen(DOCUMENT_ROOT));
		if(is_dir(DOCUMENT_ROOT . $dir))
			return true;
		$arr = explode('/', $dir);
		$dir = '';
		foreach($arr as $k => $v) {
			if(!$v) continue;
			$dir .= DIRECTORY_SEPARATOR . $v;
			if(is_dir(DOCUMENT_ROOT . $dir))
				continue;
			else { //如果文件夹不存在
				if(!mkdir(DOCUMENT_ROOT . $dir, $mode)) return false;
				if(!chmod(DOCUMENT_ROOT . $dir, $mode)) return false;
			}
		}
		clearstatcache();
		return true;
	}
	/*
	 * 复制文件夹
	 * 
	 * @param string $old_dir 旧路径
	 * @param string $new_dir 新路径
	 * @param int $counter 计数器
	 * @param int $mode 文件夹操作权限
	 * @return boolean
	 */
	public static function copyDir($old_dir, $new_dir, $counter = 0, $mode = 0777) {
		if(empty($old_dir) || empty($new_dir) || !is_dir(DOCUMENT_ROOT . $old_dir) || !is_dir(DOCUMENT_ROOT . $new_dir))
			return 0;
		
		$handle = opendir(DOCUMENT_ROOT.$old_dir);
		
		while(false !== ($file = readdir($handle))) {
			if (0 === strpos($file, '.')) {
                continue;
            }
			if (is_dir(DOCUMENT_ROOT . $old_dir . $file)) {
                if(!is_dir(DOCUMENT_ROOT . $new_dir . $file))
					mkdir(DOCUMENT_ROOT . $new_dir . $file, $mode);
				$counter += self::copyDir($old_dir.$file.'/', $new_dir.$file.'/', $counter, $mode);
            } else {
                //if(file_exists(DOCUMENT_ROOT . $new_dir . $file))
				//	unlink(DOCUMENT_ROOT . $new_dir . $file);
				copy(DOCUMENT_ROOT . $old_dir . $file, DOCUMENT_ROOT . $new_dir . $file);
				$counter++;
            }
		}
		closedir($handle);
		return $counter;
	}
	/*
	 * 用iconv转换数组或者对象
	 * 
	 * @param string $old_charset 旧的字符编码
	 * @param string $new_charset 新的字符编码
	 * @param array/object $obj 需要转换的数组或者对象
	 *
	 * @return 
	 */
	public static function iconvPlus($old_charset, $new_charset, $obj) {
		if(is_array($obj)) {//转换数组
			foreach($obj as $k => $v) {
				if(is_array($v))
					$obj[$k] = self::iconvPlus($old_charset, $new_charset, $v);
				else
					$obj[$k] = iconv($old_charset, $new_charset, (string)$v);
			}
		} else
			$obj = iconv($old_charset, $new_charset, (string)$obj);
			
		return $obj;
	}
	/*
	 * 截取中英文字符串，2个英文=1个中文
	 *
	 * @param string $str 原始字符串
	 * @param int $start 截取开始位置，2/3个英文=1个中文
	 * @param int $len 截取长度，2/3个英文=1个中文
	 * @param string $charset 设置字符串编码
	 *
	 * @return string
	 */
	public static function truncate($str, $start = 0, $len = 20, $charset = 'utf-8') {
		$counter = 0; //截取长度计数器
		$str_arr = array();
		
		switch(strtolower($charset)) {
			//确定中文偏移量
			case 'gbk': $offset = 2;
			break;
			case 'utf-8': $offset = 3;
			break;
			default: $offset = 2;
		}
		$str_len = strlen($str);
		for($index = $start; $index < $str_len && $counter < 2*$len;) {
			if(ord($str{$index}) > 127) {
				$str_arr[] = substr($str, $index, $offset);
				$index += $offset;
				$counter += 2;
			} else {
				$str_arr[] = $str{$index};
				$index++;
				$counter++;
			}
    	}
		
		return implode('', $str_arr);
	}
	/*
	 * xml转数组
	 *
	 * @param object $xml 时间
	 *
	 * @return array
	 */ 
	public static function xmlToArray($xml) {
		$arr = array();
		$xml = (array)$xml;
	
		if($xml)
			foreach($xml as $k => $v) {
				if(is_object($v))
					$arr[$k] = self::xmlToArray($v);
				else if(is_array($v))
					$arr[$k] = self::xmlToArray($v);
				else
					$arr[$k] = $v;
			}
		else
			$arr = $xml;
	
		return $arr;
	}
	/*
	 * 获取客户端ip地址
	 *
	 * @return string
	 */ 
	public static function getIp() {
		$ip = NULL;
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_FORWARD_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		} else
			return false;
		$_ = explode(',', $ip);
		$ip = trim($_[0]);
		
		return $ip;
	}
	/*
	 * 获取当前运行页面路径
	 *
	 * @return string
	 */ 
	public static function selfUri() {
		return (stripos('_' . $_SERVER["SERVER_PROTOCOL"], 'HTTPS') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	/*
	 * 模拟post
	 * 
	 * @param string $url 请求地址
	 * @param array $params post文件的话，需要@文件路径$params = array('name' => "@")
	 *
	 * @return string
	 */
	public static function post($url = '', $params = array(), $header = false, $timeout = 30) {
		$multipart = false; //如果有文件需要上传
		if(is_array($params) && !empty($params)) {
			foreach($params as $v)
				if('@' == substr($v, 0, 1))
					$multipart = true;
		}
		if(is_array($params) && !$multipart)
			$params = http_build_query($params);
		$options = array(CURLOPT_POST			=> true,
						 CURLOPT_CUSTOMREQUEST => 'POST',
						 CURLOPT_POSTFIELDS		=> $params
						 );
		$res = self::curl($url, $options, $header, $timeout);
		
		return $res;
	}
	/*
	 * 模拟get
	 * 
	 * @param string $url 请求地址
	 *
	 * @return string
	 */
	public static function get($url = '', $header = false, $timeout = 30) {
		if(empty($url))
			return false;
		$options = array(CURLOPT_POST => false,	// post method
						 CURLOPT_CUSTOMREQUEST => 'GET'
						 );
		$res = self::curl($url, $options, $header, $timeout);
		return $res;
	}
	/*
	 * 模拟post
	 * 
	 * @param string $url 请求地址
	 * @param array $params post文件的话，需要@文件路径$params = array('name' => "@")
	 *
	 * @return string
	 */
	public static function curl($url = '', $options = array(), $header = 0, $timeout = 30) {
		if(empty($url))
			return false;
		if(!function_exists('curl_init'))
			return false;
			
		$ch = curl_init($url);
		
		$options[CURLOPT_HEADER] = $header; // 是否返回网页头
		$options[CURLOPT_RETURNTRANSFER] = 1; // 返回网页内容,不直接显示
		$options[CURLOPT_NOBODY] = 0;
		$options[CURLOPT_ENCODING] = 'UTF-8'; // 编码
		$options[CURLOPT_USERAGENT] = $_SERVER['HTTP_USER_AGENT']; // 浏览设备
		$options[CURLOPT_REFERER] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // 引用页面
		$options[CURLOPT_CONNECTTIMEOUT] = 60; // 连接超时时间
		$options[CURLOPT_TIMEOUT] = $timeout; // 执行超时时间
		$options[CURLOPT_FOLLOWLOCATION] = 0; // 如果跳转，获取跳转后页面
		//$options[CURLOPT_MAXREDIRS] = 2; // 如果跳转，最大跳转次数
		$options[CURLOPT_FORBID_REUSE] = true; // 处理完后，关闭连接，释放
		curl_setopt_array($ch, $options);
		
		$res = curl_exec($ch);
		if(curl_error($ch)) {
			$res = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			unset($ch);
			return $res;
		}
		curl_close($ch);
		unset($ch);
		if($header)
			$res = preg_split('/\r\n\r\n|\n\n|\r\r/', $res, 2);
		
		return $res;
	}
	/*
	 * 用curl获取远程文件内容，
	 * 
	 * @param string $url 请求地址
	 * @param int $timeout 连接超时时间
	 *
	 * @return mix
	 */
	public static function curlGetContents($url, $timeout = 5){
		if(empty($url))
			return false;
		$ch = curl_init($url);
		
		$options[CURLOPT_TIMEOUT] = $timeout;
		$options[CURLOPT_HEADER] = 0; // 是否返回网页头
		$options[CURLOPT_NOBODY] = 0; // 返回网页内容
		$options[CURLOPT_RETURNTRANSFER] = 1;
		$options[CURLOPT_ENCODING] = 'UTF-8';
		$options[CURLOPT_USERAGENT] = $_SERVER['HTTP_USER_AGENT']; // 浏览设备
		$options[CURLOPT_REFERER] = _REFERER_;
		$options[CURLOPT_FORBID_REUSE] = true; // 处理完后，关闭连接，释放
		curl_setopt_array($ch, $options);
		
		$res = curl_exec($ch);
		if(curl_error($ch)) {
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		
		return $res;
	}
	/*
	 * 用curl获取远程文件头信息
	 * 
	 * @param string $url 请求地址
	 * @param int $timeout 连接超时时间
	 *
	 * @return mix
	 */
	public static function curlGetHeaders($url, $format = false, $timeout = 5){
		if(empty($url))
			return false;
		
		$ch = curl_init($url);
		$options[CURLOPT_HEADER] = 1; // 是否返回网页头
		$options[CURLOPT_NOBODY] = 1; // 返回网页内容
		$options[CURLOPT_RETURNTRANSFER] = 1; // 返回网页内容
		$options[CURLOPT_ENCODING] = 'UTF-8'; // 编码
		$options[CURLOPT_TIMEOUT] = $timeout; // 执行超时时间
		$options[CURLOPT_FORBID_REUSE] = true; // 处理完后，关闭连接，释放
		curl_setopt_array($ch, $options);
		
		$res = curl_exec($ch);
		if(curl_error($ch)) {
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		
		$res = preg_split('/\r\n\r\n|\n\n|\r\r/', $res, 2);
		$res = explode("\n", $res[0]);
		if($format) {
			$res_ = array();
			while(list($k, $v) = each($res)) {
				if(false !== strpos($v, 'HTTP/'))
					$res_[0] = $v;
				else {
					$t = explode(':', $v);
					$res_[$t[0]] = trim($t[1]);
				}
			}
			$res = $res_;
		}
		
		return $res;
	}
	/*
	 * 过滤危险标签
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	public static function noScript($str = '') {
		if(is_numeric($str))
			return $str;
		$str = trim($str);
		$search = array("/\s+/",
					"/<(\/?)(php|script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU", //过滤 <script 等可能引入恶意内容或恶意改变显示布局的代码,如果不需要插入flash等,还可以加入<object的过滤 
					"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU", //过滤javascript的on事件
					);
		$replace = array(" ",
					 "&lt;\\1\\2\\3&gt;",           //如果要直接清除不安全的标签，这里可以留空 
					 "\\1\\2",
					 );
		$str = preg_replace($search, $replace, $str); 
		return $str;	
	}
	/*
	 * 对字符串进行分词。中文字符需要传一个词组的长度，如2个字，英文字符按照空格和标点进行分词
	 *
	 * @param string $t 需要分词的字符串
	 * @param int $min 中文词组最少字数
	 * @param int $max 中文词组最长字数
	 */
	public static function splitWord($t, $min = 2, $max = 3) {
		$words = array();
		$chn = array();
		$en = array();
		$len = strlen($t);
		if($min < 1 || $min >= $len)
			$min = 1;
		if($max >= $len || $max < $min)
			$max = 2;
		//过滤html标签
		$t = preg_replace('/<[a-zA-Z0-9]+[^>]*>|<\/[a-zA-Z0-9]+>/', '', $t);
		//匹配中文
		preg_match_all(RegExp::CHNWORD, $t, $chn);
		if(!empty($chn[0])) {
			$t = preg_replace(RegExp::CHNWORD, ' ', $t);
			$len = count($chn[0]);
			foreach($chn[0] as $k => $v) {
				for($i = $min; $i <= $max; $i++) {
					if(($k + $i) <= $len) {
						$r = array();
						$r = array_slice($chn[0], $k, $i);
						$r = implode('', $r);
						$m = md5($r);
						if(empty($words[$m]))
							$words[$m] = $r;
					}
				}
			}
		}
		//匹配英文
		$t = trim(preg_replace('/[^a-zA-Z0-9]+/', ' ', $t));
		if(!empty($t)) {
			$en = explode(' ', $t);
			//合并中英文
			foreach($en as $v) {
				$m = md5($v);
				if(empty($words[$m]))
					$words[$m] = $v;
			}
		}
		
		return $words;
	}
	/*
	 * 获取关键词
	 *
	 * @param string $tl 标题
	 * @param string $con 正文
	 * @param int $len 获取关键词长度，0为全部返回
	 * @param int $min 中文词组最少字数
	 * @param int $max 中文词组最长字数
	 * @param bool $flag 是否检查正文里的词组
	 *
	 * @return array
	 */
	public static function keyword($tl, $con, $len = 0, $min = 2, $max = 3, $flag = true) {
		if(empty($tl) || empty($con))
			return false;
		
		$keywords = array(); //关键词
		$tWord = array(); //title里面包含的词
		$cWord = array(); //content里面包含的词
		$fCon = ''; //过滤html标签后的content内容
		$hCon = null; //h标签的内容
		$sortKey = array(); //需要排序的key
		$counter = 0; //计数器
		
		//对title进行分词
		$tWord = self::splitWord($tl, $min, $max);
		//过滤script/css标签
		$fCon = preg_replace('/<(script|style)[^>]*>.*<\/\\1>/u', '', $con);
		//过滤带有title的标签，保留title内容
		$fCon = preg_replace('/<([a-zA-Z0-9])\s.+title=([\'"]?)([^\\2]*)\\2[^>]*>(.*)<\/\\1>/u', '\\3\\4', $fCon);
		//过滤除h以外的html标签
		$fCon = preg_replace('/<[a-zA-Z]+[^>0-9]*>|<\/[a-zA-Z]+>/', '', $fCon);
		//获取带有h标记的标签
		if(preg_match_all('/<h([1-9])>(.*)<\/h\\1>/u', $fCon, $hCon))
			$hCon = implode('', $hCon[2]);
		//获取content里面可能的关键词
		if($flag)
			$cWord = self::keyword($fCon, $con, 100, $min, $max, false);
		foreach($tWord as $k => $v) {
			$counter = substr_count($fCon, $v);
			//h标签里的添加权重
			if($hCon)
				$counter += substr_count($hCon, $v);
			//对content里经常重复出现的内容添加权重
			if($cWord) {
				$m = md5($v);
				if(!empty($cWord[$m]))
					$counter += $cWord[$m]['counter'];
			}
			$sortKey[$counter]['word'][] = $v;
			if(is_null($sortKey[$counter]['counter']))
				$sortKey[$counter]['counter'] = $counter;
		}
		//按照频率进行排序
		krsort($sortKey, SORT_NUMERIC);
		foreach($sortKey as $v) {
			if($len === 0) 
				$keywords[] = $v;
			else {
				foreach($v['word'] as $vv) {
					if($len < $counter++)
						break;
					$m = md5($vv);
					$keywords[$m] = array('word'=>$vv, 'counter'=>$v['counter']);
				}
			}	
		}
	
		return $keywords;
	}
	/*
	 * 生成随机字符串
	 *
	 * @param int $len 字符串长度
	 * @param string $char 随机字符字典
	 */
	public static function randCode($len = 4, $char = '') {
		$code = '';
		if(empty($char))
			$char = '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ'; //没有字母o。
		if(is_array($char)) {
			//可以支持中文
			shuffle($char);
			$char = array_slice($char, 0, $len);
			$code = implode('', $char);
		} else {
			$_len = strlen($char);
			for($i = 0; $i < $len; $i++)
				$code .= $char{mt_rand(0,$_len-1)};
		}
		return $code;
	}
	/*
	 * 按数组抽奖
	 *
	 * @param array $data 带数值的数组,array(0=>1,2=>99)，key为奖品类型
	 * @return int/string 数组key
	 */
	public static function lottery($data = array()) {
		if(empty($data) || !is_array($data))
			return false;
		$lot = false;
		
    	//概率数组的总概率精度 
    	$sum = array_sum($data);
    	//概率数组循环 
    	foreach ($data as $key => $val) {
			$rand_num = mt_rand(1, $sum);
			if($rand_num <= $val) {
				$lot = $key;
				break;
			} else {
				$sum -= $val;
			}
		}
		unset($data);
		
		return $lot;
	}
	/*
	 * string 转数字
	 */
	public static function toNumeric($num = '') {
		if(empty($num) || !is_numeric($num))
			return 0;
		else if(abs($num) > PHP_INT_MAX)
			return (float)$num;
		else
			return (int)$num;
	}
}

