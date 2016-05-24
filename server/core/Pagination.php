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
	public $rightSize = 2; //右边显示页数
	public $currentClass = 'on'; //但前页样式
	public $etcString = '<span class="etc">...</span>'; //隐藏内容html格式
	public $startText = '&lt;&lt;'; //第一页
	public $endText = '&gt;&gt;'; //最后页
	public $prevText = '&lt;'; //上一页
	public $nextText = '&gt;'; //下一页
	public $query = ''; //参数
	public $uri = '';
	public $style = 'default'; //分页样式
	//private $isQuery = false;
	
	/*
	 * @param int $total 总数
	 * @param int $size 每页显示多少
	 * @param int $current 当前页数
	 * @param int $page 获取页数的参数，即?page=
	 */
	function __construct($config = array()) {
		if(!empty($config))
			$this->init($config);
	}
	public function init($config = array()) {
		if($config) {
			$this->total = $config['total'];
			$this->size = $config['size'];
			$this->current = $config['current'];
			$this->uri = $config['uri'] or $this->uri = $_SERVER['REQUEST_URI'];
			//$config['is_query'] = true;
			//$config['query_flag'] = 'pg';
			//$config['uri'] = '/abc.php';
			if(!$config['is_query']) {
				if('/' != $this->uri{strlen($this->uri)-1})
					$this->uri .= '/';
				$this->query = '/'.($_SERVER['QUERY_STRING']?'?'.$_SERVER['QUERY_STRING']:'');
			} else {
				if(!empty($_SERVER['QUERY_STRING'])) {
					$this->query = array();
					$querys = explode('&',$_SERVER['QUERY_STRING']);
					foreach($querys as $v) {
						$tmp = explode('=', $v);
						if(!empty($tmp[0]) && !isset($this->query[$tmp[0]]) && $tmp[0] != $config['query_flag']) {
							$this->query[$tmp[0]] = $tmp[0].'='.$tmp[1];
						}
					}
					if(!empty($this->query))
						$this->query = '&'.implode('&', $this->query);
					else
						$this->query = '';
				}
					
				$this->uri .= (false !== strpos($this->uri, '?') ? '&' : '?').$config['query_flag'].'=';
			}
		}
		
		if(empty($this->size))
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
		
		$show_etc = true;//是否显示更多
		$footer = '';
		
		//显示首页，前一页
		if($this->current > 1)
			$footer .= '<a href="' . $this->uri . '1'.$this->query.'" class="start title="第一页"">'.$this->startText.'</a><a href="' . $this->uri . ($this->current -1) .$this->query.'" class="prev title="上一页"">'.$this->prevText.'</a>';
		else
			$footer .= '<span class="start">'.$this->startText.'</span><span class="prev">'.$this->prevText.'</span>';
		
		//显示中间数字内容
		if($this->max <= ($this->leftSize + $this->rightSize)) {
			for($i = 1; $i <= $this->max; $i++) {
				if($i != $this->current)
					$footer .= '<a href="' . $this->uri . $i .$this->query.'" title="第' . $i . '页">' . $i . '</a>';
				else
					$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
			}
		} else {
			if($this->leftSize <=2)
				$this->leftSize = 3;
			$leftMinSize = floor($this->leftSize/2);//左边显示最小值
			$leftMaxSize = $this->max - $this->rightSize;//左边显示最大值
			//显示左边
			if($this->current <= $leftMinSize) {
				//如果当前页在最小范围内，显示从1开始
				for($i = 1; $i <= $this->leftSize; $i++)
					if($i == $this->current)
						$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
					else
						$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
			} else if($this->current < ($this->leftSize%2 == 1 ? $leftMaxSize-$leftMinSize : $leftMaxSize-$leftMinSize +1 )) {
				//如果当前页在中间位置
				for($i = ($this->current-$leftMinSize); $i <= ($this->current-$leftMinSize+$this->leftSize-1); $i++)
					if($i == $this->current)
						$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
					else
						$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
			} else {
				//如果当前页靠后
				for($i = $this->max-$this->rightSize-$this->leftSize+1; $i <= $leftMaxSize; $i++)
					if($i == $this->current)
						$footer .= '<span class="' . $this->currentClass . '" title="第' . $i . '页">' . $i . '</span>';
					else
						$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
					
				$show_etc = false; //不显示更多
			}
			//显示更多
			if($show_etc)
				$footer .= $this->etcString;
			//显示右边
			for($i = $this->max-$this->rightSize+1; $i <= $this->max; $i++) {
				if($i == $this->current)
					$footer .= '<span title="第' . $i . '页" class="' . $this->currentClass . '">' . $i . '</span>';
				else
					$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
			}
		}
		
		//显示下一页，最后页
		if($this->current < $this->max)
			$footer .= '<a href="' . $this->uri . ($this->current + 1) . $this->query.'" class="next" title="下一页">' . $this->nextText . '</a><a href="' . $this->uri . $this->max . $this->query.'" class="end" title="最后页">'.$this->endText.'</a>';
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
		
		if($this->current < 2)
			$footer .= '<span class="start">'.$this->startText.'</span><span class="prev">'.$this->prevText.'</span>';
		else
			$footer .= '<a href="' . $this->query . $this->pageTag . '=1" title="第一页" class="start">'.$this->startText.'</a><a href="' . $this->query . $this->pageTag . '=' . ($this->current -1) . '" title="上一页" class="prev">'.$this->prevText.'</a>';
		
		if($this->current < $this->max)
			$footer .= '<a href="' . $this->query . $this->pageTag . '=' . ($this->current + 1) . '" title="下一页" class="next">' . $this->nextText . '</a><a href="' . $this->query . $this->pageTag . '=' . $this->max . '" title="最后页" class="end">'.$this->endText.'</a>';
		else
			$footer .= '<span title="下一页" class="next">' . $this->nextText . '</span><span title="最后页" class="end">' . $this->endText . '</span>';
		
		return $footer;
	}
}
