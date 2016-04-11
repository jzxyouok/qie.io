<?php
/*
 * 网站设定
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2016/04/11
 * 
 */

class Setting extends Model {
	/*
	 *  更新网站信息配置文件
	 *
	 * @param array $data
	 *
	 * @return boolean
	 */
	function update($data) {
		if(empty($data))
			return false;
		//判断权限
		
		var_dump($data);
		exit;
		
		if(!empty($data['keywords']))
			$data['keywords'] = str_replace('，',',', $data['keywords']);
		$result = false;
		
		$file = APPPATH.'config/site.php';
		$fp = fopen($file, FOPEN_READ_WRITE_CREATE);
		
		if($fp) {
			$config = fread($fp, filesize($file));
			$search = array();
			$replace = array();
			
			//标题
			if(!empty($data['title'])) {
				$search[] = '#\$config\[\s*\'meta\'\\s*]\s*\[\s*\'title\'\\s*]\s*=\s*\'.*\'#';	
				$replace[] = '$config[\'meta\'][\'title\'] = \''.$data['title'].'\'';
			}
			//关键词
			if(!empty($data['keywords'])) {
				$search[] = '#\$config\[\s*\'meta\'\\s*]\s*\[\s*\'keywords\'\\s*]\s*=\s*\'.*\'#';	
				$replace[] = '$config[\'meta\'][\'keywords\'] = \''.$data['keywords'].'\'';
			}
			//网站描述
			if(!empty($data['description'])) {
				$search[] = '#\$config\[\s*\'meta\'\\s*]\s*\[\s*\'description\'\\s*]\s*=\s*\'.*\'#';	
				$replace[] = '$config[\'meta\'][\'description\'] = \''.$data['description'].'\'';
			}
			//ICP证
			if(!empty($data['icp'])) {
				$search[] = '#\$config\[\s*\'icp\'\\s*]\s*=\s*\'.*\'#';	
				$replace[] = '$config[\'icp\'] = \''.$data['icp'].'\'';
			}
			//监测代码
			if(!empty($data['analytics'])) {
				$search[] = '#\$config\[\s*\'analytics\'\s*\]\s*=\s*<<<EOT[\n\r](?:\w|\W)*?[\n\r]EOT;#';
				$replace[] = "\$config['analytics'] = <<<EOT".PHP_EOL."{$data['analytics']}".PHP_EOL."EOT;";
			}
			//简易联系方式
			if(!empty($data['contact'])) {
				$search[] = '#\$config\[\s*\'contact\'\s*\]\s*=\s*<<<EOT[\n\r](?:\w|\W)*?[\n\r]EOT;#';
				$replace[] = "\$config['contact'] = <<<EOT".PHP_EOL."{$data['contact']}".PHP_EOL."EOT;";
			}
			//管理员
			if(!empty($data['master'])) {
				$search[] = '#\$config\[\s*\'master\'\\s*]\s*=\s*\'.*\'#';	
				$replace[] = '$config[\'master\'] = \''.$data['master'].'\'';
			}
			//模板
			if(!empty($data['theme']) && $this->config->config['theme'] != $data['theme'] && is_dir("{$_SERVER['DOCUMENT_ROOT']}/theme/{$data['theme']}")) {
				$search[] = '#\$config\[\s*\'theme\'\\s*]\s*=\s*\'.*\'#';	
				$replace[] = '$config[\'theme\'] = \''.$data['theme'].'\'';
			}
			$config = preg_replace($search, $replace, $config);
			if(!empty($config) && !empty($search)) {
				fclose($fp);
				$fp = fopen($file, 'w');
				$result = fwrite($fp, $config);
			}
			fclose($fp);	
		}
		return $result;
	}
}
