<?php
/**
 * Alert
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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-07 15:03:26
 */

class Qwin_Application_View_Alert extends Qwin_Application_View
{
    /**
     * 显示js对话框视图
     *
     * @return object 当前对象
     */
    public function display()
    {
        $data = $this->_data;
        $message = str_replace(array('"'), array('\"'), $data['message']);

        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>' . $message . '</title>
</head>
<body>';
        
        echo '<script type="text/javascript">alert("' . $message . '");';
        switch($data['method'])
        {
            case null :
            case '' :
            case 'goback' :
                echo 'history.go(-1);';
                break;
            case 'close' :
                echo 'window.close();';
                break;
            default :
                echo 'window.location.href="' . $data['method'] . '";';
                break;
        }
        echo '</script>';
        echo '</body></html>';

        return $this;
    }
}
