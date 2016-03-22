<?php
/*
 * 数据库操作类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2010/10/08
 * 更新时间：2012/06/02
 */
class DatabaseException extends Exception {}

class Database {
	private $config = ''; //配置字符串
	private $connection = null; //数据库连接资源
	private $db = null; //数据库字符串
	private $sql = ''; //sql查询语句
	private $charset = ''; //字符编码
	
	/*
	 * @param string $cfg 数据库配置字符串
	 * @param string $charset 数据库使用字符编码
	 */
	function __construct($db = 'default') {
		$db = $db or 'default';
		include_once(APP_PATH . '/config/database.php');
		
		if(empty($DBList) || empty($DBList[$db]))
			throw new DatabaseException('the db option is missing.');
		
		$this->connection = new MySQLi($DBList[$db]['host'], $DBList[$db]['user_name'], $DBList[$db]['password'], $DBList[$db]['db']);
	}
	
	/*
	 * 查询数据用，执行select查询工作
	 * 
	 * @param string $sql 查询的sql语句(select)
	 * @param string $type 设置返回数值类型
	 *
	 * @return array
	 */
	public function query($sql, $type = 'assoc') { //查询语句
		
	}
	/*
	 * 执行数据库更改操作，如insert，update，delete
	 * 
	 * @param string $sql 执行的sql语句
	 *
	 * @return bool(int) 操作成功，返回1或者id，操作失败返回false
	 */
	public function execute($sql) {
		
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
	
	public function __destruct() {
		
	}
}
