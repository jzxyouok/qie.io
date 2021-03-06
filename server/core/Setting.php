<?php
/*
 * 网站设定
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2016/04/11
 */

class Setting {
	const PROFILE_PATH = '/config/profile.php';
	const DATABASE_PATH = '/config/database.php';
	/*
	 *  更新网站信息配置文件(profile)
	 *
	 * @param array $data
	 *
	 * @return boolean
	 */
	function setProfile($data) {
		if(empty($data))
			return false;
			
		//判断权限
		$result = false;
		$profile = Loader::loadVar(APP_PATH. self::PROFILE_PATH);
		$content = file_get_contents(APP_PATH. self::PROFILE_PATH);
		
		if($content) {
			$search = array();
			$replace = array();
			
			foreach($data as $k => $v) {
				if(!isset($profile[$k]) && !isset($profile['meta'][$k]))
					continue;
				
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
					case 'admin_dir': {
						//admin_dir,管理后台地址
						if($v !== $profile['admin_dir'] && rename(DOCUMENT_ROOT.$profile['admin_dir'], DOCUMENT_ROOT.$v)) {
							$search[] = '#\$profile\[\s*\'admin_dir\'\s*]\s*=.+#';	
							$replace[] = '$profile[\'admin_dir\'] = \''.$v.'\';';
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
						//网站流量监测
						$search[] = '#\$profile\[\s*\'analytics\'\s*\]\s*=\s*<<<EOT[\n\r](?:\w|\W)*?[\n\r]EOT;#';
						$replace[] = "\$profile['analytics'] = <<<EOT".PHP_EOL."{$v}".PHP_EOL."EOT;";
					}
					break;
					default: {
						//salt,加密
						//domain,域名
						//homepage,首页
						//title,标题
						//icp,ICP证
						switch($k) {
							case 'db_config': {
								//数据库配置
								$dbList = $this->getDatabase();
								if(!isset($dbList[$v])) {
									continue;
								}
							}
							break;
							case 'state': {
								$v = (int)$v;
							}
							break;
						}
						$search[] = '#\$profile\[\s*\''.$k.'\'\s*]\s*=.+#';	
						$replace[] = '\$profile[\''.$k.'\'] = '.(is_int($v)?$v:'\''.$v.'\'').';';
					}
				}
			}
			
			$content = preg_replace($search, $replace, $content);
			if(!empty($content) && !empty($search)) {
				$result = file_put_contents(APP_PATH. self::PROFILE_PATH, $content);
			}
		}
		return $result;
	}
	/*
	 * 获取网站配置
	 *
	 * @return array
	 */
	public function getProfile() {
		$profile = Loader::loadVar(APP_PATH. self::PROFILE_PATH);
		
		$profile['themes'] = array();
		$dir = DOCUMENT_ROOT.'/theme';
		$handle = opendir($dir);
		while(false !== ($file = readdir($handle))) {
			if(0 !== strpos($file, '.') && is_dir($dir . '/' . $file)) {
         $profile['themes'][] = $file;
       }
			
		}
		closedir($handle);
		
		return $profile;
	}
	/*
	 *  更新网站数据库配置(database)
	 *
	 * @param array $data
	 *
	 * @return boolean
	 */
	public function setDatabase($data) {
		if(empty($data))
			return false;
		
		return Util::var2file(APP_PATH. self::DATABASE_PATH, $data, 'DBList');
	}
	/*
	 * 获取网站数据库配置
	 *
	 * @return array
	 */
	public function getDatabase() {
		return Loader::loadVar(APP_PATH. self::DATABASE_PATH, 'DBList');
	}
}
