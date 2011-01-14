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
        $asc = $view['asc'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $controller = Qwin::run('-controller');
        $forbiddenAction = $controller->getForbiddenAction();
        $tab = array();
        
        if (!in_array('add', $forbiddenAction)) {
            $tab['add'] = array(
                'url'       => $url->createUrl($asc, array('action' => 'Add')),
                'title'     => $lang->t('LBL_ACTION_ADD'),
                'icon'      => 'ui-icon-plus',
                'target'    => null,
                'id'        => 'action-add',
                'class'     => null,
            );
        }

        // TODO jsLang
        if (!in_array('delete', $forbiddenAction)) {
            if (!isset($view['meta']['page']['useRecycleBin'])) {
                $icon = 'ui-icon-close';
                $jsLang = 'MSG_CONFIRM_TO_DELETE';
            } else {
                $icon = 'ui-icon-trash';
                $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
            }
            $tab['delete'] = array(
                'url'       => 'javascript:;',
                'title'     => $lang->t('LBL_ACTION_DELETE'),
                'icon'      => $icon,
                'target'    => null,
                'id'        => 'action-delete',
                'class'     => null,
            );
        }

        if (!in_array('list', $forbiddenAction)) {
            $tab['list'] = array(
                'url' => $url->createUrl($asc, array('action' => 'Index')),
                'title' => $lang->t('LBL_ACTION_LIST'),
                'icon' => 'ui-icon-note',
                'target' => null,
                'id' => 'action-list',
                'class' => null,
            );
        }

        // 如果当前行为存在选项卡视图,加载该视图,否则直接输出默认选项卡内容
        $class = $asc['namespace'] . '_' . $asc['module'] . '_Widget_ListTab';
        if(class_exists($class)) {
            $object = new $class;
            $file = $view->decodePath('<resource><theme>/<defaultNamespace>/element/<module>/<controller>/<action>-tab<suffix>');
            return $object->render(array(
                'tab' => $tab,
                'file' => $file,
                'object' => $this,
            ), $view);
        } else {
            $this->renderTab($tab, $view);
        }
    }

    /**
     * 输出选项卡视图
     *
     * @param object $view 视图对象
     * @param bool $echo 是否输出视图
     * @return string
     */
    public function renderTab($tab, $view, $echo = true)
    {
        $output = '';
        foreach ($tab as $row) {
            $output .= Qwin_Helper_Html::jQueryLink($row['url'], $row['title'], $row['icon'], $row['class'], $row['target'], $row['id']);
        }
        if ($echo) {
            require $view->decodePath('<resource><theme>/<defaultNamespace>/element/basic/output<suffix>');
        } else {
            return $output;
        }
    }
}
