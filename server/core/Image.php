<?php
/*
 * 上传图片类
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2016/05/04
 * 更新时间：2016/05/04
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
		
		$file = Loader::load('File');
		$file->mimes = $this->mimes;
		$file->dir = $this->dir. date("Ymd");
		$file->extension = $this->extension;
		$file->maxSize = $this->maxSize;

		$result = $file->transfer($image, $this);
		
		$this->name = $file->name;
		$this->extension = $file->extension;
		$this->mime = $file->mime;
		$this->size = $file->size;
		unset($image, $file);
		if($result) {
			$data = array('file_md5'=>$result['md5'],'create_time'=>date(DATE_FORMAT, $_SERVER['REQUEST_TIME']));
			$result['id'] = parent::insert($data);
			//生成缩略图
			$size = substr($result['path'], strrpos($result['path'], '_')+1);
			$size = explode('x', $size);
			$thumb = $result['path'].'.'.$this->extension;
			if(self::createThumb($result['path'], $thumb, array('max_width'=>200,'max_height'=>200,'image_width'=>$size[0],'image_height'=>$size[1],'image_extension'=>$this->extension), 70))
				$result['thumb'] = $thumb;
		}

		return $result;
	}
	/*
	 * 分解图片高度/宽度/拍摄角度
	 *
	 * @param object $file File对象
	 */
	public function transferCallback($file = NULL) {
		if(!$file)
			return;
		
		//为图片判断高宽
		$path = $file->dir . '/' . $file->name . '.' . $file->extension;
			
		$orientation = 0;
		if(function_exists('exif_read_data')) {
			$res = exif_read_data(DOCUMENT_ROOT . $path);
			$width = $res['COMPUTED']['Width'];
			$height = $res['COMPUTED']['Height'];
			$orientation = (int)$res['Orientation']; //1:0°，6:顺时针90°，8:逆时针90°，3:180°
		}
		if(empty($width) || empty($height)) {
			$res = getimagesize(DOCUMENT_ROOT . $path);
			$width = $res[0];
			$height = $res[1];
		}
		
		$name = "{$file->name}_{$width}x{$height}x{$orientation}";
		if(rename(DOCUMENT_ROOT . $path, DOCUMENT_ROOT . $file->dir . '/' . $name . '.' . $file->extension)) {
			$file->name = $name;
		}
	}
	/*
	 * 查询select
	 * 
	 * @param array $cfg 原始图片路径
	 *
	 * @return array
	 */
	public function select($cfg = array()) {
		$cfg['field'] = array(array('name'=>$this->table,'column'=>'id'),array('name'=>$this->table,'column'=>'create_time'), array('name'=>'file','column'=>'md5'), array('name'=>'file','column'=>'path'));
		$cfg['table'] = array(array('name'=>'file','type'=>'LEFT JOIN', 'on'=>'`file`.`md5`=`'.$this->table.'`.`file_md5`'));
		
		return parent::select($cfg);
	}
	/*
	 * delete
	 * 
	 * @param string $md5
	 *
	 * @return array
	 */
	public function delete($md5 = '') {
		if(!is_string($md5) || strlen($md5) != 32)
			return false;
			
		$db = Loader::load('Database');
		$sql = "DELETE FROM `{$this->table}` WHERE `file_md5`='{$md5}' LIMIT 1";
		return $db->execute($sql);
	}
	/*
	 * 生成图片缩略图，需要GD库支持
	 * 只支持jpg/png/gif
	 * 
	 * @param string $origin 原始图片路径
	 * @param string $target 缩略图路径
	 * @param int $param 最大宽度 array('image_width'=>,'image_height'=>,'image_extension'=>,'max_width'=>,'max_height'=>)
	 * @param int $quality 缩略图保存质量
	 *
	 * @return string
	 */
	public static function createThumb($origin, $target, $param = array(), $quality = 60) {
		$origin = DOCUMENT_ROOT . $origin;
		if(!file_exists($origin))
			return false;
		//生成文件夹
		if(file_exists(DOCUMENT_ROOT . $target))
			return true;
		if(!Util::makeDir(dirname($target)))
			return false;
		$target = DOCUMENT_ROOT . $target;
		
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
		if($originExt == $targetExt && $param['max_width'] >= $param['image_width'] && $param['max_height'] >= $param['image_height']) {
			return copy($origin, $target);
		}
		
		$memory = 0;
		if($param['image_width'] > 5000 || $param['image_height'] > 5000) {
			$memory = 1;
			ini_set('memory_limit', '-1');
		} else if($param['image_width'] > 1000 && $param['image_height'] > 1000) {
			$memory = ceil($param['image_width']*$param['image_height']/1024000*(24/4));
			if($memory > 128)
				$memory = 128;
			else if($memory < 8)
				$memory = 8;
			
			ini_set('memory_limit', $memory.'M');
		}
    //根据扩展名使用不同的生成方法
		if(empty($image) && !($image = $imageCreateFrom($origin)))
			return false; //如果生成失败
		
		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {
			//生成新比例
			$ratio = min($param['max_width']/$param['image_width'], $param['max_height']/$param['image_height']);
			//新图片宽高度
			$newWidth = floor($param['image_width']*$ratio);
			$newHeight = floor($param['image_height']*$ratio);
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
		if($memory>0)
			ini_set('memory_limit', '8M');
		
		if(!$res)
			return false;
		else
			return true;
	}
}
