<?php
/*
 * 加密和解密类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/02/18
 * 修改时间：2012/06/03
 */

class Crypt {
	const SECURE_CODE = 'chen!@#!gui%^*{}(_+biao?>:<FHUE2f';
	/*
	 * 加密方法
	 *
	 * @param string $plain_text 需要加密的字符串
	 * @param $key 加密key
	 *
	 * @return string
	 */
	public static function encrypt($plain_text,$key = null) {
		$key = empty($key) ? self::SECURE_CODE : $key;
		if(strlen($key) > 32)
			$key = substr($key, 0, 32);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypt_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plain_text, MCRYPT_MODE_ECB, $iv);
		return trim(base64_encode($encrypt_text));
    }
	/*
	 * 解密方法
	 *
	 * @param string $encrypt_text 需要解密的字符串
	 * @param $key 加密key
	 *
	 * @return string
	 */
	public static function decrypt($encrypt_text,$key = null) {
		$key = empty($key) ? self::SECURE_CODE : $key;
		if(strlen($key) > 32)
			$key = substr($key, 0, 32);
		$crypt_text = base64_decode($encrypt_text);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypt_text = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypt_text, MCRYPT_MODE_ECB, $iv);
		return trim($decrypt_text);
    }
}
