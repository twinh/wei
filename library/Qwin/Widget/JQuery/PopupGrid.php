<?php
/**
 * PopupGrid
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
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-04 18:54:37
 */

class Qwin_Widget_JQuery_PopupGrid
{
    /**
     * 生成文件树
     *
     * @param array $meta 域的元数据
     * @return string 文件树代码
     */
    public function render($meta, $title, $url, $viewField)
    {

        $lang = Qwin::run('-lang');
        $jquery = Qwin::run('Qwin_Resource_JQuery');
        $id = $meta['id'];

        // 设置新的表单属性
        foreach(array('name', 'id') as $value)
        {
            $meta[$value] .= '_value';
        }
        $meta['readonly'] = 'readonly';
        
        // 输入框显示的值
        if(isset($meta['_value2']))
        {
            $meta['_value'] = $meta['_value2'];
            $selected = $lang->t('LBL_SELECTED');
        } else {
            $selected = $lang->t('LBL_NOT_SELECTED');
        }
        $meta['_value'] .= '(' . $selected . ', ' . $lang->t('LBL_READONLY') . ')';
        
        $title = $lang->t($title);
        $url = Qwin::run('Qwin_Url')->createUrl($url);
        $code = $jquery->loadUi('position')
            . $jquery->loadUi('dialog')
            . $jquery->loadPlugin('qwin-popup')
            . Qwin::run('Qwin_Form')->render($meta)
            . '<button id="ui-button-qwin-popup-' . $id . '" type="button"><span class="ui-icon ui-icon-calculator">' . $meta['name'] . '</span></button>
               <button id="ui-button-qwin-popup-' . $id . '-clear" type="button"><span class="ui-icon ui-icon-arrowreturnthick-1-w">' . $meta['name'] . '</span></button>
               <script type="text/javascript">
                jQuery(function($){
                    $("#' . $id . '").hide();
                    $("#ui-button-qwin-popup-' . $id . '").qwinPopup({
                        title: "' . $title . '",
                        url: "' . $url . '",
                        viewInput: "#' . $meta['id'] . '",
                        valueInput: "#' . $id . '",
                        viewColumn: "' . $viewField[0] .'",
                        valueColumn: "' . $viewField[1] . '"
                    });
                    $("#ui-button-qwin-popup-' . $id . '-clear").click(function(){
                        $("#' . $id . '").val("");
                        $("#' . $meta['id'] . '").val("(' . $lang->t('LBL_NOT_SELECTED') . ', ' . $lang->t('LBL_READONLY') . ')");
                    });
                })
                </script>'
        ;
        
        return $code;
    }
}
