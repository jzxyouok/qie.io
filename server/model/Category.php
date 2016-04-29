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
		if(empty($data['title']))
			return false;
		$data['lock'] = (int)$data['lock'];
		$sql = "INSERT INTO `sort` (`title`,`description`,`parent_id`,`depth`,`root_id`,`lock`,`create_time`) VALUES ('{$data['title']}','{$data['description']}',".(0 >= $data['parent_id'] ? "0,1,0":"(SELECT * FROM (SELECT `id` FROM `sort` WHERE `id`={$data['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp1`),(SELECT * FROM (SELECT `depth`+1 FROM `sort` WHERE `id`={$data['parent_id']} LIMIT 1) AS `tmp2`),(SELECT IF(`tmp3`.`root_id`>0,`tmp3`.`root_id`,{$data['parent_id']}) FROM (SELECT `root_id` FROM `sort` WHERE `id`={$data['parent_id']} LIMIT 1) AS `tmp3`)").",{$data['lock']},'".date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME'])."')";
		//echo $sql;
		$this->db->query($sql);
		$res = $this->db->insert_id();
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
		$sql = "SELECT `id` FROM `sort` WHERE `parent_id`={$id}";
		$child = $this->db->query($sql)->result_array();
		if($depth < 2)
			$rootId = "`id`";
		else
			$rootId = "(SELECT * FROM (SELECT `root_id` FROM `sort` WHERE `id`={$id}) AS `tmp`)";
		$sql = "UPDATE `sort` SET `depth`={$depth},`root_id`={$rootId} WHERE `parent_id`={$id}";
		$this->db->query($sql);
		$count += $this->db->affected_rows();
		if(!empty($child)) {
			$depth++;
			foreach($child as $v) {
				$count += $this->fix($v['id'], $depth, false);
			}
		}
		return $count;
	}
}
