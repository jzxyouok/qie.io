<?php
/*
 * 数据模型
 * model class
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 *
 * 更新时间：2016/03/21
 *
 */
class Model {
	protected $table = '';
	protected $error = array('code'=>0, 'msg'=>'');
	const MAX_PAGE_NUM = 100;
	
	protected function error($code= 0, $msg= '') {
		$this->error = array('code'=>(int)$code, 'msg'=> (string)$msg);
		return $this->error;
	}
	/*
	 * 通用select
	 *
	 * @param array $cfg 配置。如果array('field' => '*', 'where' => '', 'order' => 'id DESC', 'now' => 1, 'row' => 20)
	 *
	 * @return array array('now'=>,'max'=>,'row'=>,'sum'=>,'result'=>)
	 */
	public function select($cfg = array('field' => '*', 'where' => '', 'order' => 'id DESC', 'now' => 1, 'row' => 20)) {
		if(empty($cfg['field']))
			$cfg['field'] = '*';
		if(empty($cfg['where']))
			$cfg['where'] = '';
		if(empty($cfg['order']))
			$cfg['order'] = 'id DESC';
		if(!isset($cfg['now']))
			$data['now'] = 1;
		else
			$data['now'] = (int)$cfg['now'];
		if(!isset($cfg['row']))
			$data['row'] = 20;
		else
			$data['row'] = (int)$cfg['row'];
		
		if($data['row'] < 0)
			$data['row'] = 0;
		else if($data['row'] > self::MAX_PAGE_NUM)
			$data['row'] = self::MAX_PAGE_NUM;
		if($data['now'] < 1)
			$data['now'] = 1;
			
		$data['max'] = 0;
		
		$db = Loader::load('Database');
		$cfg['where'] = Database::setSelectWhere($cfg['where'], $this->table);
		$sql = "SELECT COUNT(1) AS `sum` FROM `{$this->table}`".(!empty($cfg["where"])?" WHERE {$cfg['where']}":"");
		$res = $db->query($sql);
		$data['sum'] = (int)$res[0]['sum'];
		if($data['sum']< 1) {
			//如果查询为空
			$data['result'] = array();
			return $data;
		}
		
		$data['max'] = ceil($data['sum']/$data['row']);
		if($cfg['now'] > $data['max'])
			$data['now'] = $cfg['now'];
		else if($cfg['now'] < 1)
			$data['now'] = 1;
		else
			$data['now'] = $cfg['now'];
		
		$cfg['field'] = Database::setSelectField($cfg['field'], $this->table);
		$cfg['order'] = Database::setSelectOrder($cfg['order'], $this->table);
		
		$sql = "SELECT {$cfg['field']} FROM `{$this->table}`".(!empty($cfg['where'])?" WHERE {$cfg['where']}":"")." ORDER BY {$cfg['order']} LIMIT ".($data['now']-1)*$data['row'].",{$data['row']}";
		$data['result'] = $db->query($sql);
		
		return $data;
	}
	/*
	 * 通用insert
	 *
	 * @param array $data 插入的数据
	 *
	 * @return int 
	 */
	public function insert($data) {
		$db = Loader::load('Database');
		$field = Database::setInsertField($data);
		$sql = "INSERT INTO `{$this->table}`".$field;
		return $db->execute($sql);
	}
	/*
	 * 通用update
	 *
	 * @param array $cfg array('data'=>需要更新的数据,'where'=>更新的条件,'limit'=>更新的个数
	 *
	 * @return boolean 
	 */
	public function update($cfg = array('data'=>array(),'where'=>'','limit'=>0)) {
		$db = Loader::load('Database');
		$field = Database::setUpdateField($cfg['data'], $this->table);
		$sql = "UPDATE `{$this->table}` SET {$field}".(!empty($cfg['where'])?" WHERE {$cfg['where']}":"").(!empty($cfg['limit'])?" LIMIT {$cfg['limit']}":"");
		return $db->execute($sql);
	}
	/*
	 * 通用delete
	 *
	 * @param array $cfg array('where'=>删除的条件,'limit'=>删除的个数
	 *
	 * @return boolean 
	 */
	public function delete($cfg = array('where'=>'','limit'=>0)) {
		$db = Loader::load('Database');
		$field = Database::setUpdateField($cfg['data'], $this->table);
		$sql = "DELETE FROM `{$this->table}`".(!empty($cfg['where'])?" WHERE {$cfg['where']}":"").(!empty($cfg['limit'])?" LIMIT {$cfg['limit']}":"");
		return $db->execute($sql);
	}
}