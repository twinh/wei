<?php
/**
 * 富文本
 *
 * 
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
 * @version   2009-11-21 13:18 utf-8 中文
 * @since     2009-11-21 13:18 utf-8 中文
 */


class Qwin_Form_ElementExt_Editor
{
    // 编辑器 (CKEditor)
    public function ckeditor($pub_set, $pri_set, $value, $data)
    {
        $self = Qwin_Class::run('-c');
        $js = Qwin_Class::run('-js');
        $rsc = Qwin_Class::run('-rsc');

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
        $self = Qwin_Class::run('-c');
        $js = Qwin_Class::run('-js');
        $rsc = Qwin_Class::run('-rsc');
        // 初始化 js 代码组
        $js->newJsCodeGroup($pub_set['name'])
           ->newJqCodeGroup($pub_set['name']);
        $rsc->load('js/other/json2');
        $rsc->load('jquery/ui/dialog');
        $rsc->load('jquery/plugin/mapeditor');

        if('' == $value)
        {
            $option = Qwin_Class::run('Qwin_Helper_Array')->toJsObject($pri_set['_typeExt']['mapeditor']);
        }
        // 初始化jq插件代码
        $code = '$("#' . $pub_set['id'] . '").mapEditor(' . $option . ');';
        // 将代码加入代码组中
        $js->addJq($pub_set['name'], $code);

        return $data;
    }
}
