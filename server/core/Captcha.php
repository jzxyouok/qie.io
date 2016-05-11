<?php
/* 
 * 验证码类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/06/25
 * 修改时间：2012/06/25
 */
/*
 * require list
 *
 * Cache.php
 * Util.php
 */
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
	public function createImage($width = 80, $height = 20) {
		if(!function_exists("imagecreate"))
			throw new CaptchaException('Captcha::createImage: GD库不存在');
		
		//验证码句柄
		$c = ip2long(Util::getIP());
		$c = $c.$_SERVER['REQUEST_TIME'].rand(10,99);
		//验证码内容
		$code = Util::randCode(4, '1234567890qwertyuipasdfghjklzxcvbnm'); //去掉O
		//把验证码写入cookie和缓存
		setcookie('c_code', $c, time()+360, '/');
		$cache = Loader::load('Cache');
		$cache->setExpire($this->expire);
		$res = $cache->set($c, strtolower($code));
		//生成图片
		$width = (int)$width;
		$height = (int)$height;
		if($width < 10)
			$width = 80;
		if($height < 10)
			$height = 20;
		
		$im = imagecreate($width, $height);
		$fontType = DOCUMENT_ROOT . '/static/fonts/verdanab.ttf';
		//背景颜色
		//$backcolor = imagecolorallocate($im,190, 190, 120);
		//杂点背景线
		for($j=3; $j<=$height; $j=$j+3)
			imageline($im, 2, $j, $width, $j, imagecolorallocate($im, 130, 220, 245));
		for($j=2; $j<$width; $j=$j+(mt_rand(3, 10)))
			imageline($im, $j, 2, $j-6, $height, imagecolorallocate($im, 225, 245, 255));
		//文字
		$fontColor = imagecolorallocate($im, 0, 0, 0);
		if(function_exists('imagettftext') && file_exists($fontType))
			imagettftext($im, 12, 0, ($width-40)/2, ($height+12)/2, $fontColor, $fontType, $code); //x轴按一个字10px计算
		else
			imagestring($im, 12, ($width-40)/2, ($height-12)/2, $code, $fontColor);
			
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
}
