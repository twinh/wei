<?php
/**
 * Jquery
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-5-27 7:56:33 utf-8 中文
 * @since     2010-5-27 7:56:33 utf-8 中文
 */

class Api_File_Controller_Jquery extends Qwin_Trex_Controller
{
    public function actionAjaxUpload()
    {
        $gpc = Qwin::run('-gpc');
        $str = Qwin::run('Qwin_converter_String');
        // 是否有上传文件
        $name = 'userfile';
        !isset($_FILES[$name]) && exit('Forbidden');
        // 默认的上传路径
        $default_path = 'Public/upload';

        // TODO安全性检查等
        // 获取想要上传到的目录名称
        $path = trim($gpc->g('path'));
        if(null != $path && file_exists($path) && $default_path == substr($path, 0, 13))
        {
            $path = $str->toPathSeparator($path);
            DS == substr($path, -1, 1) && $path = substr($path, 0, -1);
        } else {
            $path = $default_path;
        }

        // 加载上传类
        $upload = Qwin::run('Qwin_File_Upload');
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
            //'path' => Qwin::run('-str')->toUrlSeparator($upload->out_file_dir),
            'error' => array(
                'num' => $upload->error_no,
                'msg' => $error_msg,
            )
        );
        echo Qwin::run('-arr')->jsonEncode($arr, 'pear');
    }

    public function actionFileTree()
    {
        //
        // jQuery File Tree PHP Connector
        //
        // Version 1.01
        //
        // Cory S.N. LaViska
        // A Beautiful Site (http://abeautifulsite.net/)
        // 24 March 2008
        //
        // History:
        //
        // 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
        // 1.00 - released (24 March 2008)
        //
        // Output a list of files for jQuery File Tree
        //
        // Fixed By Twin 2009-04-04
        // 修改 htmlentities 为 htmlspecialchars
        // 添加 iconv 转换为utf-8 简体中文
        $dir = urldecode($_POST['dir']);
        $root = '';
        //$dir = urldecode($_GET['dir']);
        // check
        if(substr($dir, 0, 7) != 'Public/')
        {
            exit('Forbidden');
        }

        if( file_exists($root . $dir) )
        {
            $files = scandir($root . $dir);
            natcasesort($files);
            if( count($files) > 2 )
            { /* The 2 accounts for . and .. */
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach( $files as $file ) {
                    if(file_exists($root . $dir . $file) &&
                        $file != '.' &&
                        $file != '..' &&
                        is_dir($root . $dir . $file) &&
                        substr($file, 0, 1) != '.' &&
                        substr($file, 0, 1) != '_'
                    ){
                        $file = iconv("GBK", "UTF-8", $file);
                        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlspecialchars($dir . $file) . "/\">" . htmlspecialchars($file) . "</a></li>";
                    }
                }
                // All files
                foreach( $files as $file ) {
                    if(
                        file_exists($root . $dir . $file) &&
                        $file != '.' &&
                        $file != '..' &&
                        !is_dir($root . $dir . $file) &&
                        substr($file, 0, 1) != '.' &&
                        substr($file, 0, 1) != '_'
                    ){
                        $ext = preg_replace('/^.*\./', '', $file);
                        $ext = strtolower($ext);
                        $file = iconv("GBK", "UTF-8", $file);
                        echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlspecialchars($dir . $file) . "\">" . htmlspecialchars($file) . "</a></li>";
                    }
                }
                echo "</ul>";
            }
        }
    }
}
