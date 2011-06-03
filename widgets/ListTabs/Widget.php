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
    protected $_defaults = array(
        'max' => 4,
    );

    public function getTabs($module, $get)
    {
        $url = Qwin::call('-url');
        $lang = $this->_Lang;
        $tabs = array();

        $moduleId = $module->toId();
        if (isset($get['json'])) {
            unset($get['json']);
        }

        // 获取禁用的行为
        $controllerClass = Controller_Widget::getByModule($module, false);
        $classVar = get_class_vars($controllerClass);
        if (isset($classVar['_unableAction'])) {
            $unableAction = $classVar['_unableAction'];
        } else {
            $unableAction = array();
        }

        if (!in_array('add', $unableAction)) {
            $tabs['add'] = array(
                'url'       => $url->build($get, array('action' => 'add')),
                'title'     => $lang->t('ACT_ADD'),
                'icon'      => 'ui-icon-plus',
                'target'    => null,
                'id'        => 'action-' . $moduleId . '-add',
                'class'     => 'action-add',
            );
            $tabs['copy'] = array(
                'url'   => $url->build($get, array('action' => 'add')),
                'title' => $lang->t('ACT_COPY'),
                'icon'  => 'ui-icon-transferthick-e-w',
                'target'    => null,
                'id'        => 'action-' . $moduleId . '-copy',
                'class'     => 'action-copy',
            );
        }

        // TODO jsLang
        if (!in_array('delete', $unableAction)) {
            $meta = Com_Meta::getByModule($module);
            if (!isset($meta['page']['useTrash'])) {
                $icon = 'ui-icon-close';
                $jsLang = 'MSG_CONFIRM_TO_DELETE';
            } else {
                $icon = 'ui-icon-trash';
                $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
            }
            $tabs['delete'] = array(
                'url'       => 'javascript:;',
                'title'     => $lang->t('ACT_DELETE'),
                'icon'      => $icon,
                'target'    => null,
                'id'        => 'action-' . $moduleId . '-delete',
                'class'     => 'action-delete',
            );
        }

        if (!in_array('list', $unableAction)) {
            $tabs['list'] = array(
                'url' => $url->url($module->toUrl(), 'index'),
                'title' => $lang->t('ACT_LIST'),
                'icon' => 'ui-icon-note',
                'target' => null,
                'id' => 'action-' . $moduleId . '-list',
                'class' => 'action-list',
            );
            // TODO hook
            $tabs['filter'] = array(
                'url' => $url->url($module->toUrl(), 'index', array('filter' => '1')),
                'title' => $lang->t('ACT_FILTER'),
                'icon' => 'ui-icon-calculator',
                'target' => null,
                'id' => 'action-' . $moduleId . '-filter',
                'class' => 'action-filter',
            );
        }
        return $tabs;
    }

    /**
     * 生成列表选项卡
     *
     * @param array $param      参数
     *
     *      -- module           模块标识
     *
     *      -- url              当前选项卡查询组成的请求字符串
     *
     * @param Com_View $view      视图对象
     * @return array
     * @todo ListTab, FormLink等存在重复的情况,应考虑如何合并
     */
    public function render($view = null)
    {
        $tabs = $this->getTabs($view['module'], $view['get']);
        return $this->renderTabs($tabs);
    }

    public function renderTabs($tabs)
    {
        $this->_options = $this->_defaults;
        $moreTab = $subTabs = array();
        if ($this->_options['max'] < count($tabs)) {
            $subTabs = array_splice($tabs, $this->_options['max']);
            $tabs = array_splice($tabs, 0, $this->_options['max']);
            $moreTab = array(
                'url' => 'javascript:;',
                'title' => $this->_Lang->t('ACT_MORE'),
                'icon' => 'ui-icon-triangle-1-e',
                'target' => null,
                'id' => null,
                'class' => 'action-more',
            );
        }

        /* @var $minify Minify_Widget */
        $minify = $this->_widget->get('Minify');
        $minify->addArray(array(
            $this->_path . 'view/style.css',
            $this->_path . 'view/js.js',
        ));

        /* @var $smarty Smarty */
        $smarty = $this->_widget->get('Smarty')->getObject();
        $smarty->assign('tabs', $tabs);
        $smarty->assign('moreTab', $moreTab);
        $smarty->assign('subTabs', $subTabs);
        $smarty->assign('lang', $this->_Lang);
        $smarty->display($this->_path . 'view/default.tpl');
    }
}
