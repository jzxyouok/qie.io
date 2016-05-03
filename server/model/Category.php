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

class Category extends Model {
	protected $depth = 3;
	protected $defaultId = 0;
	protected $table = 'category';
	
	public function selectOne($id) {
		if(!is_numeric($id) || $id < 1)
			return array();
		
		$sql = "SELECT * FROM `{$this->table}` WHERE `id`={$id} LIMIT 1";
		$db = Loader::load('Database');
		$res = $db->query($sql);
		return $res[0];
	}
	public function insert($data) {
		if(empty($data['name']))
			return false;
		
		$data['name'] = trim($data['name']);
		$data['description'] = trim($data['description']);
		$data['parent_id'] = (int)$data['parent_id'];
		$sql = "INSERT INTO `{$this->table}` (`name`,`description`,`parent_id`,`depth`,`root_id`,`create_time`) VALUES ('{$data['name']}','{$data['description']}',".(0 >= $data['parent_id'] ? "0,1,0":"(SELECT * FROM (SELECT `id` FROM `{$this->table}` WHERE `id`={$data['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp1`),(SELECT * FROM (SELECT `depth`+1 FROM `{$this->table}` WHERE `id`={$data['parent_id']} LIMIT 1) AS `tmp2`),(SELECT IF(`tmp3`.`root_id`>0,`tmp3`.`root_id`,{$data['parent_id']}) FROM (SELECT `root_id` FROM `{$this->table}` WHERE `id`={$data['parent_id']} LIMIT 1) AS `tmp3`)").",'".date(DATE_FORMAT, $_SERVER['REQUEST_TIME'])."')";
		//echo $sql;
		$db = Loader::load('Database');
		$res = $db->execute($sql);
		if($res && $data['parent_id'] == 0) {
			$sql = "UPDATE `{$this->table}` SET `root_id`=`id` WHERE `id`={$res} LIMIT 1";
			$db->execute($sql);
		}
		return $res;
	}
	public function update($cfg=array()) {
		$id = (int)$cfg['where'];
		if($id <= 0 || empty($cfg['data']))
			return false;
			
		$sql = "UPDATE `{$this->table}` SET ";
		$tmp = '';
		if(!empty($cfg['data']['name']))
			$tmp .= ",`name`='{$cfg['data']['name']}'";
		if(isset($cfg['data']['description']))
			$tmp .= ",`description`='{$cfg['data']['description']}'";
		if(isset($cfg['data']['parent_id'])) {
			if($id == $cfg['data']['parent_id'])
				return false;
			if(0 ==$cfg['data']['parent_id'])
				$tmp .= ",`root_id`=`id`,`parent_id`=0,`depth`=1";
			else
				$tmp .= ",`root_id`=(SELECT * FROM (SELECT `root_id` FROM `{$this->table}` WHERE `id`={$cfg['data']['parent_id']} LIMIT 1) AS `tmp1`),`parent_id`=(SELECT * FROM (SELECT `id` FROM `{$this->table}` WHERE `id`={$cfg['data']['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp2`),`depth`=(SELECT * FROM (SELECT `depth`+1 FROM `category` WHERE `id`={$cfg['data']['parent_id']} LIMIT 1) AS `tmp3`)";
		}
		
		if(empty($tmp))
			return false;	
		$sql .= substr($tmp, 1)." WHERE `id`={$id} LIMIT 1";
		echo $sql;
		$db = Loader::load('Database');
		return $db->execute($sql);
	}
	public function delete($ids = array()) {
		
		return parent::delete($cfg);
	}
	/*
	 * 修复分类
	 */
	public function fix() {
		$count = 0;
		//修复根分类
		$db = Loader::load('Database');
		$sql = "SELECT `id` FROM `{$this->table}` WHERE `parent_id`=0";
		$child = $db->query($sql);
		$sql = "UPDATE `{$this->table}` SET `depth`=1,`root_id`=`id` WHERE `parent_id`=0";
		$res = $db->execute($sql);
		$count += $res;
		if(!empty($child)) {
			foreach($child as $v) {
				$count += $this->fixTree($v['id'], 2, $v['id']);
			}
		}
		return $count;
	}
	/*
	 * 修复分类
	 */
	private function fixTree($id = 0, $depth = 2, $rootId = 0) {
		if($depth> $this->depth)
			return 0;
			
		$count = 0;
		$db = Loader::load('Database');
		$sql = "SELECT `id` FROM `{$this->table}` WHERE `parent_id`={$id}";
		$child = $db->query($sql);
		$sql = "UPDATE `{$this->table}` SET `depth`={$depth},`root_id`={$rootId} WHERE `parent_id`={$id}";
		$res = $db->execute($sql);
		$count += $res;
		if(!empty($child)) {
			$depth++;
			foreach($child as $v) {
				$count += $this->fixTree($v['id'], $depth, $rootId);
			}
		}
		return $count;
	}
}
