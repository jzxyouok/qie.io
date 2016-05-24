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
	private $leftSize = 5; //左边显示页数
	private $rightSize = 2; //右边显示页数
	private $onStyle = 'on'; //但前页样式
	private $etc = '<span class="etc">...</span>'; //缩略样式
	private $query = ''; //参数
	private $goStart = '&lt;&lt;'; //第一页
	private $goEnd = '&gt;&gt;'; //最后页
	private $goPrev = '&lt;'; //上一页
	private $goNext = '&gt;'; //下一页
	private $style = 'default'; //分页样式
	private $uri = '';
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
		
		if($this->current < 1) $this->current = 1;
		if($this->max > 0 && $this->current >= $this->max) $this->current = $this->max;
		
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
	 * 获取当前页面查询参数
	 *
	 * @return string
	 */
	public function getQuery() {
		return $this->query;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setLeftSize($size = 3) {
		return $this->leftSize = (int)$size;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setRightSize($size = 3) {
		return $this->rightSize = (int)$size;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setGoStart($go = '') {
		return $this->goStart = $go;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setGoEnd($go = '') {
		return $this->goEnd = $go;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setGoPrev($go = '') {
		return $this->goPrev = $go;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setGoNext($go = '') {
		return $this->goNext = $go;	
	}
	/*
	 * 设置分页样式
	 *
	 * @param string $style {default,mini}
	 *
	 * @return string
	 */
	public function setStyle($style = 'default') {
		return $this->style = $style;	
	}
	public function setEtc($e) {
		$this->etc = $e;	
	}
	public function setPageTag($p) {
		$this->pageTag = $p;	
	}
	public function setOnStyle($o) {
		$this->onStyle = $o;	
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
			case 'default':
				return $this->defaultStyle();
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
		if($this->current > 1) $footer .= '<a href="' . $this->uri . '1'.$this->query.'" title="第一页" class="start">'.$this->goStart.'</a><a href="' . $this->uri . ($this->current -1) .$this->query.'" title="上一页" class="prev">'.$this->goPrev.'</a>';
			else $footer .= '<span class="start">'.$this->goStart.'</span><span class="prev">'.$this->goPrev.'</span>';
			
		if($this->max <= ($this->leftSize + $this->rightSize)) {
			for($i = 1; $i <= $this->max; $i++)
				if($i == $this->current)
					$footer .= '<strong title="第' . $i . '页" class="' . $this->onStyle . '">' . $i . '</strong>';
				else
					$footer .= '<a href="' . $this->uri . $i .$this->query.'" title="第' . $i . '页">' . $i . '</a>';
		} else {
			if($this->leftSize <=2) $this->leftSize = 3;
			$left_min_size = floor($this->leftSize/2);//左边显示最小值
			$left_max_size = $this->max - $this->rightSize;//左边显示最大值
			//显示左边
			if($this->current <= $left_min_size) //如果当前页在最小范围内，显示从1开始
				for($i = 1; $i <= $this->leftSize; $i++)
					if($i == $this->current)
						$footer .= '<strong title="第' . $i . '页" class="' . $this->onStyle . '">' . $i . '</strong>';
					else
						$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
			else if($this->current < ($this->leftSize%2 == 1 ? $left_max_size-$left_min_size : $left_max_size-$left_min_size +1 ))//如果当前页在中间位置
				for($i = $this->current-$left_min_size; $i <= $this->current-$left_min_size+$this->leftSize-1; $i++)
					if($i == $this->current)
						$footer .= '<strong title="第' . $i . '页" class="' . $this->onStyle . '">' . $i . '</strong>';
					else
						$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
			else { //如果当前页靠后
				for($i = $this->max-$this->rightSize-$this->leftSize+1; $i <= $left_max_size; $i++)
					if($i == $this->current)
						$footer .= '<strong title="第' . $i . '页" class="' . $this->onStyle . '">' . $i . '</strong>';
					else
						$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
					
				$show_etc = false; //不显示更多
			}
			//显示更多
			if($show_etc) $footer .= $this->etc;
			//显示右边
			for($i = $this->max-$this->rightSize+1; $i <= $this->max; $i++)
				if($i == $this->current)
					$footer .= '<strong title="第' . $i . '页" class="' . $this->onStyle . '">' . $i . '</strong>';
				else
					$footer .= '<a href="' . $this->uri . $i . $this->query.'" title="第' . $i . '页">' . $i . '</a>';
		}
		//显示下一页，最后页
		if($this->current < $this->max) $footer .= '<a href="' . $this->uri . ($this->current + 1) . $this->query.'" title="下一页" class="next">' . $this->goNext . '</a><a href="' . $this->uri . $this->max . $this->query.'" title="最后页" class="end">'.$this->goEnd.'</a>';
		else $footer .= '<span title="下一页" class="next">' . $this->goNext . '</span><span title="最后页" class="end">' . $this->goEnd . '</span>';
		
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
			$footer .= '<span class="start">'.$this->goStart.'</span><span class="prev">'.$this->goPrev.'</span>';
		else
			$footer .= '<a href="' . $this->query . $this->pageTag . '=1" title="第一页" class="start">'.$this->goStart.'</a><a href="' . $this->query . $this->pageTag . '=' . ($this->current -1) . '" title="上一页" class="prev">'.$this->goPrev.'</a>';
		
		if($this->current < $this->max)
			$footer .= '<a href="' . $this->query . $this->pageTag . '=' . ($this->current + 1) . '" title="下一页" class="next">' . $this->goNext . '</a><a href="' . $this->query . $this->pageTag . '=' . $this->max . '" title="最后页" class="end">'.$this->goEnd.'</a>';
		else
			$footer .= '<span title="下一页" class="next">' . $this->goNext . '</span><span title="最后页" class="end">' . $this->goEnd . '</span>';
		
		return $footer;
	}
}
