<?php
/**
 * validation 的名称
 *
 * validation 的简要介绍
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
 * @version   2009-10-31 01:19:04 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 */

/*
+--------------------------------------------------------------------------------
|
|	@abstract	简单检查
|	@author		twin
|	@created	2009-03-07
|	@version	2009-10-03
|	
+--------------------------------------------------------------------------------
*/
class Validation
{
	public static $msg;
	
	
	// 去除空格
	function toTrim($str)
	{
		$str = trim($str);
		if($str == '')
		{
			
		} else {
			return $str;
		}
	}
	
	function isNumber($qData)
	{
		$bool = is_numeric($qData);
		$bool == false && self::$msg = '必需为数字';
		
		return $bool;
	}
	
	function isNotBlank($str)
	{
		$bool = trim($str) != '' ? true : false;
		$bool == false && self::$msg = '不能为空';
		
		return $bool;
	}
	
	// 邮箱
	function isEmail($str)
	{
		$bool = ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $str);
		$bool == false && self::$msg = '不是合法的邮箱格式';
		
		return $bool;
	}
	
	// 是否为正数
	function isPositiveNumber($str)
	{
		$bool = is_numeric($str) && $str > 0;
		$bool == false && self::$msg = '应为正数';
		
		return $bool;
	}
	
	// 是否为数据库 id (空/正数)
	function isSqlId($str)
	{
		$bool = true;
		
		if($str != '')
		{
			$bool = self::isPositiveNumber($str);
		}
		
		return $bool;
	}
	
	// 是否在数组中
	function isInArray($str, $arr, $type = 'KEY')
	{
		switch($type)
		{
			case 'ALL'		:
				$bool = in_array($str, $arr);
				if($bool == true)
				{
					break;
				}
			case 'KEY'		:
				$bool = array_key_exists($str, $arr);
				break;
			case 'VALUE'	:
			default			:
				$bool = in_array($str, $arr);
				break;
		}
		$bool == false && self::$msg = '不在规定的范围内';
		
		return $bool;
	}
	
	function isLength($str, $num)
	{
		$bool = strlen($str) == $num;
		$bool == false && self::$msg = '长度应为 ' . $num . ' 个字节';
		
		return $bool;
	}
	
	// 是否在指定长度之间
	function isLengthBetween($str, $min, $max)
	{
		$bool = self::isLonger($str, $min) && self::isShorter($str, $max);
		$bool == false && self::$msg = '长度应控制在 ' . $min . '-' . $max . ' 字节以内';
		
		return $bool; 
	}
	
	// 是否长于
	function isLonger($str, $num)
	{
		$bool = strlen($str) >= $num;
		$bool == false && self::$msg = '长度应大于等于 ' . $num . ' 个字节';
		
		return $bool;
	}
	
	// 是否短于
	function isShorter($str, $num)
	{
		$bool = strlen($str) <= $num;
		$bool == false && self::$msg = '长度应小于等于 ' . $num . ' 个字节';
		
		return $bool;
	}
	
	// $cResource 格式检查 合法的格式有 5(单独数字), 5-10(5到10), 5-n(5到无穷大), n-5(无穷小到5) 
	function autoLength($cStr, $cResource)
	{
		$bool = strpos($cResource, '-');
		if($bool == true)
		{
			$aData = explode('-', $cResource);
			if($aData[0] != 'n')
			{
				if($aData[1] != 'n')
				{
					$bCheck = self::isBetween($cStr, $aData[0], $aData[1]);
				} else {
					$bCheck = self::isLonger($cStr, $aData[0]);
				}
			} else {
				$bCheck = self::isShorter($cStr, $aData[1]);
			}
		} else {
			// 确定的长度值
			$bCheck = self::isLength($cStr, $cResource);
		}
		
		return $bCheck;
	}
	
	function isSesstionCode($value, $name)
	{
		$bool = $_SESSION[$name] == $value;
		$bool == false && self::$msg = 'Session 代码不正确';
		return $bool;
	}
	
	function isNot0($value)
	{
		if($value == 0)
		{
			$bool = false;
			self::$msg = '不能为空';
		} else {
			$bool = true;
		}
		return $bool;
	}
	
	/**
	 * @todo \w 作为正则式
	 */
	function isSimpleCode($str)
	{
		$bool = ereg("^[0-9a-zA-Z\_]*$", $str);
		$bool == false && self::$msg = '只能由字母,数字,下划线组成';
		
		return $bool;
	}
}
?>