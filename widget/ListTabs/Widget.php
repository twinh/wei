<?php
/**
 * Widget
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-03-27 02:21:04
 */

class ListTabs_Widget extends Qwin_Widget_Abstract
{
    /**
     * 生成列表选项卡
     *
     * @param array $param      参数
     *
     *      -- module           模块标识
     *
     *      -- url              当前选项卡查询组成的请求字符串
     *
     * @param <type> $view      视图对象
     * @return array
     * @todo Asc和Url的选择
     * @todo ListTab, FormLink等存在重复的情况,应考虑如何合并
     */
    public function render($view)
    {
        // 获取应用结构配置
        $module = $view['module'];

        $app = Qwin::call('-app');
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $tab = array();

        $moduleId = $module->toId();
        parse_str($view['option']['url'], $get);

        // 获取禁用的行为
        $controllerClass = Com_Controller::getByModule($module, false);
        $classVar = get_class_vars($controllerClass);
        if (isset($classVar['_unableAction'])) {
            $unableAction = $classVar['_unableAction'];
        } else {
            $unableAction = array();
        }

        if (!in_array('add', $unableAction)) {
            $tab['add'] = array(
                'url'       => $url->url($get, array('action' => 'add')),
                'title'     => $lang->t('ACT_ADD'),
                'icon'      => 'ui-icon-plus',
                'target'    => null,
                'id'        => 'action-' . $moduleId . '-add',
                'class'     => 'action-add',
            );
            $tab['copy'] = array(
                'url'   => $url->url($get, array('action' => 'add')),
                'title' => $lang->t('ACT_COPY'),
                'icon'  => 'ui-icon-transferthick-e-w',
                'target'    => null,
                'id'        => 'action-' . $moduleId . '-copy',
                'class'     => 'action-copy',
            );
        }

        // TODO jsLang
        if (!in_array('delete', $unableAction)) {
            $meta = Com_Metadata::getByModule($module);
            if (!isset($meta['page']['useTrash'])) {
                $icon = 'ui-icon-close';
                $jsLang = 'MSG_CONFIRM_TO_DELETE';
            } else {
                $icon = 'ui-icon-trash';
                $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
            }
            $tab['delete'] = array(
                'url'       => 'javascript:;',
                'title'     => $lang->t('ACT_DELETE'),
                'icon'      => $icon,
                'target'    => null,
                'id'        => 'action-' . $moduleId . '-delete',
                'class'     => 'action-delete',
            );
        }

        if (!in_array('list', $unableAction)) {
            $tab['list'] = array(
                'url' => $url->url($get, array('action' => 'index')),
                'title' => $lang->t('ACT_LIST'),
                'icon' => 'ui-icon-note',
                'target' => null,
                'id' => 'action-' . $moduleId . '-list',
                'class' => 'action-list',
            );
        }

        // 如果当前行为存在选项卡视图,加载该视图,否则直接输出默认选项卡内容
        $class = strtr($module, '/', '_') . '_ListTabWidget';
        if(class_exists($class)) {
            $object = new $class;
            $file = $view->decodePath('<root>com/widget-<action>-tab<suffix>');
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
            $output .= Qwin_Util_JQuery::link($row['url'], $row['title'], $row['icon'], $row['class'], $row['target'], $row['id']);
        }
        if ($echo) {
            require $view->decodePath('<root>com/basic/output<suffix>');
        } else {
            return $output;
        }
    }
}
