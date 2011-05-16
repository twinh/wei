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
 * @since       2011-03-27 02:05:30
 */

class OperLinks_Widget extends Qwin_Widget_Abstract
{
//    protected $_defaults = array(
//        'view'  => null,
//        'data'  => array(),
//        'pk'    => null,
//    );

    public function render($view)
    {
        return null;
        $module = $view['module'];
        $action = $view['action'];
        // 模块的Url形式
        $moduleUrl = $module->toUrl();
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $controller = Com_Controller::getByModule($module, false);
        $varList = get_class_vars($controller);
        if (isset($varList['_unableActions'])) {
            $unableActions = (array)$varList['_unableActions'];
        } else {
            $unableActions = array();
        }
        $link = array();
        $data = $view['data'];
        $primaryKey = $view['primaryKey'];
        
        // 上一记录，下一记录
        if ('edit' == $action || 'view' == $action) {
            $link['next'] = array(
                'url'   => $url->url($moduleUrl, $action, array($primaryKey => $data[$primaryKey], 'forward' => 'next')),
                'title' => $lang['ACT_NEXT'],
                'icon'  => 'ui-icon-circle-triangle-e',
                'class' => 'qw-fr',
            );
            $link['prev'] = array(
                'url'   => $url->url($moduleUrl, $action, array($primaryKey => $data[$primaryKey], 'forward' => 'prev')),
                'title' => $lang['ACT_PREV'],
                'icon'  => 'ui-icon-circle-triangle-w',
                'class' => 'qw-fr',
            );
        }

        if (!in_array('index', $unableActions) && method_exists($controller, 'actionIndex')) {
            $link['index'] = array(
                'url'   => $url->url($moduleUrl, 'index'),
                'title' => $lang->t('ACT_LIST'),
                'icon'  => 'ui-icon-note',
            );
        }

        if (!in_array('add', $unableActions) && method_exists($controller, 'actionAdd')) {
            $link['add'] = array(
                'url'   => $url->url($moduleUrl, 'add'),
                'title' => $lang->t('ACT_ADD'),
                'icon'  => 'ui-icon-plus',
            );

        }

        
        if (isset($data[$primaryKey])) {
            if (!in_array('edit', $unableActions) && method_exists($controller, 'actionEdit')) {
                $link['edit'] = array(
                    'url'   => $url->url($moduleUrl, 'edit', array($primaryKey => $data[$primaryKey])),
                    'title' => $lang->t('ACT_EDIT'),
                    'icon'  => 'ui-icon-tag',
                );
            }

            if (!in_array('view', $unableActions) && method_exists($controller, 'actionView')) {
                $link['view'] = array(
                    'url'   => $url->url($moduleUrl, 'view', array($primaryKey => $data[$primaryKey])),
                    'title' => $lang->t('ACT_VIEW'),
                    'icon'  => 'ui-icon-lightbulb',
                );
            }

            if (!in_array('add', $unableActions) && method_exists($controller, 'actionAdd')) {
                $link['copy'] = array(
                    'url'   => $url->url($moduleUrl, 'add', array($primaryKey => $data[$primaryKey])),
                    'title' => $lang->t('ACT_COPY'),
                    'icon'  => 'ui-icon-transferthick-e-w',
                );
            }

             if (!in_array('delete', $unableActions) && method_exists($controller, 'actionDelete')) {
                $meta = Com_Meta::getByModule($module);
                if (!isset($meta['page']['useTrash'])) {
                    $icon = 'ui-icon-close';
                    $jsLang = 'MSG_CONFIRM_TO_DELETE';
                } else {
                    $icon = 'ui-icon-trash';
                    $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
                }
                 $link['delete'] = array(
                    'url'   => 'javascript:if(confirm(Qwin.Lang.' . $jsLang . ')){window.location=\'' . $url->url($moduleUrl, 'delete', array($primaryKey => $data[$primaryKey])) . '\'};',
                    'title' => $lang->t('ACT_DELETE'),
                    'icon'  => $icon,
                );
             }
        }
        $link['return'] = array(
            'url'   => 'javascript:history.go(-1);',
            'title' => $lang->t('ACT_RETURN'),
            'icon'  => 'ui-icon-arrowthickstop-1-w',
        );

        

        // 如果当前行为存在选项卡视图,加载该视图,否则直接输出默认选项卡内容
        $class = strtr($module, '/', '_') . '_OperLinksWidget';
        if(class_exists($class)) {
            $object = new $class;
            // TODO
            $file = $view->decodePath('<resource><theme>/<defaultPackage>/element/<module>/<controller>/<action>-formlink<suffix>');
            return $object->render(array(
                'link'      => $link,
                'file'      => $file,
                'object'    => $this,
                'param'     => $param,
            ), $view);
        } else {
            return $this->renderLink($link, $view, false);
        }
    }

    /**
     * 输出表单链接
     *
     * @param object $view 视图对象
     * @param bool $echo 是否输出视图
     * @return string
     */
    public function renderLink($link, $view, $echo = true)
    {
        $output = '';
        foreach ($link as $row) {
            !isset($row['class']) && $row['class'] = null;
            $output .= Qwin_Util_JQuery::link($row['url'], $row['title'], $row['icon'], $row['class']);
        }
        if ($echo) {
            require $view->decodePath('<resource><theme>/<defaultPackage>/element/basic/output<suffix>');
        } else {
            return $output;
        }
    }
}
