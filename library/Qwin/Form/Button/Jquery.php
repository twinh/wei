<?php
/**
 * Jquery
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
 * @since       2010-02-12 18:57
 * @todo        以模板形式来加载,分离php和html
 */


class Qwin_Form_Button_JQuery extends Qwin_Form_Button
{
    public function ajaxUpload($set, $typeSet)
    {
        $js = Qwin::run('-js');
        $rsc = Qwin::run('-rsc')
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
