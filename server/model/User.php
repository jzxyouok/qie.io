<?php
/*
 * 用户类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2012/02/18
 * 更新时间：2016/03/22
 * 
 */

class User extends Model {
	protected $table = 'user';
	public function selectOne($id = 0) {
		if(empty($id) || $id < 1)
			return array();
		
		$sql = "SELECT * FROM `{$this->table}` WHERE `id`={$id} LIMIT 1";
		$db = Loader::load('Database');
		$res = $db->query($sql);
		return $res[0];
	}
	public function selectAdmin($cfg) {
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
		$cfg['where'] = Database::setSelectWhere($cfg['where'], 'u');
		$sql = "SELECT COUNT(1) AS `sum` FROM `user_admin`".(!empty($cfg["where"])?" WHERE {$cfg['where']}":"");
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
		
		if($cfg['order'])
			$cfg['order'] = Database::setSelectOrder($cfg['order'], 'u');
		
		$sql = "SELECT `u`.`id`,`u`.`name`,`u`.`nick`,`u`.`email`,`ua`.`grade` FROM `user_admin` AS `ua` LEFT JOIN `user` AS `u` ON `ua`.`user_id`=`u`.`id`".(!empty($cfg['where'])?" WHERE {$cfg['where']}":"").(!empty($cfg['order'])?" ORDER BY {$cfg['order']}":"")." LIMIT ".($data['now']-1)*$data['row'].",{$data['row']}";
		
		$data['result'] = $db->query($sql);
		
		return $data;
	}
	/*
	 * 删除用户
	 *
	 * @param array/int/string $ids
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
			$ids = implode(',', $ids);
			$cfg['where'] = '`id` IN ('.$ids.')';
		}
		//不能删除管理员
		$cfg['where'] .= ' AND `id` NOT IN (SELECT `user_id` FROM `user_admin` WHERE `user_id` IN ('.$ids.'))';
		
		return parent::delete($cfg);
	}
	public function updateAdmin($id, $password) {
		$psp = Loader::load('Passport');
		return $psp->adminAdd((int)$id, $password);
	}
	public function deleteAdmin($id) {
		$psp = Loader::load('Passport');
		return $psp->adminDelete((int)$id);
	}
}
