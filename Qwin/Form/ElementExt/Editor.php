<?php
/**
 * 富文本
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
 * @subpackage  Form
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 13:18
 */


class Qwin_Form_ElementExt_Editor
{
    // 编辑器 (CKEditor)
    public function ckeditor($pub_set, $pri_set, $value, $data)
    {
        $self = Qwin::run('-c');
        $js = Qwin::run('-js');
        $rsc = Qwin::run('-rsc');

        // 初始化 js 代码组
        $js->newJsCodeGroup($pub_set['name']);
        $js->newJqCodeGroup($pub_set['name']);
        
        // 配置 CKFinder
        require_once RESOURCE_PATH . DS . 'js/ckfinder/qwin_interface.php';
        $qwin_interface = new QWin_CKFinder_Interface();
        // TODO param 2 登陆的标准
        $path = dirname($_SERVER['SCRIPT_NAME']) . '/Public/upload/';
        // $path = '/' . basename(ROOT_PATH) . '/public/upload/';
        $qwin_interface->setInterface($path, true);
        
        $rsc->load('js/ckeditor/');
        $rsc->load('js/ckfinder/');
        
        qw('-str')->set($pri_set['_resource']);
        $code = 'var ckeditor = CKEDITOR.replace("' . $pub_set['id'] . '", ' . qw('-arr')->toJsObject($pri_set['_resource']) . ');';
        $code .= 'CKFinder.SetupCKEditor(ckeditor, "' . qw('-str')->toUrlSeparator(RESOURCE_PATH) . '/js/ckfinder/" );';
        $js->addJq($pub_set['name'], $code);
        
        return $data;
    }
    
    // jQuery 插件
    public function mapEditor($pub_set, $pri_set, $value, $data)
    {
        $option = '';
        $self = Qwin::run('-c');
        $js = Qwin::run('-js');
        $rsc = Qwin::run('-rsc');
        // 初始化 js 代码组
        $js->newJsCodeGroup($pub_set['name'])
           ->newJqCodeGroup($pub_set['name']);
        $rsc->load('js/other/json2');
        $rsc->load('jquery/ui/dialog');
        $rsc->load('jquery/plugin/mapeditor');

        if('' == $value)
        {
            $option = Qwin::run('Qwin_Helper_Array')->toJsObject($pri_set['_typeExt']['mapeditor']);
        }
        // 初始化jq插件代码
        $code = '$("#' . $pub_set['id'] . '").mapEditor(' . $option . ');';
        // 将代码加入代码组中
        $js->addJq($pub_set['name'], $code);

        return $data;
    }
}
