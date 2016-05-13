<?php
/*
 * 上传图片类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2016/05/04
 * 更新时间：2016/05/04
 * 
 */

class Image extends Model {
	public $table = 'image';
	
	public function upload($image) {
		if(empty($image))
			return false;
		
		$file = Loader::load('model/File');
		$file->mimes = array('jpg'	=>	array('image/jpeg', 'image/pjpeg'),
				'png'	=>	array('image/png',  'image/x-png'),
				'gif'	=>	'image/gif',
				'bmp'	=>	array('image/bmp', 'image/x-windows-bmp'),
				'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpe'	=>	array('image/jpeg', 'image/pjpeg'));
		$file->dir = '/user_files/upload/image/' . date("Ymd");
		$res = $file->transfer($image);
		return $res;
	}
}
