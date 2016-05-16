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
	public $extension = '';
	public $mimes = array('jpg'	=>	array('image/jpeg', 'image/pjpeg'),
				'png'	=>	array('image/png',  'image/x-png'),
				'gif'	=>	'image/gif',
				'bmp'	=>	array('image/bmp', 'image/x-windows-bmp'),
				'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpe'	=>	array('image/jpeg', 'image/pjpeg'));
	public $dir = '/user_files/upload/image/';
	public $maxSize = 1048576; //1M
	
	public function upload($image) {
		if(empty($image))
			return false;
		
		$file = Loader::load('model/File');
		$file->mimes = $this->mimes;
		$file->dir = $this->dir. date("Ymd");
		$file->extension = $this->extension;
		$file->maxSize = $this->maxSize;

		$res = $file->transfer($image);

		$this->name = $file->name;
		$this->extension = $file->extension;
		$this->mime = $file->mime;
		$this->size = $file->size;
		unset($image, $file);
		//生成缩略图
		$size = substr($res['path'], strrpos($res['path'], '_')+1);
		$size = explode('x', $size);
		$thumb = $res['path'].'.'.$this->extension;
		if(self::createThumb($res['path'], $thumb, array('max_width'=>200,'max_height'=>200,'image_width'=>$size[0],'image_height'=>$size[1],'image_extension'=>$this->extension), 70))
			$res['thumb'] = $thumb;

		return $res;
	}
	/*
	 * 生成图片缩略图，需要GD库支持，支持远程图片下载
	 * 
	 * @param string $origin 原始图片路径
	 * @param string $target 缩略图路径
	 * @param int $param 最大宽度 array('image_width'=>,'image_height'=>,'image_extension'=>,'max_width'=>,'max_height'=>)
	 * @param int $quality 缩略图保存质量
	 *
	 * @return string
	 */
	public static function createThumb($origin, $target, $param = array(), $quality = 60) {
		$target = DOCUMENT_ROOT . $target;
		if(file_exists($target))
			return true;
		$origin = DOCUMENT_ROOT . $origin;
		if(!file_exists($origin))
			return false;
		//生成文件夹
		if(!Util::makeDir(dirname($target)))
			return false;
		
		$image = NULL;
		//源图扩展名
		if(empty($param['image_extension'])) {
			$originExt = substr($origin, strrpos($origin,'.')+1);
		} else
			$originExt = $param['image_extension'];
		//缩略图扩展名
		$targetExt = substr($target, strrpos($target,'.')+1);
		
		if(($originExt == 'jpg' || $originExt == 'jpeg') && function_exists('imagecreatefromjpeg')){
  		$imageCreateFrom = 'imagecreatefromjpeg';
		} else if($originExt == 'png' && function_exists('imagecreatefrompng')){
			$imageCreateFrom = 'imagecreatefrompng';
		} else if($originExt == 'gif' && function_exists('imagecreatefromgif')){
			$imageCreateFrom = 'imagecreatefromgif';
		} else
			return copy($origin, $target); //只支持jpg/png/gif
			
		if(empty($param['image_width']) || empty($param['image_height'])) {
			if(!($image = $imageCreateFrom($origin)))
				return false; //如果生成失败
			
			//文件原始大小
			$param['image_width'] = imagesx($image);
			$param['image_height'] = imagesy($image);
		}
		$param['max_width'] = is_numeric($param['max_width'])?(int)$param['max_width']:200;
		$param['max_height'] = is_numeric($param['max_height'])?(int)$param['max_height']:200;
		//直接复制文件（1.相同扩展名;）
		if($originExt == $targetExt && ($param['max_width'] >= $param['image_width'] && $param['max_height'] >= $param['image_height'])) {
			return copy($origin, $target);
		}
		
		$ratio = min($param['max_width']/$param['image_width'], $param['max_height']/$param['image_height']); //生成新比例
		
		if($param['image_width'] > 3000 || $param['image_height'] > 3000)
			$memory = 32;
		else {
			$memory = ceil($param['image_width']*$param['image_height']*(24/4)/1024000);
			if($memory > 128)
				$memory = 128;
			else if($memory < 32)
				$memory = 32;
		}
		ini_set('memory_limit', $memory.'M');
    //根据扩展名使用不同的生成方法
		if(empty($image) && !($image = $imageCreateFrom($origin)))
			return false; //如果生成失败
		
		//新图片宽高度
		$newWidth = floor($param['image_width']*$ratio);
		$newHeight = floor($param['image_height']*$ratio);
		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {
			//生成新图片资源
			$thumbImage = imagecreatetruecolor($newWidth, $newHeight);
			if($targetExt == 'png'){
				imagealphablending($thumbImage, false);
      	imagesavealpha($thumbImage, true);
			}
			imagecopyresampled($thumbImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $param['image_width'], $param['image_height']);
		} else
			return false;
			
		//写入新文件
		if($targetExt == "jpg" || $targetExt == "jpeg") {
			$res = imagejpeg($thumbImage, $target, $quality);
		} else if($targetExt == 'png'){
			$res = imagepng($thumbImage, $target);
		} else {
			$res = imagegif($thumbImage, $target);
		}
		//清理
		imagedestroy($image);
		imagedestroy($thumbImage);
		unset($image);
		unset($thumbImage);
		ini_set('memory_limit', '8M');
		
		if(!$res)
			return false;
		else
			return true;
	}
}
