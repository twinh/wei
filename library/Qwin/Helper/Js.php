<?php
/**
 * Js
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Qwin
 * @subpackage  Hepler
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Helper_Js
{
    function show($msg, $method = '')
    {
        $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
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
