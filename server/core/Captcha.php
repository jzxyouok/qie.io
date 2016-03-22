<?php
/* 
 * 验证码类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/06/25
 * 修改时间：2012/06/25
 */
ob_start();

class CaptchaException extends Exception {}

class Captcha {
	private $expire = 360;
	/*
	 * 
	 */
	function __construct($exp = 0) {
		if(!empty($exp) && is_int($exp) && $exp > 0)
			$this->expire = $exp;
	}
	/*
	 * 验证是否一致
	 *
	 * @param string $code 验证码
	 *
	 * @return boolean
	 */
	public function verify($code) {
		$c = $_COOKIE['c_code'];
		if(empty($c))
			return false;
			
		$cache = Loader::load('Cache', array($this->expire));
		$cache->setExpire($this->expire);
		$code_ = $cache->get($c);
		if(empty($code_))
			return false;
		if($code_ === strtolower($code)) {
			$cache->delete($c);
			return true;
		} else
			return false;
	}
	/*
	 * 创建验证码图片
	 *
	 * @return string
	 */
	public function createImage() {
		if(!function_exists("imagecreate"))
			throw new CaptchaException('GD库不支持');
		//验证码句柄
		$c = ip2long(Util::getIp());
		$c = $c.$_SERVER['REQUEST_TIME'].rand(10,99);
		/*验证码内容
		$code = '';
		for ($i=0; $i<2; $i++)
			$code .= chr(mt_rand(48,57));
		for ($i=0; $i<2; $i++)
			$code .= chr(mt_rand(65,90));
		$strs = str_split($code);
		shuffle($strs);
		$code = '';
		while(list(, $str) = each($strs))
			$code .= $str;*/
		$code = Util::randCode(4, '1234567890qwertyuipasdfghjklzxcvbnm'); //去掉O
		//把验证码写入cookie和缓存
		setcookie('c_code', $c, time()+360, '/');
		$cache = Loader::load('Cache');
		$cache->setExpire($this->expire);
		$cache->set($c, strtolower($code));
		//生成图片
		$im = imagecreate(80, 20);
		$font_type = __ROOT . '/_static/font/verdanab.ttf';
		//背景颜色
		$backcolor = imagecolorallocate($im,190, 190, 120);
		//杂点背景线
		$line_color1 = imagecolorallocate($im, 130, 220, 245);
		$line_color2 = imagecolorallocate($im, 225, 245, 255);
		for($j=3; $j<=16; $j=$j+3)
			imageline($im, 2, $j, 83, $j, $line_color1);
		for($j=2; $j<83; $j=$j+(mt_rand(3, 10)))
			imageline($im, $j, 2, $j-6, 18, $line_color2);
		//文字
		$font_color = imagecolorallocate($im, 0, 0, 0);
		if(function_exists('imagettftext') && file_exists($font_type))
			imagettftext($im, 12, 0, 16, 16, $font_color, $font_type, $code);
		else
			imagestring($im, 5, 20, 2, $code, $font_color);
			
		header("Pragma:no-cache\r\n");
		header("Cache-Control:no-cache\r\n");
		header("Expires:0\r\n");
		//输出特定类型的图片格式，优先级为 gif -> jpg ->png
		if(function_exists("imagejpeg")){
			header("content-type:image/jpeg\r\n");
			imagejpeg($im);
		}else{
			header("content-type:image/png\r\n");
			imagepng($im);
		}
		imagedestroy($im);
	}
	function destruct() {
		ob_end_flush();
	}
}
