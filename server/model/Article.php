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
	
	public function selectOne($id) {
		if(!is_numeric($id) || $id < 1)
			return array();
		
		$sql = "SELECT * FROM `{$this->table}` WHERE `id`={$id} LIMIT 1";
		$db = Loader::load('Database');
		$res = $db->query($sql);
		return $res[0];
	}
	public function insert($data) {
		if(empty($data['title']) || empty($data['content']))
			return false;
		
		$words = array();
		$data['title'] = addslashes(trim($data['title']));
		$data['content'] = trim($data['content']);
		$data['category_id'] = (int)$data['category_id'];
		if($data['keywords']) {
			//处理tag
			$tag = Loader::load('model/Tag');
			$words = Tag::format($data['keywords']);
			$data['keywords'] = implode(',', $words);
		}
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
		
		$res = parent::insert($data);
		if($res && $data['keywords']) {
			$tag->insert(array('words'=>$words,'target_table'=>'article','target_id'=>$res));
		}
		return $res;
	}
	public function update($cfg=array()) {
		if(empty($cfg['data']))
			return false;
		
		$id = (int)$cfg['where'];
		$words = array();
		if($cfg['data']['title'])
			$cfg['data']['title'] = addslashes(trim($cfg['data']['title']));
		if($cfg['data']['content'])
			$cfg['data']['content'] = addslashes(trim($cfg['data']['content']));
		if($cfg['data']['keywords']) {
			//处理tag
			$tag = Loader::load('model/Tag');
			$words = Tag::format($cfg['data']['keywords']);
			$cfg['data']['keywords'] = implode(',', $words);
		}
			$cfg['data']['keywords'] = addslashes(str_replace('，',',', trim($cfg['data']['keywords'])));
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
		
		$cfg['where'] = '`id`='.$id;
		$res = parent::update($cfg);
		if($res && $cfg['data']['keywords']) {
			$tag->update(array('words'=>$words,'target_table'=>'article','target_id'=>$id));
		}
		return $res;
	}
	public function delete($ids = array()) {
		if(empty($ids))
			return false;
		
		$cfg = array();
		if(is_numeric($ids)) {
			$cfg = array('where'=>'`id`='.(int)$ids, 'limit'=>1);
		} else {
			if(is_string($ids))
				$ids = explode(',', $ids);
			
			while(list($k, $v) = each($ids)) {
				if(!is_numeric($v))
					unset($ids[$k]);
			}
			$cfg['limit'] = count($ids);
			$ids = implode(',', $ids);
			$cfg['where'] = '`id` IN ('.$ids.')';
		}
		
		return parent::delete($cfg);
	}
}
