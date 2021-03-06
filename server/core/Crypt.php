<?php
/*
 * 加密和解密类
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2012/02/18
 * 修改时间：2012/06/03
 */

class Crypt {
	const SECURE_CODE = 'chen!@#!gui%^*{}(_+biao?>:<FHUE2f';
	/*
	 * 加密方法
	 *
	 * @param string $plainText 需要加密的字符串
	 * @param $key 加密key
	 *
	 * @return string
	 */
	public static function encrypt($plainText,$key = null) {
		$key = empty($key) ? self::SECURE_CODE : $key;
		if(strlen($key) > 32)
			$key = substr($key, 0, 32);
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
		$encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plainText, MCRYPT_MODE_ECB, $iv);
		return trim(base64_encode($encryptText));
    }
	/*
	 * 解密方法
	 *
	 * @param string $encryptText 需要解密的字符串
	 * @param $key 加密key
	 *
	 * @return string
	 */
	public static function decrypt($encryptText,$key = null) {
		$key = empty($key) ? self::SECURE_CODE : $key;
		if(strlen($key) > 32)
			$key = substr($key, 0, 32);
		$cryptText = base64_decode($encryptText);
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
		$decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $cryptText, MCRYPT_MODE_ECB, $iv);
		return trim($decryptText);
    }
}
