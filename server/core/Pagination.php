<?php
/*
 * 设置分页显示
 * 作者：陈贵标
 * 邮箱：48838096@qq.com
 * 创建时间：2010/10/01
 * 修改时间：2012/06/02
 *
 * array('total'=>$this->vars['data']['total'],'size'=>$size, 'current'=>$current, 'uri'=>'/manage/index.php/article/')
 * array('total'=>$this->vars['data']['total'], 'size'=>$size, 'current'=>$current,'uri'=>'/manage/', 'is_query'=>true, 'query_flag'=>'pg')
 */

class Pagination {
	private $total = 0; //数据总数
	private $current = 0; //当前页
	private $max = 0; //页面总数
	private $size = 10; //每页显示数
	private $start = 0; //数据开始位置
	public $leftSize = 5; //左边显示页数
	public $rightSize = 3; //右边显示页数
	public $currentClass = 'current'; //但前页样式
	public $etcString = '<span class="etc">...</span>'; //隐藏内容html格式
	public $startText = '&lt;&lt;'; //第一页
	public $endText = '&gt;&gt;'; //最后页
	public $prevText = '&lt;'; //上一页
	public $nextText = '&gt;'; //下一页
	public $queryString = ''; //参数
	public $uri = '';
	public $style = 'default'; //分页样式
	
	/*
	 * 根据config初始化
	 *
	 * @param array $config array('total'=>int, 'size'=>int, 'current'=>int, 'uri'=>string, 'is_query' => boolean, 'page_flag' => string)
	 */
	function __construct($config = array()) {
		if(!empty($config))
			$this->init($config);
	}
	/*
	 * 根据config初始化
	 *
	 * @param array $config array('total'=>int, 'size'=>int, 'current'=>int, 'uri'=>string, 'is_query' => boolean, 'page_flag' => string)
	 */
	public function init($config = array()) {
		if($config) {
			//数据
			$this->total = (int)$config['total'];
			$this->size = (int)$config['size'];
			$this->current = (int)$config['current'];
			//跳转地址
			$this->uri = $config['uri'] or $this->uri = $_SERVER['REQUEST_URI'];
			if(!$config['is_query']) {
				if('/' != $this->uri{strlen($this->uri)-1})
					$this->uri .= '/';
				$this->queryString = '/'.($_SERVER['QUERY_STRING']?'?'.$_SERVER['QUERY_STRING']:'');
			} else {
				if(!empty($_SERVER['QUERY_STRING'])) {
					$querys = array();
					$res = explode('&',$_SERVER['QUERY_STRING']);
					//query_string去重，过滤page_flag
					foreach($res as $v) {
						$tmp = explode('=', $v);
						if(!empty($tmp[0]) && !isset($querys[$tmp[0]]) && $tmp[0] != $config['page_flag']) {
							$querys[$tmp[0]] = $tmp[0].'='.$tmp[1];
						}
					}
					if(!empty($querys))
						$this->queryString = '&'.implode('&', $querys);
				}
				
				$this->uri .= (false !== strpos($this->uri, '?') ? '&' : '?').$config['page_flag'].'=';
			}
		}
		
		if($this->size < 0)
			$this->size = 20;
		if($this->total > 0)
			$this->max = ceil($this->total/$this->size);
		else
			$this->max = 0;
		if($this->current < 1)
			$this->current = 1;
		else if($this->max > 0 && $this->current > $this->max)
			$this->current = $this->max;
		
		$this->start = ($this->current-1) * $this->size;
	}
	/*
	 * 设置总数
	 *
	 * @return int
	 */
	public function setTotal($total) {
		$this->total = $total;
		$this->init();
	}
	/*
	 * 获取总数
	 *
	 * @return int
	 */
	public function getTotal() {
		return $this->total;
	}
	/*
	 * 获取分页最大值
	 *
	 * @return int
	 */
	public function getMax() {
		return $this->max;	
	}
	/*
	 * 设置分页最大值
	 *
	 * @param int @max
	 */
	public function setMax($max = 0) {
		$this->max = $max;	
		$this->init();
	}
	/*
	 * 获取当前页
	 *
	 * @return int
	 */
	public function getCurrent() {
		return $this->current;	
	}
	/*
	 * 设置当前页
	 *
	 * @param int $current
	 */
	public function setCurrent($current = 0) {
		$this->current = $current;
		$this->init();
	}
	/*
	 * 获取当前页
	 *
	 * @return int
	 */
	public function getSize() {
		return $this->size;	
	}
	/*
	 * 设置当前页
	 *
	 * @param int $current
	 */
	public function setSize($size = 20) {
		$this->size = $size;
		$this->init();
	}
	/*
	 * 获取数据库查询开始位置
	 *
	 * @return int
	 */
	public function getStart() {
		return $this->start;
	}
	/*
	 * 显示分页页脚
	 *
	 * @return string
	 */
	public function get() {
		if($this->max < 2)
			return '';
			
		switch($this->style) {
			case 'mini':
				return $this->miniStyle();
			default:
				return $this->defaultStyle();	
		}
	}
	/*
	 * 显示分页页脚
	 *
	 * @return string
	 */
	private function defaultStyle() {
		$footer = '';
		
		//显示首页，前一页
		if($this->current > 1)
			$footer .= '<a href="' . $this->uri . '1'.$this->queryString.'" class="start" title="第一页">'.$this->startText.'</a><a href="' . $this->uri . ($this->current -1) .$this->queryString.'" class="prev" title="上一页">'.$this->prevText.'</a>';
		else
			$footer .= '<span class="start">'.$this->startText.'</span><span class="prev">'.$this->prevText.'</span>';
		
		//显示中间数字内容
		if($this->max <= ($this->leftSize + $this->rightSize)) {
			for($i = 1; $i <= $this->max; $i++) {
				if($i != $this->current)
					$footer .= '<a href="' . $this->uri . $i .$this->queryString.'" title="第' . $i . '页">' . $i . '</a>';
				else
					$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
			}
		} else {
			if($this->leftSize < 3)
				$this->leftSize = 3;
			if($this->rightSize < 2)
				$this->rightSize = 2;
			
			$leftMinSize = floor($this->leftSize/2);//左边显示最小值
			$leftLength = $this->max - $this->rightSize;//左边显示最大值
			$i = 0;
			$length = 0;
			//显示左边
			if($this->current <= $leftMinSize) {
				//如果当前页在最小范围内，显示从1开始
				//echo 'case 1';
				$i = 1;
				$length = $this->leftSize;
			} else if($this->current > ($leftLength - ($this->leftSize-$leftMinSize))) {
				//如果当前页靠后
				//echo 'case 2';
				$i = $leftLength - $this->leftSize+1;
				$length = $leftLength;
			} else {
				//如果当前页在中间位置
				//echo 'case 3';
				$i = $this->current-$leftMinSize;
				$length = $this->current-$leftMinSize+$this->leftSize-1;
			}
			for(; $i <= $length; $i++) {
				if($i == $this->current)
					$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
				else
					$footer .= '<a href="' . $this->uri . $i . $this->queryString.'" title="第' . $i . '页">' . $i . '</a>';
			}
			//显示更多
			$i = $leftLength+1;
			if($length < ($i-1))
				$footer .= $this->etcString;
			//显示右边
			$length = $this->max;
			for(; $i <= $length; $i++) {
				if($i == $this->current)
					$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
				else
					$footer .= '<a href="' . $this->uri . $i . $this->queryString.'" title="第' . $i . '页">' . $i . '</a>';
			}
		}
		
		//显示下一页，最后页
		if($this->current < $this->max)
			$footer .= '<a href="' . $this->uri . ($this->current + 1) . $this->queryString.'" class="next" title="下一页">' . $this->nextText . '</a><a href="' . $this->uri . $this->max . $this->queryString.'" class="end" title="最后页">'.$this->endText.'</a>';
		else
			$footer .= '<span class="next" title="下一页">' . $this->nextText . '</span><span class="end" title="最后页">' . $this->endText . '</span>';
		
		return $footer;
	}
	
	/*
	 * 显示分页页脚
	 *
	 * @return string
	 */
	private function miniStyle() {
		$footer = '';
		
		if($this->current >1)
			$footer .= '<a href="' . $this->queryString . $this->pageTag . '=1" class="start" title="第一页">'.$this->startText.'</a><a href="' . $this->queryString . $this->pageTag . '=' . ($this->current -1) . '" class="prev" title="上一页">'.$this->prevText.'</a>';
		else
			$footer .= '<span class="start">'.$this->startText.'</span><span class="prev">'.$this->prevText.'</span>';
		
		if($this->current < $this->max)
			$footer .= '<a href="' . $this->queryString . $this->pageTag . '=' . ($this->current + 1) . '" class="next" title="下一页">' . $this->nextText . '</a><a href="' . $this->queryString . $this->pageTag . '=' . $this->max . '" class="end" title="最后页">'.$this->endText.'</a>';
		else
			$footer .= '<span class="next" title="下一页">' . $this->nextText . '</span><span class="end" title="最后页">' . $this->endText . '</span>';
		
		return $footer;
	}
}
