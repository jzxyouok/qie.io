<?php
/*
 * ui
 * 作者：billchen
 * 邮箱：48838096@qq.com
 */

class UiCtrl extends Controller {
	/*
	 * 页面
	 */
	//ui
	function index() {
		$this->view('ui', 'theme');
	}
}