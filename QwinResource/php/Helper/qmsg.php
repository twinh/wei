<?php
/**
 * qmsg 的名称
 *
 * qmsg 的简要介绍
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
 * @version   2009-10-31 01:19:05
 * @since     2009-11-24 20:45:11
 */

/*---- utf-8编码 ----*/
// 0910
class QMsg
{
	function show($msg, $method = '')
	{
		$str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>' . $msg . '</title>
<link type="text/css" href="css/css.css" rel="stylesheet" />
</head>
<body>';
		$msg = str_replace(array('"'), array('\"'), $msg);
		$str .= '<script type="text/javascript">alert("' . $msg . '");';
		switch($method)
		{
			case '' :
			case 'goback' :
				$str .= 'history.go(-1);';
				break;
			case 'close' :
				$str .= 'window.close();';
				break;
			case '' :
				break;
			default :
				$str .= 'window.location.href="' . $method . '";';
				break;
		}
		$str .= '</script>';
		$str .= '</body></html>';
		echo $str;
		exit;
	}
	
	// 文件不存在
	function fileError($path, $type)
	{
		switch($type)
		{
			
		}
	}
}
?>
