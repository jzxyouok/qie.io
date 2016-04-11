<?php
/*
 * 网站设定
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2016/04/11
 * 
 */

class Setting extends Model {
	const FILE = APP_PATH.'/config/profile.php';
	/*
	 *  更新网站信息配置文件
	 *
	 * @param array $data
	 *
	 * @return boolean
	 */
	function set($data) {
		if(empty($data))
			return false;
		//判断权限
		$profile = Loader::loadVar(self::FILE);
		
		$result = false;
		
		$content = file_get_contents(self::FILE);
		
		if($content) {
			$search = array();
			$replace = array();
			
			foreach($data as $k => $v) {
				$v = trim($v);
				switch($k) {
					case 'admin_relogin': {
						//管理员二次登录验证
						if($v !== $profile['admin_relogin']) {
							$search[] = '#\$profile\[\s*\'admin_relogin\'\s*]\s*=.+#';	
							$replace[] = '$profile[\'admin_relogin\'] = '.($v? 'true':'false').';';
						}
					}
					break;
					case 'theme': {
						//网站主题
						if($v != $profile['theme'] && is_dir(DOCUMENT_ROOT."/theme/{$v}")) {
							$search[] = '#\$profile\[\s*\'theme\'\s*]\s*=.+#';	
							$replace[] = '$profile[\'theme\'] = \''.$v.'\';';
						}
					}
					break;
					case 'keywords': {$v = str_replace('，',',', $v);}
					case 'description': {
						//关键词
						//网站描述
						$search[] = '#\$profile\[\s*\'meta\'\s*]\s*\[\s*\''.$k.'\'\s*]\s*=.+#';	
						$replace[] = '\$profile[\'meta\'][\''.$k.'\'] = \''.$v.'\';';
					}
					break;
					case 'analytics': {
						$search[] = '#\$profile\[\s*\'analytics\'\s*\]\s*=\s*<<<EOT[\n\r](?:\w|\W)*?[\n\r]EOT;#';
						$replace[] = "\$profile['analytics'] = <<<EOT".PHP_EOL."{$v}".PHP_EOL."EOT;";
					}
					break;
					default: {
						//domain,域名
						//homepage,首页
						//title,标题
						//icp,ICP证
						if(isset($profile[$k])) {
							$v = trim($v);
							$search[] = '#\$profile\[\s*\''.$k.'\'\s*]\s*=.+#';	
							$replace[] = '\$profile[\''.$k.'\'] = \''.$v.'\';';
						}
					}
				}
			}
			
			$content = preg_replace($search, $replace, $content);
			
			if(!empty($content) && !empty($search)) {
				$result = file_put_contents(self::FILE, $content);
			}
		}
		return $result;
	}
	public function get() {
		$profile = Loader::loadVar(self::FILE);
	}
}
