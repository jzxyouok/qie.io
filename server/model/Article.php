<?php
/*
 * 文章类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2016/04/29
 * 更新时间：2016/04/29
 * 
 */
 /*
  * database
 
  */

class Article extends Model {
	protected $table = 'article';
	
	public function insert($data) {
		if(empty($data['title']) || empty($data['content']))
			return false;
		
		$data['title'] = addslashes(trim($data['title']));
		$data['content'] = trim($data['content']);
		$data['category_id'] = (int)$data['category_id'];
		$data['excerpt'] = addslashes(trim($data['excerpt']));
		$data['from'] = addslashes(trim($data['from']));
		$data['href'] = addslashes(trim($data['href']));
		$data['cover'] = addslashes(trim($data['cover']));
		$data['create_time'] = date(DATE_FORMAT, $_SERVER['REQUEST_TIME']);
		if($data['author'])
			$data['author'] = addslashes(trim($data['author']));
		else {
			$psp = Loader::load('Passport');
			$user = $psp->getUser();
			$data['author'] = $user['nick'];
		}
		
		return parent::insert($data);
	}
	public function update($cfg=array()) {
		if(empty($cfg['data']))
			return false;
			
		if($cfg['data']['title'])
			$cfg['data']['title'] = addslashes(trim($cfg['data']['title']));
		if($cfg['data']['content'])
			$cfg['data']['content'] = addslashes(trim($cfg['data']['content']));
		if($cfg['data']['excerpt'])
			$cfg['data']['excerpt'] = addslashes(trim($cfg['data']['excerpt']));
		if($cfg['data']['from'])
			$cfg['data']['from'] = addslashes(trim($cfg['data']['from']));
		if($cfg['data']['href'])
			$cfg['data']['href'] = addslashes(trim($cfg['data']['href']));
		if($cfg['data']['cover'])
			$cfg['data']['cover'] = addslashes(trim($cfg['data']['cover']));
		if($cfg['data']['author'])
			$cfg['data']['author'] = addslashes(trim($cfg['data']['author']));
		if(isset($cfg['data']['category_id']))
			$cfg['data']['category_id'] = (int)$cfg['data']['category_id'];
		if(isset($cfg['data']['counter']))
			$cfg['data']['counter'] = (int)$cfg['data']['counter'];
		if(isset($cfg['data']['order']))
			$cfg['data']['order'] = (int)$cfg['data']['order'];
		
		$cfg['where'] = '`id`='.(int)$cfg['where'];
		return parent::update($cfg);
	}
}
