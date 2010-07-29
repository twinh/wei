<?php
/**
 * arraycache 的名称
 *
 * arraycache 的简要介绍
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
 * @version   2009-02-18 00:00:00 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 * @todo      去除通用分类缓存(因为已有),作为针对一般数据型表的缓存
 */

class ArrayCache
{
	public $cache_path;
	// list cache path
	public $list_cache_path = 'list';
	// common class cache path
	public $cc_cache_path = 'common_class';
	// 每个 cache 文件中数组的键数
	public $num_per_file;
	// 数据表
	public $table = 'common_class';
	// 数据库操作
	public $db;
	
	function __construct()
	{		
		$this->cache_path = ROOT_PATH . '/cache/php';
		$this->table = 'common_class';
		//$this->utf8_sign = '/*+----- utf-8 编码 -----+*/';
		// 默认10
		$this->num_per_file = 10;
	}
	


	
	//------------------------------
	// 按 id 号, 指定数目存入文件
	//------------------------------
	/*
		$aSetting = array
		(
			'cPageName' => 'common_setting', // 页面名称,生成的文件名称,变量名称(必需)
			'cTable' => '', // 对应的数据表,留空则使用 cPageName
			'id' => '5', // 当前id(必需)
			'num_per_file' => '2', // 每文件存储数组数目
			'aExpectKey' => array('iStateCodeCc', 'id'), // 不写入文件的键名
		);
	*/
	function setCacheById($aSetting)
	{
		// 便于引用
		$this->common = new Common();
		$aQuery = $data = array();
		// 数据表
		$this->common->db_table = $aSetting['cTable'] ? $aSetting['cTable'] : $aSetting['cPageName'];
		// 每页数目
		$aSetting['num_per_file'] && $this->num_per_file = $aSetting['num_per_file'];
		
		// 查找当前 id 在数据库中的位置
		$idPosition = $this->getIdPosition($aSetting);
		// 获得当前 id 对应的文件名编号
		$iPageNum = ceil($idPosition / $this->num_per_file);
		// 转换为 sql LIMIT 语句
		$aQuery = array
		(
			'LIMIT' => ($iPageNum - 1) * $this->num_per_file . ", " . $this->num_per_file,
		);
		$data = $this->common->getList($aQuery);
		// 删除指定键名
		if(is_array($aSetting['aExpectKey']))
		{
			$data = $this->unsetKey($data, $aSetting['aExpectKey']);
		}
		$cData = ExArray::toPhpCode($data);
		// 构造路径,包括索引页(index.php)和当前页(index_*.php);
		$cPath = $this->cache_path . '/' . $this->list_cache_path . '/' . $aSetting['cPageName'] . '/';
		// 建立文件夹
		$this->setPath($cPath);
		$cInexPath = $cPath . 'index.php';
		
		$cCachePath = $cPath . 'index_' . $iPageNum . '.php';
		// 更新 cache 文件
		QFile::write($cCachePath, 
						 "<?php\r\n\$" . $aSetting['cPageName'] . "Ac = " . $cData . ";\r\n?>");
		// 更新索引
		$this->updateListIndex($aSetting, $cInexPath);
	}
	
	// 更新索引
	private function updateListIndex($aSetting, $cInexPath)
	{
		$aQuery = $aDbData = $data = $data_set = array();
		
		$aQuery = array
		(
			'SELECT' => "id",
		);
		$aDbData = $this->common->getList($aQuery);
		$i = 1;
		foreach($aDbData as $val)
		{
			// id 作为 key 便于索引
			$data[$val['id']] = $i;
			$i++;
		}
		// 各细节设定
		$data_set['cVarName'] = $aSetting['cPageName'];
		$data_set['iCount'] = $i;
		$data_set['num_per_file'] = $this->num_per_file;
		$cDataSet = ExArray::toPhpCode($data_set);
		$cData = ExArray::toPhpCode($data);	
		
		QFile::write($cInexPath, 
						 "<?php\r\n". $this->utf8_sign ."\r\n\$" .
						 $aSetting['cPageName'] . "AcSet = " . $cDataSet . ";\r\n\$" .
						 $aSetting['cPageName'] . "AcIndex = " . $cData . ";\r\n?>");
	}
	
	/*
		$aSetting = array
		(
			'cPageName' => 'common_setting', // 页面名称,生成的文件名称,变量名称(必需)
			'id' => '5', // 要查找的id(二选一)
			'iPageNum' => // 指定某编号, 0 表示首页, 如果未设置,则按id查找(二选一)
		);
	*/
	// 获取 id 路径
	function getIdPath($aSetting)
	{
		$cPath = $this->cache_path . '/' . $this->list_cache_path . '/' . $aSetting['cPageName'] . '/';
		
		if(isset($aSetting['iPageNum']))
		{
			if($aSetting['iPageNum'] == 0)
			{
				$cPathPart = 'index.php';
			} else {
				$cPathPart = 'index_' . $aSetting['iPageNum'] . '.php';
			}
		} else {
			require $cPath . 'index.php';
			$aSet = &${$aSetting['cPageName'] . 'AcSet'};
			$aIndex = &${$aSetting['cPageName'] . 'AcIndex'};
			$iPageNum = ceil($aIndex[$aSetting['id']] / $aSet['num_per_file']);
			$cPathPart = 'index_' . $iPageNum . '.php';
		}
		$cPath .= $cPathPart;
		
		return $cPath;
	}
		
	// 查找当前 id 在数据库中的位置
	function getIdPosition($aSetting)
	{
		$aQuery = array
		(
			'WHERE' => "AND id <= '$aSetting[id]'",
		);
		$idPosition = $this->common->getListNum($aQuery);

		return $idPosition;
	}	
}
