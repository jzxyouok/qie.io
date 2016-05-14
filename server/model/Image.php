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
		$thumb = $res['path'].'.jpg';
		if(self::createThumbImage($res['path'], $thumb, array('width'=>200,'height'=>200), array('width'=>$size[0],'height'=>$size[1],'extension'=>$this->extension)))
			$res['thumb'] = $thumb;

		return $res;
	}
	/*
	 * 生成图片缩略图，需要GD库支持，支持远程图片下载
	 * 
	 * @param string $file 文件路径
	 * @param string $new_file 缩略图保存路径
	 * @param int $max_size 最大宽度 array('width'=>,'height'=>)
	 * @param int $image 图片大小，图片太大，有可能内存不足 array('width'=>,'height'=>,'extension'=>)
	 * @param int $quality 缩略图保存质量
	 *
	 * @return string
	 */
	public static function createThumbImage($file, $thumb, $maxSize = array(), $imageSize = array(), $quality = 60) {
		$thumb = DOCUMENT_ROOT . $thumb;
		if(file_exists($thumb))
			return true;
			
    	//如果文件太大，需要分配多一些内存空间
		$file = DOCUMENT_ROOT . $file;
		if(empty($imageSize['width']) || empty($imageSize['height'])) {
			$size = getimagesize($file);
			if(empty($size))
				return false;
			//文件原始大小
			//$width = imagesx($image);
			//$height = imagesy($image);
			$imageSize['width'] = $size[0];
			$imageSize['height'] = $size[1];
		}
		//判断扩展名
		if(empty($imageSize['extension'])) {
			$imageSize['extension'] = substr($file, strrpos($file,'.')+1);
		}
			
		$ratio = 0.5;
		if(!Util::makeDir(dirname($thumb)))
			return false;
		if($imageSize['extension'] != 'gif' && ($maxSize['width'] >= $imageSize['width'] || $maxSize['height'] >= $imageSize['height'])) {
			//如果原始图片尺寸小于缩略图高宽，直接复制文件
			return copy($file, $thumb);
		} else
			$ratio = min($maxSize['width']/$imageSize['width'], $maxSize['height']/$imageSize['height']); //生成新比例
		
		if($imageSize['width'] > 3000 || $imageSize['height'] > 3000)
			$memory = 64;
		else {
			$memory = ceil($imageSize['width']*$imageSize['height']*(24/4)/1024000);
			if($memory > 128)
				$memory = 128;
				
			if($memory < 32)
				$memory = 32;
		}
		ini_set('memory_limit', $memory.'M');
    	//根据扩展名使用不同的生成方法
   		if(($imageSize['extension'] == 'jpeg' || $imageSize['extension'] == 'jpg') && function_exists('imagecreatefromjpeg')){
  			$image = imagecreatefromjpeg($file);
			$func = 'imagejpeg';
		} else if($imageSize['extension'] == 'gif' && function_exists('imagecreatefromgif')){
			$image = imagecreatefromgif($file);
			$func = 'imagegif';
		} else if($imageSize['extension'] == 'png' && function_exists('imagecreatefrompng')){
			$image = imagecreatefrompng($file);
			$func = 'imagepng';
		} else
			return false;

		//如果生成失败
		if(!is_resource($image))
			return false;

		//throw new UploadException('createThumbImage-'.$func.'-无法获取源文件');
		//新图片宽高度
		$newWidth = floor($imageSize['width']*$ratio);
		$newHeight = floor($imageSize['height']*$ratio);
		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {
			//生成新图片资源
			$thumbImage = imagecreatetruecolor($newWidth, $newHeight);
			imagecopyresampled($thumbImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageSize['width'], $imageSize['height']);
		} else
			return false;

			//throw new UploadException('createThumbImage-GD库不支持');
		//写入新文件
		if($image['extension'] == "jpg" || $image['extension'] == "jpeg")
			$res = $func($thumbImage, $thumb, $quality);
		else
			$res = $func($thumbImage, $thumb);
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
