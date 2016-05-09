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
	 * @param array $cfg 配置。如果array('field' => '*', 'where' => '', 'order' => 'id DESC', 'now' => 1, 'row' => 20, 'tables'=>array('tablename'=>array('alias'=>'', 'type'=>'LEFT JOIN', 'on'=> 'table1.id=table2.id')))
	 *                   field = array('article'=>'*','category'=>array('name','category_name'))，key:表名 value:列名，如果value是数组，0是列名，1是别名。也可以用array($this->table=>'*','category'=>'name` AS `category_name')
	 *                   table = array ('category' => array ('type' => 'LEFT JOIN','on' => '`article`.`category_id`=`category`.`id`','alias'=>''))
	 *                   order = array('article'=>'id desc','category'=>'id asc')
	 *
	 * @return array array('now'=>,'max'=>,'row'=>,'sum'=>,'result'=>)
	 */
	public function select($cfg = array('field' => '*', 'where' => '', 'tables'=>array(), 'order' => '', 'now' => 1, 'row' => 20)) {
		if(empty($cfg['field']))
			$cfg['field'] = '*';
		
		$field = '';
		$table = '';
		$order = '';
		$where = '';
		//处理field
		if(!empty($cfg['field'])) {
			if(is_array($cfg['field'])) {
				foreach($cfg['field'] as $k=>$v) {
					$field .= ',`'.$k.'`.'.($v == '*'?'*':(is_array($v)?'`'.$v[0].'` AS `'.$v[1].'`':'`'.$v.'`'));
				}
				$field = substr($field, 1);
			} else
				$field = $cfg['field'];
		} else
			$field = '*';
		//处理table连接
		if($cfg['tables']) {
			foreach($cfg['tables'] as $k => $v) {
				$table .= " {$v['type']} `{$k}`".($v['alias']?" AS `{$v['alias']}`":"")." ON {$v['on']}";
			}
		}
		//处理where
		if(!empty($cfg['where'])) {
			$where = " WHERE {$cfg['where']}";
		}
		//处理order
		if(!empty($cfg['order'])) {
			if(is_array($cfg['order'])) {
				foreach($cfg['order'] as $k=>$v) {
					$vv = explode(' ', $v);
					$order .= ",`{$k}`.`{$vv[0]}` {$vv[1]}";
				}
				$order = ' ORDER BY '.substr($order, 1);
			} else
				$order = " ORDER BY {$cfg['order']}";
		}
		
		$db = Loader::load('Database');
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
		$field = Database::setUpdateField($cfg['data'], $this->table);
		$sql = "DELETE FROM `{$this->table}`".(!empty($cfg['where'])?" WHERE {$cfg['where']}":"").(!empty($cfg['limit'])?" LIMIT {$cfg['limit']}":"");
		return $db->execute($sql);
	}
}