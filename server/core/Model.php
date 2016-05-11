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
	public $table = ''; //数据库表名称
	protected $error = array('code'=>0, 'msg'=>''); //错误代码和信息
	const MAX_PAGE_ROW = 100; //分页最大数
	
	protected function error($code= 0, $msg= '') {
		$this->error = array('code'=>(int)$code, 'msg'=> (string)$msg);
		return $this->error;
	}
	/*
	 * 通用数据库select
	 *
	 * @param array $cfg 配置。如果array('field' => '*', 'where' => '', 'order' => 'id DESC', 'now' => 1, 'row' => 20, 'tables'=>array
	 *                   
array (
  'field' => array (0 => array ('name' => 'article','column' => '*'),1 => array ('name' => 'category','column' => 'category_name', 'alias'=>'')),
  'tables' => array (0 => array ('name' => 'category','type' => 'LEFT JOIN','on' => '`article`.`category_id`=`category`.`id`'),1 => array ('name' => 'tag_article','alias' => '','type' => 'RIGHT JOIN','on' => '`tag_article`.`target_id`=`article`.`id`')),
  'where' => array (0 => array ('type' => 'and','name' => 'article','field' => 'title','condition' => ' LIKE "飞洒%"'),1 => array ('name' => 'article','field' => 'category_id','condition' => '=1','type' => 'and'), array('condition'=>'MATCH (`content`) AGAINST ("something" IN NATURAL LANGUAGE MODE)')),
  'order' => array (0 => array ('name' => 'article','by' => 'id desc'),1 => array ('name' => 'category','by' => 'id desc'))
	)
	 *
	 * @return array array('now'=>,'max'=>,'row'=>,'sum'=>,'result'=>)
	 */
	public function select($cfg = array('field' => '', 'where' => '', 'tables'=>array(), 'order' => '', 'now' => 1, 'row' => 20)) {
		if(empty($cfg['field']))
			$cfg['field'] = '*';
		
		$field = '';
		$table = '';
		$order = '';
		$where = '';
		
		$db = Loader::load('Database');
		//处理field
		if(!empty($cfg['field'])) {
			if(is_array($cfg['field'])) {
				$field = Database::setSelectField($cfg['field']);
			} else
				$field = $cfg['field'];
		} else
			$field = '*';
		//处理table连接
		if(!empty($cfg['tables'])) {
			if(is_array($cfg['tables'])) {
				foreach($cfg['tables'] as $v) {
					$table .= " {$v['type']} `{$v['name']}`".($v['alias']?" AS `{$v['alias']}`":"")." ON {$v['on']}";
				}
			} else
				$table = $cfg['table'];
		}
		//处理where
		if(!empty($cfg['where'])) {
			if(is_array($cfg['where'])) {
				$where = ' WHERE '.Database::setSelectWhere($cfg['where']);
			} else
				$where = " WHERE {$cfg['where']}";
		}
		//处理order
		if(!empty($cfg['order'])) {
			if(is_array($cfg['order'])) {
				$order = ' ORDER BY '.Database::setSelectOrder($cfg['order']);
			} else
				$order = " ORDER BY {$cfg['order']}";
		}
		
		$sql = "SELECT COUNT(1) AS `sum` FROM `{$this->table}`{$table}{$where}";
		$res = $db->query($sql);
		$data['sum'] = (int)$res[0]['sum'];
		if($data['sum']< 1) {
			//如果查询为空
			$data['result'] = array();
			return $data;
		}
		
		$data['row'] = (int)$cfg['row'];
		if($data['row'] !== 0) {
			if($data['row'] > self::MAX_PAGE_ROW) {
				$data['row'] = self::MAX_PAGE_ROW;
			}
			$data['now'] = (int)$cfg['now'];
			if($data['now'] < 1)
				$data['now'] = 1;
			
			$data['max'] = 0;
		
			$data['max'] = ceil($data['sum']/$data['row']);
			if($data['now'] > $data['max'])
				$data['now'] = $data['max'];
		}
		
		$sql = "SELECT {$field} FROM `{$this->table}`{$table}{$where}{$order}".($data['row'] !== 0?" LIMIT ".($data['now']-1)*$data['row'].",{$data['row']}":"");
		$data['result'] = $db->query($sql);
		
		return $data;
	}
	/*
	 * 通用数据库insert
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
	 * 通用数据库update
	 *
	 * @param array $cfg array('data'=>需要更新的数据,'where'=>更新的条件,'limit'=>更新的个数)
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
	 * 通用数据库delete
	 *
	 * @param array $cfg array('where'=>删除的条件,'limit'=>删除的个数)
	 *
	 * @return boolean 
	 */
	public function delete($cfg = array('where'=>'','limit'=>0)) {
		$db = Loader::load('Database');
		$sql = "DELETE FROM `{$this->table}`".(!empty($cfg['where'])?" WHERE {$cfg['where']}":"").(!empty($cfg['limit'])?" LIMIT {$cfg['limit']}":"");
		return $db->execute($sql);
	}
}