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
		$file = APP_PATH.'/config/profile.php';
		$profile = Loader::loadVar($file);
		
		$result = false;
		
		$content = file_get_contents($file);
		
		if($content) {
			$search = array();
			$replace = array();
			
			//管理员二次登录验证
			if(isset($data['admin_relogin']) && $data['admin_relogin'] !== $profile['admin_relogin']) {
				$search[] = '#\$profile\[\s*\'admin_relogin\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'admin_relogin\'] = '.($data['admin_relogin']? 'true':'false').';';
			}
			//域名
			if(isset($data['domain'])) {
				$data['domain'] = trim($data['domain']);
				$search[] = '#\$profile\[\s*\'domain\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'domain\'] = \''.$data['domain'].'\';';
			}
			//首页
			if(isset($data['homepage'])) {
				$data['homepage'] = trim($data['homepage']);
				$search[] = '#\$profile\[\s*\'homepage\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'homepage\'] = \''.$data['homepage'].'\';';
			}
			//网站主题
			if($data['theme'] && ($data['theme'] = trim($data['theme'])) && $data['theme'] != $profile['theme'] && is_dir(DOCUMENT_ROOT."/theme/{$data['theme']}")) {
				$search[] = '#\$profile\[\s*\'theme\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'theme\'] = \''.$data['theme'].'\';';
			}
			//标题
			if(isset($data['title'])) {
				$data['title'] = trim($data['title']);
				$search[] = '#\$profile\[\s*\'title\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'title\'] = \''.$data['title'].'\';';
			}
			//关键词
			if(isset($data['keywords'])) {
				$data['keywords'] = str_replace('，',',', trim($data['keywords']));
				$search[] = '#\$profile\[\s*\'meta\'\\s*]\s*\[\s*\'keywords\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'meta\'][\'keywords\'] = \''.$data['keywords'].'\';';
			}
			//网站描述
			if(isset($data['description'])) {
				$data['description'] = trim($data['description']);
				$search[] = '#\$profile\[\s*\'meta\'\\s*]\s*\[\s*\'description\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'meta\'][\'description\'] = \''.$data['description'].'\';';
			}
			//监测代码
			//preg_replace('/\s*/', '',$data['analytics']) == preg_replace('/\s*/', '',$profile['analytics'])
			if(isset($data['analytics'])) {
				$data['analytics'] = trim($data['analytics']);
				$search[] = '#\$profile\[\s*\'analytics\'\s*\]\s*=\s*<<<EOT[\n\r](?:\w|\W)*?[\n\r]EOT;#';
				$replace[] = "\$profile['analytics'] = <<<EOT".PHP_EOL."{$data['analytics']}".PHP_EOL."EOT;";
			}
			//ICP证
			if(isset($data['icp'])) {
				$search[] = '#\$profile\[\s*\'icp\'\\s*]\s*=.+#';	
				$replace[] = '$profile[\'icp\'] = \''.trim($data['icp']).'\';';
			}
			
			$content = preg_replace($search, $replace, $content);
			
			if(!empty($content) && !empty($search)) {
				$result = file_put_contents($file, $content);
			}
		}
		return $result;
	}
}
