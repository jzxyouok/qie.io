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
	function index($pg = 1) {
		$pagination = Loader::load('Pagination', array(array(
			'total'=>500,
			'size'=>20,
			'current'=>$pg,
			'uri'=>'/index.php/ui/'
		)));
		$this->vars['pagination'] = $pagination->get();
		$this->display('ui');
	}
}