<?php
/*
 * 验证码生成和验证
 */
class CaptchaCtrl extends Controller {
	function index() {
		try {
			$captcha = Loader::load('Captcha');
			$captcha->createImage($_GET['w'], $_GET['h']);
		} catch(Exception $e) {
			exit('Exception:'.$e->getMessage());
		}
	}
}