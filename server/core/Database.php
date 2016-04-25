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
	 * 设置insert语句的字段
	 *
	 * @param array $param 设置语句的field字段，如 array('id' => 1, 'title' => 'hello world', 'other'=>' 123')，如果field为varchar,但是想传数字，前面可以加个空格' 1234'
	 *
	 * @return string
	 */
	public static function setInsertField($param = array()) {
		$key = '';
		$value = '';
		$counter = 0;
		foreach($param as $k => $v) {
			if(is_array($v)) {
				$val = '';
				foreach($v as $kk => $vv) {
					if($counter < 1)
						$key .= ",`{$kk}`";
					$val .= ",".(is_int($vv)||is_float($vv)?$vv:"'{$vv}'");
				}
				$value .= ',('.substr($val, 1).')';
				$counter++;
			} else {
				$key .= ",`{$k}`";
				$value .= ",".(is_int($v)||is_float($v)?$v:"'{$v}'");
			}
		}
		$key = '('.substr($key, 1).')';
		if($counter == 0)
			$value = '('.substr($value, 1).')';
		else
			$value = substr($value, 1);
			
		return $key . ' VALUES ' . $value;
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
	 * 设置select语句的field字段
	 *
	 * @param string $field 如id,name,title
	 * @param string $table 当前需要设置的表
	 *
	 * @return string
	 */
	public static function setSelectField($field = '', $table = '') {
		if(empty($table) || empty($field))
			return $field;
		$field = trim($field);
		$f = explode(',', $field);
		$counter = 0;
		while(list($k, $v) = each($f)) {
			if(false !== strpos($v, ')')) {
				$counter--;
				continue;
			}
			if(false !== strpos($v, '(')) {
				$counter++;
				continue;
			}
			if($counter > 0)
				continue;
			$f[$k] = "`{$table}`.".($v == '*'?$v:"`{$v}`");
		}
		$field = implode(',', $f);
		return $field;
	}
	/*
	 * 设置select语句的where字段
	 *
	 * @param string $where 
	 * @param string $table 当前需要设置的表
	 *
	 * @return string
	 */
	public static function setSelectWhere($where = '', $table = '') {
		//如果条件已and开头
		if(empty($table) || empty($where))
			return $where;
		$w = explode(' ', $where);
		$subquery_count = 0; //子查询次数()
		$slash = ''; //引号类型，'|"
		$slash_count = 0;
		$flag = true; //断点标记
		while(list($k, $v) = each($w)) {
			$flag = true;
			//处理字符串
			if($slash != '') {
				//如果是字符串
				if($v == $slash || preg_match("/[^\\\]*{$slash}$/i", $v)) {
					//字符串结束标记
					$slash = '';
				}
				continue;
			}
			if($v{0} == '\'' || $v{0} == '"') {
				$slash = $v{0};
				continue;
			} else if(preg_match("/(['\"])/i", $v, $slash)) {
				//字符串开始标记
				$slash = $slash[1];
				if(!preg_match("/^[^\\\]*(['\"]).+?\\1/is", $v)) {
					if(!preg_match("/^[^.]+?[!=<>]{1,2}.+/is", $v))
						continue;
				} else
					$slash = ''; //如果是闭包的引号，如'abc'
			} else
				$slash = '';
			//处理括号
			if($slash == '' && false !== strpos($v, '(')) {
				//如果有子查询开始标记
				$subquery_count++;
				if($v == '(') {
					continue;
				}
			}
			if($slash == '' && $subquery_count > 0) {
				//子查询结束标记
				if(false !== strrpos($v, ')'))
					$subquery_count--;
				continue;
			}
			//如果是系统关键词
			if(preg_match("/^(?:and|or|in|exists|not|like|regexp|match|against)/i", $v)) {
				$w[$k] = strtoupper($v);
				continue;
			}
			//如果已经限定表名
			if(preg_match("/^[a-z0-9_`]+\.[a-z0-9_`]+/is", $v))
				continue;
			$match = array();
			if(preg_match("/^[^.]+?([!=<>]{1,2}).+/is", $v, $match) && $slash_count == 0) {
				$w[$k] = "`{$table}`.`".substr($v, 0, strpos($v, $match[1]))."`".substr($v, strpos($v, $match[1])); //如果是判断语句
			} else {
				//如果是数字或者比值
				if(is_numeric($v) || in_array($v, array('=','!=','>=','<=','<>')))
					continue;
				$w[$k] = "`{$table}`.`{$v}`";
			}
		}
		$where = implode(' ', $w);
		return $where;
	}
	/*
	 * 设置select语句的order字段
	 *
	 * @param string $order
	 * @param string $table 当前需要设置的表
	 *
	 * @return string
	 */
	public static function setSelectOrder($order = '', $table = '') {
		$o = explode(',', $order);
		foreach($o as $k => $v) {
			$o[$k] = (false !== strpos($v, '(')? $v : (empty($table) ? "" : "`{$table}`.") . "`" . substr($v, 0, strpos($v, ' ')) . "` " . strtoupper(substr($v, strpos($v, ' ')+1)));	
		}
		$order = implode(',', $o);
		return $order;
	}
}
