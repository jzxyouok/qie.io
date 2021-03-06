<?php
/*
 * 文章类
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2016/04/29
 * 更新时间：2016/04/29
 */
 /*
  * database
 
  */

class Article extends Model {
	public $table = 'article';
	const PAGE_BREAK = '<!--pagebreak-->';
	
	/*
	 * 查询文章列表
	 *
	 * @param array $cfg array('field'=>,'table'=>,'order'=>,'size'=>int,'now'=>int)
	 *
	 * @return array
	 */
	public function select($cfg) {
		//处理field
		$cfg['field'] = array(array('table'=>$this->table,'column'=>'*'),array('table'=>'category','column'=>'name','alias'=>'category_name'));
		//处理内联
		$cfg['table'] = array(array('name'=>'category','type'=>'LEFT JOIN', 'on'=>'`article`.`category_id`=`category`.`id`'));
		//处理where
		while(list($k, $v) = each($cfg['where'])) {
			switch($v['field']) {
				case 'tag_id': {
					$cfg['where'][$k]['type'] = 'AND';
					$cfg['where'][$k]['table'] = 'tag_'.$this->table;
					$cfg['table'][] = array('name'=>'tag_'.$this->table,'alias'=>'', 'type'=>'RIGHT JOIN', 'on'=>'`tag_'.$this->table.'`.`target_id`=`'.$this->table.'`.`id`');
				}
				break;
				case 'title':
				case 'category_id': {
					$cfg['where'][$k]['type'] = 'AND';
					$cfg['where'][$k]['table'] = $this->table;
				}
				default: break;
			}
		}
		//处理order，以空格( )分割
		$cfg['order'] = array(array('by'=>$cfg['order']));
		
		return parent::select($cfg);
	}
	/*
	 * 查询单个文章
	 *
	 * @param int $id 文章id
	 *
	 * @return array
	 */
	public function selectOne($id) {
		if(!is_numeric($id) || $id < 1)
			return array();
		
		$sql = "SELECT * FROM `{$this->table}` WHERE `id`={$id} LIMIT 1";
		$db = Loader::load('Database');
		$res = $db->query($sql);
		return $res[0];
	}
	/*
	 * 插入新文章
	 *
	 * @param array $cfg 
	 *
	 * @return int
	 */
	public function insert($data) {
		if(empty($data['title']) || empty($data['content']))
			return false;
		
		$words = array();
		$data['title'] = addslashes(trim($data['title']));
		$data['content'] = trim($data['content']);
		$data['category_id'] = (int)$data['category_id'];
		if($data['keywords']) {
			//处理tag
			$tag = Loader::load('Tag');
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
	/*
	 * 更新文章
	 *
	 * @param array $cfg array('data'=>array,'where'=>int)
	 *
	 * @return int
	 */
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
			$tag = Loader::load('Tag');
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
		if($cfg['data']['keywords']) {
			$res2 = $tag->update(array('words'=>$words,'target_table'=>'article','target_id'=>$id));
		}
		return $res || $res2;
	}
	/*
	 * 删除文章
	 *
	 * @param array/string/int $ids id数组或者,分割
	 *
	 * @return int
	 */
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
			$cfg['where'] = '`id` IN ('.implode(',', $ids).')';
		}
		
		$res = parent::delete($cfg);
		if($res) {
			$tag = Loader::load('Tag');
			$tag->deleteRelation('article', $ids);
		}
		
		return $res;
	}
	/*
	 * 更新文章tag
	 *
	 * @param array/string $words
	 * @param int $id
	 *
	 * @return int
	 */
	public function fixTag($words, $id) {
		if(empty($words) || empty($id))
			return false;
		
		$tag = Loader::load('Tag');
		return $tag->update(array('words'=>$words,'format'=>true,'target_table'=>'article','target_id'=>$id));
	}
}
