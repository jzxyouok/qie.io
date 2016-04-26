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
}
