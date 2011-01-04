<?php
/**
 * ListTab
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
 * @package     Common
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-04 17:02:34
 * @todo        通过元数据定义list tab
 */
class Common_Widget_ListTab extends Common_Widget
{
    public function render($param, $view)
    {
        $set = $view['set'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        
        $tabSetting = array(
            array(
                'url' => $url->createUrl($set, array('action' => 'Add')),
                'title' => $lang->t('LBL_ACTION_ADD'),
                'icon' => 'ui-icon-plus',
                'target' => null,
                'id' => null,
                'class' => null,
            ),
            array(
                'url' => 'javascript:;',
                'title' => $lang->t('LBL_ACTION_DELETE'),
                'icon' => 'ui-icon-trash',
                'target' => null,
                'id' => null,
                'class' => 'action-delete',
            ),
            array(
                'url' => $url->createUrl($set, array('action' => 'Index')),
                'title' => $lang->t('LBL_ACTION_LIST'),
                'icon' => 'ui-icon-note',
                'target' => null,
                'id' => null,
                'class' => null,
            ),
        );
        
        // 如果当前行为存在选项卡视图,加载该视图,否则直接输出默认选项卡内容
        $actionListTab = $view->decodePath('<resource><theme>/<namespace>/element/<module>/<controller>/<action>-tab<suffix>');
        if (is_file($actionListTab)) {
            require $actionListTab;
        } else {
            $output = '';
            foreach ($tabSetting as $tab) {
                $output .= Qwin_Helper_Html::jQueryLink($tab['url'], $tab['title'], $tab['icon'], $tab['class'], $tab['target'], $tab['id']);
            }
            require $view->decodePath('<resource><theme>/<namespace>/element/basic/output<suffix>');
        }
    }
}