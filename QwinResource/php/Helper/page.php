<?php
 /**
 * 分页
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-03-08 utf-8 中文
 * @since     2009-11-21 17:25 utf-8 中文
 */

class Page
{
	// 分页名称
	//public $query_name;
	// number of per page
	public $rowNum;
	// 初始页面
	public $start_page;
	
	// 总页数
	private $total_page;
	
	function __construct($name = 'page')
	{
		$this->query_name = $name;
		$this->now_page = qw('-ini')->g($name);
		// 检查 id
		$this->checkPageId();
		$this->rowNum = 10;
	}
	
	
	// 总数
	// TODO $num, style, tpl
	function getPage($num)
	{
		$this->getPageUrl();
		if($num <= 1 || !is_numeric($this->now_page)){
			return '';
		} else{
			$num = ceil($num / $this->rowNum);
			$pages = '<a href="' . $this->page_url . '1' . qw('-url')->$extension . '">首页</a>';
			if($this->now_page != 1){
				$prev = $this->now_page - 1;
				$pages .= '<a href="' . $this->page_url . $prev  . qw('-url')->$extension . '">上一页</a>';
			}
			if($this->now_page != $num){
				$next = $this->now_page + 1;
				$pages .= '<a href="' . $this->page_url . $next  . qw('-url')->$extension . '">下一页</a>';
			}
			$pages .= '<a href="' . $this->page_url . $num  . qw('-url')->$extension . '">末页</a> 当前第 ' . $this->now_page . '/' . $num . ' 页 ';
			$pages .= '<input type="text" name="gotopage" id="gotopage" ';
			$pages .= 'onkeydown="javascript: if(event.keyCode==13){ location=\'' . $this->page_url . '\'+document.getElementById(\'gotopage\').value+\'' . qw('-url')->$extension . '\'; }"/>';
			$pages .= '<input type="button" class="button_gotopage" value="跳转" onclick="location=\'' . $this->page_url . '\'+document.getElementById(\'gotopage\').value+\'' . qw('-url')->$extension . '\';" />';
			return $pages;
		}
	}
	
	function getDefaultPage($num)
	{
		$this->getPageUrl();
		$this->getTotalPage($num);
		
		$html = '<div class="page">';
		// 超出页面
		if($this->now_page > $this->total_page)
		{
			QMsg::show('请求的数据不存在或已删除!');
		}
		// 仅有一页
		if($num <= 1)
		{
			return '';
		} else{
			$html .= $this->now_page . '/' . $this->total_page . '页&nbsp;|&nbsp;<a href="' . $this->page_url . '1">首页</a>&nbsp;&lt;&nbsp;<a href="';
			if($this->now_page != 1)
			{
				$prev = $this->now_page - 1;
				$html .= $this->page_url . $prev;
			} else {
				$html .= '#';
			}
			$html .= '">前一页</a>&nbsp;|&nbsp;<a href="';
			if($this->now_page != $this->total_page)
			{
				$next = $this->now_page + 1;
				$html .= $this->page_url . $next;
			} else {
				$html .= '#';
			}
			$html .= '">后一页</a>&nbsp;&gt;&nbsp;|&nbsp;<a href="' . $this->page_url . $this->total_page . '">末页</a>';
		}
		$html .= '</div>';
		
		return $html;
	}
	
	function getTotalPage($num)
	{
		$this->total_page = ceil($num / $this->rowNum);
		// 当数据表没有记录的时候
		$this->total_page == 0 && $this->total_page = 1;
	}
	
	// 获取页面 url
	function getPageUrl()
	{
		$get = qw('-url')->getGetArray();
		unset($get[$this->query_name]);
		if(count($get) != 0)
		{
			$query = qw('-url')-> arrayKey2Url($get);
		}
		
		$this->page_url .=  '?' . $query . qw('-url')->$separator[0] .  $this->query_name . qw('-url')->$separator[1];
	}
	
	// 将数组转换为 url 中?之后的 query 形式
	function array2url($data, $array_name = null)
	{
		$query = '';
		
		foreach($data as $key => $val)
		{
			if(is_array($val))
			{
				// 多维数组
				if($array_name)
				{
					$key = $array_name . '%5B' . $key . '%5D';
				}
				$query .= $this->array2url($val, $key);
			} else {
				if($array_name)
				{
					$query .= $array_name . '%5B' . $key . '%5D';
				} else {
					$query .= $key;
				}
				$query .= '=' . $val . '&';
			}
		}
		
		return $query;
	}
	
	function getPageNum()
	{
		//if()
	}
	
	// 查询数据库限制语句
	function getSqlLimit()
	{
		$start_limit = ($this->now_page - 1) * $this->rowNum;
		$sqlLimit = "$start_limit, $this->rowNum";
		return $sqlLimit;
	}
	
	function checkPageId()
	{
		global $system;
		
		$this->now_page = intval($this->now_page);
		!$this->now_page && $this->now_page = 1;
		if(!is_numeric($this->now_page) || $this->now_page < 1)
		{
			$system->showMsg('请求数据有误,请检查!');
		}
	}
}
