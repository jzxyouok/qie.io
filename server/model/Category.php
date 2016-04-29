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
		if($data['parent_id'] == 0)
			$this->fix();
		return $res;
	}
	public function update($cfg=array()) {
		
		return parent::update($cfg);
	}
	public function delete($ids = array()) {
		
		return parent::delete($cfg);
	}
	/*
	 * 修复文章分类
	 */
	public function fix($id = 0, $depth = 1, $first = true) {
		//最大支持100层级
		if($depth > 100 || ($first && $id !== 0))
			return 0;
		$count = 0;
		$db = Loader::load('Database');
		$sql = "SELECT `id` FROM `{$this->table}` WHERE `parent_id`={$id}";
		$child = $db->query($sql);
		if($depth < 2)
			$rootId = "`id`";
		else
			$rootId = "(SELECT * FROM (SELECT `root_id` FROM `{$this->table}` WHERE `id`={$id}) AS `tmp`)";
		$sql = "UPDATE `{$this->table}` SET `depth`={$depth},`root_id`={$rootId} WHERE `parent_id`={$id}";
		$res = $db->execute($sql);
		$count += $res;
		if(!empty($child)) {
			$depth++;
			foreach($child as $v) {
				$count += $this->fix($v['id'], $depth, false);
			}
		}
		return $count;
	}
}
