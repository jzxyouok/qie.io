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
}
