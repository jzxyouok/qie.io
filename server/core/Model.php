<?php
/*
 * 数据模型
 * model class
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 更新时间：2016/03/21
 */
class Model {
	protected $error = array('code'=>0, 'msg'=>''); //错误代码和信息
	public $table = ''; //数据库表名称
	const MAX_PAGE_SIZE = 100; //分页最大数
	
	function __construct(){}
	
	protected function error($code= 0, $msg= '') {
		$this->error = array('code'=>(int)$code, 'msg'=> (string)$msg);
		return $this->error;
	}
	/*
	 * 通用数据库select
	 *
	 * @param array $cfg 配置。如果array('field' => '*', 'where' => '', 'order' => 'id DESC', 'current' => 1, 'size' => 20, 'tables'=>array
	 *                   
array (
  'field' => array (0 => array ('name' => 'article','column' => '*'),1 => array ('name' => 'category','column' => 'category_name', 'alias'=>'')),
  'table' => array (0 => array ('name' => 'category','type' => 'LEFT JOIN','on' => '`article`.`category_id`=`category`.`id`'),1 => array ('name' => 'tag_article','alias' => '','type' => 'RIGHT JOIN','on' => '`tag_article`.`target_id`=`article`.`id`')),
  'where' => array (0 => array ('type' => 'and','name' => 'article','field' => 'title','condition' => ' LIKE "飞洒%"'),1 => array ('name' => 'article','field' => 'category_id','condition' => '=1','type' => 'and'), array('condition'=>'MATCH (`content`) AGAINST ("something" IN NATURAL LANGUAGE MODE)')),
  'order' => array (0 => array ('name' => 'article','by' => 'id desc'),1 => array ('name' => 'category','by' => 'id desc'))
	)
	 *
	 * @return array array('total'=>0, 'result'=>array(), 'current'=>0, 'max'=>0, 'size'=>0)
	 */
	public function select($cfg = array('field' => '', 'table'=>array(), 'where' => '', 'order' => '', 'current' => 1, 'size' => 20)) {
		$field = '';
		$table = '';
		$order = '';
		$where = '';
		$data = array('total'=>0, 'result'=>array(), 'current'=>0, 'max'=>0, 'size'=>0);
		
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
		if(!empty($cfg['table'])) {
			if(is_array($cfg['table'])) {
				foreach($cfg['table'] as $v) {
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
		
		$sql = "SELECT COUNT(1) AS `total` FROM `{$this->table}`{$table}{$where}";
		$res = $db->query($sql);
		$data['total'] = (int)$res[0]['total'];
		if($data['total']< 1) {
			//如果查询为空
			return $data;
		}
		
		$data['size'] = (int)$cfg['size'];
		if($data['size'] !== 0) {
			if($data['size'] > self::MAX_PAGE_SIZE) {
				$data['size'] = self::MAX_PAGE_SIZE;
			}
			$data['max'] = ceil($data['total']/$data['size']);
			
			$data['current'] = (int)$cfg['current'];
			if($data['current'] < 1)
				$data['current'] = 1;
			else if($data['current'] > $data['max'])
				$data['current'] = $data['max'];
		}
		
		$sql = "SELECT {$field} FROM `{$this->table}`{$table}{$where}{$order}".($data['size'] !== 0?" LIMIT ".($data['current']-1)*$data['size'].",{$data['size']}":"");
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
		$field = Database::setUpdateField($cfg['data']);
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