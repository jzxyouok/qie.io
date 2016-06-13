<?php
/*
 * 分类类
 * 
 * 作者：billchen
 * 邮箱：48838096@qq.com
 * 网站：http://qie.io/
 *
 * 创建时间：2016/05/04
 * 更新时间：2016/05/04
 */
 /*
  * database
--
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `root_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `depth` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT '2012-02-18 00:00:00',
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `parent` (`parent_id`,`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  */

class Category extends Model {
	protected $depth = 3;
	protected $defaultId = 0;
	public $table = 'category';

	public function select($cfg = array()) {
		$cfg['field'] = "`{$this->table}`.*,(SELECT COUNT(1) FROM `article` WHERE `category_id`=`{$this->table}`.`id`) AS `article_total`";
		
		return parent::select($cfg);
	}
	/*
	 * 查询单个分类信息
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function selectOne($id) {
		if(!is_numeric($id) || $id < 1)
			return array();
		
		$sql = "SELECT * FROM `{$this->table}` WHERE `id`={$id} LIMIT 1";
		$db = Loader::load('Database');
		$res = $db->query($sql);
		return $res[0];
	}
	/*
	 * 插入一个新分类
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function insert($data) {
		if(empty($data['name']))
			return false;
		
		$data['name'] = trim($data['name']);
		$data['description'] = trim($data['description']);
		$data['parent_id'] = (int)$data['parent_id'];
		//$sql = "INSERT INTO `{$this->table}` (`name`,`description`,`parent_id`,`depth`,`root_id`,`create_time`) VALUES ('{$data['name']}','{$data['description']}',".(0 >= $data['parent_id'] ? "0,1,0":"(SELECT * FROM (SELECT `id` FROM `{$this->table}` WHERE `id`={$data['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp1`),(SELECT * FROM (SELECT `depth`+1 FROM `{$this->table}` WHERE `id`={$data['parent_id']} LIMIT 1) AS `tmp2`),(SELECT IF(`tmp3`.`root_id`>0,`tmp3`.`root_id`,{$data['parent_id']}) FROM (SELECT `root_id` FROM `{$this->table}` WHERE `id`={$data['parent_id']} LIMIT 1) AS `tmp3`)").",'".date(DATE_FORMAT, $_SERVER['REQUEST_TIME'])."')";
		if(0 >= $data['parent_id']) {
			$sql = "INSERT INTO `{$this->table}` (`name`,`description`,`parent_id`,`depth`,`root_id`,`create_time`) VALUES ('{$data['name']}','{$data['description']}',0,1,0,'".date(DATE_FORMAT, $_SERVER['REQUEST_TIME'])."')";
		} else {
			$sql = "INSERT INTO `{$this->table}` (`name`,`description`,`parent_id`,`depth`,`root_id`,`create_time`) SELECT `tmp`.`name`,`tmp`.`description`,`tmp`.`parent_id`,`tmp`.`depth`,`tmp`.`root_id`,`tmp`.`create_time` FROM (SELECT '{$data['name']}' AS `name`,'{$data['description']}' AS `description`,{$data['parent_id']} AS `parent_id`,`depth`+1 AS `depth`,`root_id`,'".date(DATE_FORMAT, $_SERVER['REQUEST_TIME'])."' AS `create_time` FROM `{$this->table}` WHERE `id`={$data['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp`";
		}
		
		//echo $sql;
		$db = Loader::load('Database');
		$res = $db->execute($sql);
		if($res && $data['parent_id'] == 0) {
			$sql = "UPDATE `{$this->table}` SET `root_id`=`id` WHERE `id`={$res} LIMIT 1";
			$db->execute($sql);
		}
		return $res;
	}
	/*
	 * 更新一个分类
	 *
	 * @param array $cfg array('where'=>int,'data'=>array)
	 *
	 * @return int
	 */
	public function update($cfg=array()) {
		$id = (int)$cfg['where'];
		if($id <= 0 || empty($cfg['data']))
			return false;
		
		$limit = ' LIMIT 1';
		$sql = "UPDATE `{$this->table}` SET ";
		$tmp = '';
		if(!empty($cfg['data']['name']))
			$tmp .= ",`{$this->table}`.`name`='{$cfg['data']['name']}'";
		if(isset($cfg['data']['description']))
			$tmp .= ",`{$this->table}`.`description`='{$cfg['data']['description']}'";
		if(isset($cfg['data']['parent_id'])) {
			if($id == $cfg['data']['parent_id'])
				return false;
			if(0 ==$cfg['data']['parent_id'])
				$tmp .= ",`root_id`=`id`,`parent_id`=0,`depth`=1";
			else {
				$sql = "UPDATE `{$this->table}` LEFT JOIN (SELECT `root_id`,`depth`+1 AS `depth` FROM `{$this->table}` WHERE `id`={$cfg['data']['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp` ON 1=1 SET ";
				//$tmp .= ",`root_id`=(SELECT * FROM (SELECT `root_id` FROM `{$this->table}` WHERE `id`={$cfg['data']['parent_id']} LIMIT 1) AS `tmp1`),`parent_id`=(SELECT `id` FROM (SELECT `id` FROM `{$this->table}` WHERE `id`={$cfg['data']['parent_id']}".($this->depth >0?" AND `depth`<{$this->depth}":"")." LIMIT 1) AS `tmp2`),`depth`=(SELECT * FROM (SELECT `depth`+1 FROM `category` WHERE `id`={$cfg['data']['parent_id']} LIMIT 1) AS `tmp3`)";
				$tmp .= ",`{$this->table}`.`root_id`=`tmp`.`root_id`,`{$this->table}`.`parent_id`={$cfg['data']['parent_id']},`{$this->table}`.`depth`=`tmp`.`depth`";
				$limit = '';
			}
		}
		
		if(empty($tmp))
			return false;	
		$sql .= substr($tmp, 1)." WHERE `{$this->table}`.`id`={$id}{$limit}";
		
		$db = Loader::load('Database');
		return $db->execute($sql);
	}
	/*
	 * 删除一个/多个分类
	 *
	 * @param array $ids array/int/string
	 *
	 * @return int
	 */
	public function delete($ids = array()) {
		$count = 0;
		if(is_numeric($ids)) {
			$db = Loader::load('Database');
			$sql = "DELETE FROM `{$this->table}` WHERE `id`={$ids} LIMIT 1";
			$count += $db->execute($sql);
			$sql = "SELECT `id` FROM `{$this->table}` WHERE `parent_id`={$ids}";
			$child = $db->query($sql);
			if($child) {
				$sql = "DELETE FROM `{$this->table}` WHERE `parent_id`={$ids}";
				$count += $db->execute($sql);
				foreach($child as $v) {
					$count += $this->delete($v['id']);
				}
			}
		} else {
			if(is_string($ids))
				$ids = explode(',', $ids);
			
			while(list($k, $v) = each($ids)) {
				if(is_numeric($v))
					$count += $this->delete($v);
			}
		}
		return $count;
	}
	/*
	 * 修复分类
	 *
	 * @param int $rootId
	 *
	 * @return int
	 */
	public function fix($rootId = 0) {
		$count = 0;
		//修复根分类
		$db = Loader::load('Database');
		$sql = "SELECT `id` FROM `{$this->table}` WHERE `parent_id`={$rootId}";
		$child = $db->query($sql);
		$sql = "UPDATE `{$this->table}` SET `depth`=1,`root_id`=`id` WHERE `parent_id`={$rootId}";
		$res = $db->execute($sql);
		$count += $res;
		if(!empty($child)) {
			foreach($child as $v) {
				$count += $this->fixTree($v['id'], 2, $v['id']);
			}
		}
		return $count;
	}
	/*
	 * 修复分类树
	 *
	 * @param int $id
	 * @param int $depth
	 * @param int $rootId
	 *
	 * @return int
	 */
	private function fixTree($id = 0, $depth = 2, $rootId = 0) {
		if($this->depth !== 0 && $depth> $this->depth)
			return 0;
			
		$count = 0;
		$db = Loader::load('Database');
		$sql = "SELECT `id` FROM `{$this->table}` WHERE `parent_id`={$id}";
		$child = $db->query($sql);
		$sql = "UPDATE `{$this->table}` SET `depth`={$depth},`root_id`={$rootId} WHERE `parent_id`={$id}";
		$res = $db->execute($sql);
		$count += $res;
		if(!empty($child)) {
			$depth++;
			foreach($child as $v) {
				$count += $this->fixTree($v['id'], $depth, $rootId);
			}
		}
		return $count;
	}
	/*
	 * 生成分类树
	 *
	 * @param array $data 必须带parent_id信息
	 * @param int $rootId 根分类id/上级分类id
	 *
	 * @return array
	 */
	public static function makeTree($data, $rootId = 0) {
		$tree = array();
		while(list($k, $v) = each($data)) {
			if($v['parent_id'] == $rootId) {
				$tree[] = $v;
				unset($data[$k]);
			}
		}
		if($data && $tree) {
			while(list($k, $v) = each($tree)) {
				$tree[$k]['children'] = self::makeTree($data, $v['id']);
			}
		}
		return $tree;
	}
	/*
	 * 按层级(树)生成选择列表
	 *
	 * @param array $data 必须带parent_id信息
	 * @param int $rootId 根分类id/上级分类id
	 *
	 * @return array
	 */
	public static function makeSelectList($data, $rootId = 0) {
		$list = array();
		$children = array();
		
		while(list($k, $v) = each($data)) {
			if($v['parent_id'] == $rootId) {
				$list[] = $v;
				unset($data[$k]);
				$children = self::makeSelectList($data, $v['id']);
				if($children) {
					$list = array_merge($list, $children);
				}
			}
		}
		
		return $list;
	}
}
