<?php
/**
 * jQuery 插件与表单
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
 * @since       2009-11-21 20:09
 * @todo        is_eval 的安全问题
 */

class Qwin_Form_ElementExt_JQuery extends Qwin_Form
{
    /**
     * @todo 配置
     */
    function datepicker($pub_set, $pri_set, $value, $data)
    {
        $js = Qwin::run('-js');
        $html = Qwin::run('-html');
        $rsc = Qwin::run('-rsc');

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
        $js = Qwin::run('-js');
        $rsc = Qwin::run('-rsc')
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
        $js = Qwin::run('-js');
        $rsc = Qwin::run('-rsc')
            ->load('jquery/ui/dialog')
            ->load('jquery/plugin/qfiletree');

        // TODO 置于Qwin_Form类中
        $set = Qwin::run('-arr')->decodeArray($pri_set['_typeExt']);
        if(!empty($set['fileTree']))
        {
            $code = '$("#' . $pub_set['id'] . '").QFileTree(' . Qwin::run('-arr')->toJsObject($set['fileTree']) . ');';
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

    public function fastCopy($type_set, $set)
    {
        /*$self = Qwin::run('-c');
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
