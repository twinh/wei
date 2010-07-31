<?php
 /**
 * 文件操作
 *
 * 文件操作后台控制器,包括上传,显示文件树,主要服务于 JQuery 插件
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
 * @version   2009-11-21 13:14
 * @since     2009-11-21 13:14
 * @todo      将文件操作分离开至 JQuery 插件中,或者分出接口
 */

class Controller_Admin_File extends QW_Controller
{
    function actionUpload()
    {
        $name = qw('-ini')->g('name');
        if($_FILES[$name])
        {
            qw('-ini')->loadClass(array('upload', 'file', 'resource'));
            $upload = new Upload();
            $upload->upload_form_field = $name;
            $upload->out_file_dir = 'public\upload';
            $upload->max_file_size = '10000000';
            $upload->make_script_safe = 1;
            $upload->allowed_file_ext = array(
                'gif', 'jpg', 'jpeg', 'png', 'bmp',
            );
            //$upload->out_file_name = time();
            $upload->upload_process();

            $saved_upload_name =  str_replace("\\", "/", $upload->saved_upload_name);
            $saved_upload_name =  str_replace("'", "\'", $saved_upload_name);
            $saved_upload_name = iconv('GBK', 'UTF-8', $saved_upload_name);
            echo '<script type=text/javascript>window.parent.qUploadCallback(' . $upload->error_no . ', \'' . $saved_upload_name . '\');</script>';
        }
    }

    function actionThumb()
    {
        // todo 更多检验
        $file = qw('-ini')->g('url');
        if(!file_exists($file))
        {
            $arr = array(
                'error' => 1,
                'msg' => '文件不存在!',
            );
        } elseif(!is_array(getimagesize($file))){
            $arr = array(
                'error' => 2,
                'msg' => '文件不是合法图片!',
            );
        } else {
            $width = qw('-ini')->g('width');
            $heigth = qw('-ini')->g('heigth');
            qw('-ini')->loadClass(array('pthumb', 'file', 'resource'));
            $thumb = new pThumb();
            $thumb->pSetWidth($width);
            $thumb->pSetHeight($heigth);
            $thumb->pSetQuality(100);
            $thumb->pCreate($file);

            $part = pathinfo ($file);
            $thumb_file = qw('-str')->toPathSeparator($part['dirname'] . DS . '_thumb' . DS . $part['filename'] . '_' . $width . '_' . $heigth . '.' . $part['extension']);
            $thumb->pSave($thumb_file);
            $arr = array(
                'error' => 0,
                'msg' => '成功生成!',
                'file_name' => qw('-str')->toUrlSeparator($thumb_file),
            );
        }
        echo qw('-arr')->jsonEncode($arr);
    }
}
