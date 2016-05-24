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
CREATE TABLE `tag` (
  `id` int(11) UNSIGNED NOT NULL,
  `word` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `text` (`word`);
	
ALTER TABLE `tag`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
	
CREATE TABLE `tag_article` (
  `tag_id` int(11) UNSIGNED NOT NULL,
  `target_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tag_article`
  ADD PRIMARY KEY (`tag_id`,`target_id`);
  */

class Tag extends Model {
	public $table = 'tag';
	private $relationTables = array('article');
	
	public function select($cfg = array()) {
		$cfg['field'] = "`{$this->table}`.*";
		
		foreach($this->relationTables as $v) {
			$cfg['field'] .= ",(SELECT COUNT(1) FROM `{$this->table}_{$v}` WHERE `tag_id`=`{$this->table}`.`id`) AS `{$v}_total`";
			//$tables[$this->table.'_'.$v] = array('alias'=>'', 'type'=>'LEFT JOIN', 'on'=> '`'.$this->table.'`.`id`=`'.$this->table.'_'.$v.'`.`tag_id`');
		}
		return parent::select($cfg);
	}
	/*
	 * 增加标签
	 *
	 * @param array $data array('words'=>string,'target_table'=>string,'target_id'=>int);
	 *
	 * @return boolean
	 */
	public function insert($data = array()) {
		if(empty($data['words']))
			return false;
			
		if($data['format']) {
			$data['words'] = self::format($data['words'], $data['max']?$data['max']:5);
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
		$insertSql = "INSERT IGNORE INTO `{$this->table}` (`word`) VALUES ".substr($insertSql, 1);
		$res = $db->execute($insertSql);
		
		if(!empty($data['target_table']) && !empty($data['target_id']) && in_array($data['target_table'], $this->relationTables)) {
			$selectSql = "SELECT `id` FROM `{$this->table}` WHERE `word` IN (".substr($selectSql, 1).")";
			$res = $db->query($selectSql);
			if($res) {
				foreach($res as $v)
					$relation[] = array('target_id'=>$data['target_id'], 'tag_id'=>(int)$v['id']);
				
				return $db->execute("INSERT IGNORE INTO `{$this->table}_{$data['target_table']}` ".Database::setInsertField($relation));
			}
		}
		
		return $res;
	}
	/*
	 * 修改标签
	 *
	 * @param array $data array('words'=>string,'target_table'=>string,'target_id'=>int);
	 *
	 * @return boolean
	 */
	public function update($data = array()) {
		if(empty($data['words']))
			return false;
		
		if(!empty($data['target_table']) && !empty($data['target_id']) && in_array($data['target_table'], $this->relationTables))
			$this->deleteRelation($data['target_table'], $data['target_id']);
			
		return $this->insert($data);
	}
	/*
	 * 删除标签
	 *
	 * @param string/array $ids 标签id
	 *
	 * @return boolean
	 */
	public function delete($ids = array()) {
		if(empty($ids))
			return false;
		
		$sql = array();
		if(strpos($ids, ','))
			$ids = explode(',', $ids);
		
		if(is_array($ids)) {
			while(list($k, $v) = each($ids)) {
				if(!is_numeric($v) || $v <= 0)
					unset($ids[$k]);	
			}
			
			$ids = implode(',', $ids);
			$sql[] = "DELETE FROM `{$this->table}` WHERE `id` IN ({$ids})";
			foreach($this->relationTables as $v)
				$sql[] = "DELETE FROM `{$this->table}_{$v}` WHERE `tag_id` IN ({$ids})";
		} else if(is_numeric($ids)) {
			$sql[] = "DELETE FROM `{$this->table}` WHERE `id`={$ids} LIMIT 1"; //删除相册
			foreach($this->relationTables as $v)
				$sql[] = "DELETE FROM `{$this->table}_{$v}` WHERE `tag_id`={$ids}";
		} else
			return false;
		
		$db = Loader::load('Database');
		return array_sum($db->commit($sql));
	}
	/*
	 * 删除标签联系
	 *
	 * @param string $table 标签id
	 * @param int 标签id
	 *
	 * @return boolean
	 */
	public function deleteRelation($table, $ids) {
		if(empty($table) || empty($ids) || !in_array($table, $this->relationTables))
			return false;
		
		if(!is_array($ids)) {
			$where = '`target_id`='.(int)$ids;
		} else {
			$where = '`target_id` IN ('.implode(',', $ids).')';
		}
		$sql = "DELETE FROM `{$this->table}_{$table}` WHERE {$where}";
		$db = Loader::load('Database');
		
		return $db->execute($sql);
	}
	/*
	 * 清理无效tag连接
	 *
	 * @param string $table
	 *
	 * @return int
	 */
	public function clean($table) {
		if(empty($table) || !in_array($table, $this->relationTables))
			return false;
		
		$sql = "DELETE FROM `{$this->table}_{$table}` WHERE NOT EXISTS (SELECT * FROM `{$table}` WHERE `id`=`{$this->table}_{$table}`.`target_id`)";
		$db = Loader::load('Database');
		return $db->execute($sql);
	}
	/*
	 * 格式化标签
	 * 
	 * @param string/array $words 标签
	 * @param int $max 最大标签数量
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
