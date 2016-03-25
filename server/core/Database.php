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
		$db = $db or 'default';
		//引用配置文件
		$DBList = Loader::loadVar(APP_PATH.'/config/database.php', 'DBList');
		if(empty($DBList) || empty($DBList[$db]))
			throw new DatabaseException('the db option is missing.');
		
		$this->connect($DBList[$db]);
	}
	public function connect($option) {
		$this->db = new MySQLi($option['host'], $option['user'], $option['password'], $option['db'], $option['port']);
		if($this->db->connect_errno)
			throw new DatabaseException('mysqli connect failed:'.$this->db->connect_errno);
		if(!empty($option['charset']))
			$this->db->set_charset($option['charset']);
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
}
