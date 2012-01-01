<?php
/**
 * Controller
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-27 07:56:33
 */

class Util_Upload_Controller extends Qwin_Application_Controller
{
    public function actionIndex()
    {
        /*
         * $str = Qwin::call('Qwin_sanitise_String');
        // 是否有上传文件
        $name = 'userfile';
        !isset($_FILES[$name]) && exit('Forbidden');
        // 默认的上传路径
        $default_path = 'upload';

        // TODO安全性检查等
        // 获取想要上传到的目录名称
        $path = isset($_GET['path']) ? trim($_GET['path']) : 'upload';
        if(null != $path && is_dir($path) && $default_path == substr($path, 0, 6))
        {
            $path = $str->toPathSeparator($path);
            DS == substr($path, -1, 1) && $path = substr($path, 0, -1);
        } else {
            $path = $default_path;
        }

        // 加载上传类
        $upload = Qwin::call('Qwin_File_Upload');
        $upload->upload_form_field = $name;
        $upload->max_file_size = '10000000';
        $upload->make_script_safe = 1;
        $upload->allowed_file_ext = array(
            'gif', 'jpg', 'jpeg', 'png', 'bmp',
        );

        // 获取上传路径
        $upload->out_file_dir = $path;
        //$upload->out_file_name = time();
        $upload->upload_process();

        switch($upload->error_no)
        {
            case 0 :
                $error_msg = '文件上传成功!';
                break;
            case 1 :
                $error_msg = '没有文件被上传!';
                break;
            case 2 :
            case 5 :
                $error_msg = '非法文件扩展名!';
                break;
            case 3 :
                $error_msg = '文件大小超过限制!';
                break;
            case 4 :
                $error_msg = '无法移动的上传文件!';
                break;
            default :

        }


        $arr = array(
            'file_name' => iconv('GBK', 'UTF-8', $str->toUrlSeparator($upload->saved_upload_name)),
            //'path' => Qwin::call('-str')->toUrlSeparator($upload->out_file_dir),
            'error' => array(
                'num' => $upload->error_no,
                'msg' => $error_msg,
            )
        );
        echo Qwin::call('-arr')->jsonEncode($arr, 'pear');
         */
    }
}
