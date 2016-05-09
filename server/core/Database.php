<?php
/*
 * 数据库操作类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2010/10/08
 * 更新时间：2012/06/02
 */
class DatabaseException extends Exception {}

class Database extends Model {
	private $db = null; //数据库连接资源
	private $sql = ''; //sql查询语句
	
	/*
	 * @param string $cfg 数据库配置字符串
	 */
	function __construct($db = 'default') {
		if(!empty($db)) {
			//引用配置文件
			if($db == 'default' && defined('DB_CONFIG'))
				$db = DB_CONFIG;
				
			$DBList = Loader::loadVar(APP_PATH.'/config/database.php', 'DBList');
			if(empty($DBList) || empty($DBList[$db]))
				throw new DatabaseException('the db option is missing.');
		
			$this->connect($DBList[$db]);
		}
	}
	public function connect($option) {
		$this->db = new MySQLi($option['host'], $option['user'], $option['password'], $option['db'], $option['port']);
		if($this->db->connect_errno)
			throw new DatabaseException('mysqli connect failed:'.$this->db->connect_errno);
		if(!empty($option['charset']))
			$this->db->set_charset($option['charset']);
	}
	/*
	 * 查询数据用，执行select查询工作
	 * 
	 * @param string $sql 查询的sql语句(select)
	 * @param string $type 设置返回数值类型
	 *
	 * @return array
	 */
	public function query($sql, $type = 'assoc') {
		$data = array();
		
		if(!empty($sql) && ($res = $this->db->query($sql))) {
			if($res->num_rows<1)
				return $data;
			
			switch($type) {
				case 'assoc' : {
					while($tmp = $res->fetch_assoc())
						$data[] = $tmp;
				}
				break;
				case 'array' : {
					while($tmp = $res->fetch_array())
						$data[] = $tmp;	
				}
				break;
				case 'row' : {
					while($tmp = $res->fetch_row())
						$data[] = $tmp;
				}
			}
		}
		return $data;
	}
	/*
	 * 执行数据库更改操作，如insert，update，delete
	 * 
	 * @param string $sql 执行的sql语句
	 *
	 * @return bool(int) 操作成功，返回1或者id，操作失败返回false
	 */
	public function execute($sql) {
		if(!empty($sql) && ($res = $this->db->query($sql, MYSQLI_USE_RESULT))) {
			$res = $this->db->affected_rows;
			if(0 < $res) {
				if(0 === stripos($sql, 'insert')) {
					$id = $this->db->insert_id;
					if(!empty($id))
						return $id;
					else
						return $res;
				} else return $res;
			} else
				return false;
		} else
			return false;
	}
	/*
	 * 执行一个事务
	 * 
	 * @param array $sql 执行的sql语句数组
	 *
	 * @return bool(array) 操作成功，返回数组，操作失败返回false
	 */
	public function commit($sql) {
		if(empty($sql) || !is_array($sql))
			return false;
			
		$flag = true;
		$res = array();
		$count = 0;
		
		$this->db->query('SET AUTOCOMMIT=0');
		foreach($sql as $k => $v) {
			if(empty($v))
				continue;
			if(!$this->db->query($v))
				$flag = false;
			if(!$flag)
				break;
			if(false !== stripos($sql, 'insert')) {
				if($count = $this->db->insert_id)
					$res[] = $count;
				else
					$res[] = $this->db->affected_rows;
			} else
				$res[] = $this->db->affected_rows;
		}
		
		if($flag) {
			$this->db->query('COMMIT');
			$this->db->query('SET AUTOCOMMIT=1');
			return $res;
		} else {
			$this->db->query('ROLLBACK');
			$this->db->query('SET AUTOCOMMIT=1');
			return false;
		}
	}
	/*
	 * 选择数据库
	 *
	 * @param string $db 数据库名称
	 */
	public function selectDB($db) {
		return $this->db->select_db($db);
	}
	/*
	 * 统计需要查询表数据的总数
	 * 
	 * @param string $field 需要统计的列
	 * @param string $table 需要统计的数据库表
	 * @param string $where 需要统计的查询约束
	 *
	 * @return int
	 */
	public function sum($table, $where = '') {
		
	}
	/*
	 * 设置select语句的field字段
	 *
	 * @param array $fields array (0 => array ('name' => 'article','column' => '*','alias'=>''), 1 => array ('name' => 'category','field' => 'name','column' => 'category_name','alias'=>''))
	 *
	 * @return string
	 */
	public static function setSelectField($param = array()) {
		$field = array();
		foreach($param as $v) {
			$field[] = '`'.$v['name'].'`.'.($v['column'] == '*'?'*':'`'.$v['column'].'`').($v['alias']?' AS `'.$v['alias'].'`':'');
		}
		return implode(',', $field);
	}
	/*
	 * 设置insert语句的字段
	 *
	 * @param array $param 设置语句的field字段，如 array('id' => 1, 'title' => 'hello world', 'other'=>'123') / array(array('id' => 1, 'title' => 'hello world', 'other'=>'123'),array('id' => 1, 'title' => 'hello world', 'other'=>'123'));
	 *
	 * @return string
	 */
	public static function setInsertField($param = array()) {
		$key = array();
		$value = array();
		$hasKey = false;
		foreach($param as $k => $v) {
			if(is_array($v)) {
				$val = array();
				foreach($v as $kk => $vv) {
					if(!$hasKey)
						$key[] = "`{$kk}`";
					$val[] = (is_int($vv)||is_float($vv)?$vv:"'{$vv}'");
				}
				$value[] = '('.implode(',', $val).')';
				if(!$hasKey)
					$hasKey = true;
			} else {
				$key[] = "`{$k}`";
				$value[] = (is_int($v)||is_float($v)?$v:"'{$v}'");
			}
		}
		
		return '('.implode(',', $key).')' . ' VALUES ' . ($counter == 0?'('.implode(',', $value).')':implode(',', $value));
	}
	/*
	 * 设置update语句的字段
	 *
	 * @param array $param 设置语句的field字段，如 array('id' => 1, 'title' => 'hello world', 'other'=>' 123')，如果field为varchar,但是想传数字，前面可以加个空格' 1234'
	 *
	 * @return string
	 */
	public static function setUpdateField($param = array()) {
		$field = '';
		foreach($param as $k => $v) {
			$field .= ",`{$k}`=" . (is_int($v)||is_float($v)?$v:"'{$v}'"); //$field .= ",`{$k}`=" . (preg_match("/^\d+$/", $v) ? $v : "'".trim($v)."'");
		}
		if(!empty($field))
			return substr($field, 1);
		else
			return '';
	}
	/*
	 * 设置select语句的where字段
	 *
	 * @param array $param array ( 0 => array ('type' => 'and','name' => 'article','field' => 'title','condition' => ' LIKE "飞洒%"'),1 => array ('name' => 'article','field' => 'category_id','condition' => '=1','type' => 'and'))
	 *
	 * @return string
	 */
	public static function setSelectWhere($param = array()) {
		//如果条件已and开头
		$where = '';
		$first = array();
		foreach($param as $v) {
			if(empty($first))
				$first = $v;
			$where[] = ($v['type']?$v['type'].' ':'').($v['name']?'`'.$v['name'].'`.':'').($v['field']?'`'.$v['field'].'`':'').$v['condition'];
		}
		if($first['type'])
			return substr(implode(' ', $where), strlen($first['type'])+1);
		else
			return implode(' ', $where);
	}
	/*
	 * 设置select语句的order字段
	 *
	 * @param array $param array (0 => array ('name' => 'article','by' => 'id desc'),1 => array ('name' => 'category','by' => 'id desc'))
	 *
	 * @return string
	 */
	public static function setSelectOrder($param = array()) {
		$order = array();
		foreach($param as $v) {
			$vv = explode(' ', $v['by']);
			$order[] = "`{$v['name']}`.`{$vv[0]}` {$vv[1]}";
		}
		
		return implode(',', $order);
	}
}
