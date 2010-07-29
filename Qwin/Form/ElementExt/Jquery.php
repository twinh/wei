<?php
/**
 * jQuery 插件与表单
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
 * @version   2009-11-21 20:09 utf-8 中文
 * @since     2009-11-21 20:09 utf-8 中文
 * @todo      is_eval 的安全问题
 */

class Qwin_Form_ElementExt_JQuery extends Qwin_Form
{
    /**
     * @todo 配置
     */
    function datepicker($pub_set, $pri_set, $value, $data)
    {
        $js = Qwin_Class::run('-js');
        $html = Qwin_Class::run('-html');
        $rsc = Qwin_Class::run('-rsc');

        // 初始化 js 代码组
        $js->newJsCodeGroup($pub_set['name']);
        $js->newJqCodeGroup($pub_set['name']);
        
        // 加载日期选择器插件
        $rsc->load('jquery/ui/datepicker');
        
        // 初始化jq插件代码
        $code = '$("#' . $pub_set['id'] . '").datepicker({dateFormat: "yy-mm-dd"});';

        // 将代码加入代码组中
        $js->addJq($pub_set['name'], $code);
        
        return $data;
    }
    
    /**
     * @todo
     */
    function ajaxUpload($pub_set, $pri_set, $value, $data)
    {
        $js = Qwin_Class::run('-js');
        $rsc = Qwin_Class::run('-rsc')
            ->load('jquery/ui/dialog')
            ->load('jquery/plugin/ajaxupload');

        // 初始化 js 代码组
        $js->newJsCodeGroup($pub_set['name']);
        $js->newJqCodeGroup($pub_set['name']);
        $code = '$("#' . $pub_set['id'] . '").ajaxUpload();';
        $js->addJq($pub_set['name'], $code);
        return $data;
    }
    
    /**
     * @todo
     */
    function fileTree($pub_set, $pri_set, $value, $data)
    {
        $js = Qwin_Class::run('-js');
        $rsc = Qwin_Class::run('-rsc')
            ->load('jquery/ui/dialog')
            ->load('jquery/plugin/qfiletree');

        // TODO 置于Qwin_Form类中
        $set = Qwin_Class::run('-arr')->decodeArray($pri_set['_typeExt']);
        if(!empty($set['fileTree']))
        {
            $code = '$("#' . $pub_set['id'] . '").QFileTree(' . Qwin_Class::run('-arr')->toJsObject($set['fileTree']) . ');';
        } else {
            $code = '$("#' . $pub_set['id'] . '").QFileTree({
            filetree : {
            dblClickFolder : function(path){
                $("#qfiletree_dialog").dialog("close");
                $("#icon-button-ajaxupload-' . $pub_set['name'] . '").ajaxUpload({
                    input : "#' . $pub_set['name'] . '",
                    path : path
                });
                $("#icon-button-ajaxupload-' . $pub_set['name'] . '").click();
            }
        }
});';
        }

        // 初始化 js 代码组
        $js->newJsCodeGroup($pub_set['name']);
        $js->newJqCodeGroup($pub_set['name']);
        
        $js->addJq($pub_set['name'], $code);
        
        return $data;
    }

    function combobox($pub_set, $pri_set, $value, $data)
    {
        //$html = qw('-rsc')->loadJQueryPlugin('dialog');
        //echo 'combobox';
        return $data;
    }
}
