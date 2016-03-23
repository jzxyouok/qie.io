<?php
/*
 * 验证码生成和验证
 */
class CaptchaCtrl extends Controller {
	function index() {
		try {
			//ob_start();
			$captcha = Loader::load('captcha');
			$captcha->createImage();
		} catch(Exception $e) {
			exit('Exception:'.$e->getMessage());
		}
	}
}