<?php
/*
 * 标签类
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2016/05/04
 * 更新时间：2016/05/04
 * 
 */
 /*
  * database

  */

class Tag extends Model {
	protected $table = 'tag';
	
	/*
	 * 增加标签
	 *
	 * @param string/array $words 标签
	 * @param string $targetTable 目标数据库表名
	 * @param int $targetId 目标数据库表行id
	 * @param int $max 保存标签最大数量
	 *
	 * @return boolean
	 */
	public function insert($data = array()) {
		if(empty($data['words']) || empty($data['target_table']) || empty($data['target_id']))
			return false;
			
		if($data['format']) {
			self::format($data['words'], $data['max']?$data['max']:5);
		}
		$relation = array();
		//保存标签
		$db = Loader::load('Database');
		$selectSql = '';
		$insertSql = '';
		
		foreach($data['words'] as $v) {
			if($v) {
				$selectSql .= ",'{$v}'";
				$insertSql .= ",('{$v}')";
			}
		}
		$selectSql = "SELECT `id` FROM `{$this->table}` WHERE `word` IN (".substr($selectSql, 1).")";
		$insertSql = "INSERT INTO `{$this->table}` (`word`) VALUES ".substr($insertSql, 1);
		$db->execute($insertSql);
		$res = $db->query($selectSql);
		if($res) {
			foreach($res as $v)
				$relation[] = array('target_id'=>$data['target_id'], 'tag_id'=>(int)$v['id']);
			return $db->execute("INSERT IGNORE INTO `{$this->table}_{$data['target_table']}` ".Database::setInsertField($relation));
		}
		
		return false;
	}
	/*
	 * 格式化标签
	 * 
	 * @param string/array $words 标签
	 *
	 * @return array
	 */
	public static function format($words, $max = 5) {
		if(empty($words))
			return false;
			
		if(!is_array($words)) {
			$words = preg_replace(array('/(\s){2,}/','/，/iu','/[^\w\x{4e00}-\x{9fa5},]/iu'), array('\\1',',',''), (string)$words); //提取英文和中文
			if(false !== strpos($words, ','))
				$words = explode(',', $words);
			else
				$words = (array)$words;
		}
		$words = array_map('strtolower', $words);
		if(1 < count($words)) {
			$words = array_unique($words); //去除重复值
		}
		
		$c = 0;
		while(list($k, $v) = each($words)) {
			if(empty($v) || $c >= $max) {
				unset($words[$k]); //去除空值
				continue;
			}
			$words[$k] =  mb_substr($v, 0, 50, 'utf-8');
			$c++;
		}
			
		return $words;
	}
}
