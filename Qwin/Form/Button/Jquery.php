<?php
/**
 * icon
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
 * @version   2010-02-12 18:57 utf-8 中文
 * @since     2010-02-12 18:57 utf-8 中文
 * @todo      以模板形式来加载,分离php和html 
 */


class Qwin_Form_Button_JQuery extends Qwin_Form_Button
{
    public function ajaxUpload($set, $typeSet)
    {
        $js = Qwin_Class::run('-js');
        $rsc = Qwin_Class::run('-rsc')
            ->load('jquery/ui/dialog')
            ->load('jquery/plugin/ajaxupload');
        // 初始化 js 代码组
        $js->newJsCodeGroup($set['name']);
        $js->newJqCodeGroup($set['name']);

        $id = 'icon-button-ajaxupload-' . $set['id'];
        $code = '$("#' . $id . '").ajaxUpload({input : "#' . $set['id'] . '"});';
        $js->addJq($set['name'], $code);
        
        $html = '<a class="ui-state-default ui-corner-all icon-button-common" id="' . $id . '" href="javascript:void(0)">
                <span class="ui-icon ui-icon-info"></span>
                </a>';
        return $html;
    }
    
    public function fileTree($type_set, $set)
    {
        $js = Qwin_Class::run('-js');
        $rsc = Qwin_Class::run('-rsc')
            ->load('jquery/ui/dialog')
            ->load('jquery/plugin/qfiletree');
        $id = 'icon-button-filetree-' . $set['id'];

        $html = '<a class="ui-state-default ui-corner-all icon-button-common" id="' . $id . '" href="javascript:void(0)">
                <span class="ui-icon ui-icon-info"></span>
                </a>';
        $code = '$("#' . $id . '").QFileTree({
        input : "#' . $set['name'] . '",
        filetree : {
            dblClickFolder : function(path){
                $("#qfiletree_dialog").dialog("close");
                $("#ajax_upload_button_' . $set['name'] . '").ajaxUpload({
                    input : "#' . $set['name'] . '",
                    path : path
                });
                $("#ajax_upload_button_' . $set['name'] . '").click();
            }
        }
    });
                    ';
        $js->addJq($set['name'], $code);
       
        return $html;
    }
    
    public function fastCopy($type_set, $set)
    {
        /*$self = Qwin_Class::run('-c');
        $name = $set['name'];
        $id = 'icon-button-fastcopy-' . $set['name'];
        qw('-rsc')->load('jquery/plugin/fastcopy');
        
        $html = '<a class="ui-state-default ui-corner-all icon-button-common" id="' . $id . '" href="javascript:void(0)">
                <span class="ui-icon ui-icon-info"></span>
                </a>';
        $code = '$("#' . $id . '").click(function(){
    $("#' . $name . '").FastCopy(' . qw('-arr')->toJsObject($type_set) . ');
});';
        $self->__js->addJq($set['name'], $code);
        return $html;
         * 
         */
    }
    
    public function qthmb($type_set, $set)
    {
        $self = qw('-ini')->getThis();
        $name = $set['name'];
        $id = 'icon-button-qthmb-' . $set['name'];
        qw('-rsc')->load('jquery/plugin/fastcopy');
        
        $html = '<a class="ui-state-default ui-corner-all icon-button-common" id="' . $id . '" href="javascript:void(0)">
                <span class="ui-icon ui-icon-info"></span>
                </a>';
        $code = '$("#' . $id . '").QThumb({
    input : "#' . $name . '"
});';
        $self->__js->addJq($set['name'], $code);
        return $html;
    }
    
    public function captcha($set, $type_set)
    {
        require 'QwinView/Button/Capcha.php';
    }
}
