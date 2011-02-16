<?php
/**
 * FormLink
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-13 19:31:17
 */

class Common_Widget_FormLink extends Common_Widget
{
    public function render($param, $view)
    {
        $manager = Qwin_Application_Manager::getInstance();
        $asc = $view['asc'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $controller = $manager->getClass('controller', $asc);
        $varList = get_class_vars($controller);
        if (isset($varList['_forbiddenAction'])) {
            $forbiddenAction = (array)$varList['_forbiddenAction'];
        } else {
            $forbiddenAction = array();
        }
        $link = array();

        if (!in_array('index', $forbiddenAction)) {
            $link['index'] = array(
                'url'   => $url->url($asc, array('action' => 'Index')),
                'title' => $lang->t('LBL_ACTION_LIST'),
                'icon'  => 'ui-icon-note',
            );
        }

        if (!in_array('add', $forbiddenAction)) {
            $link['add'] = array(
                'url'   => $url->url($asc, array('action' => 'Add')),
                'title' => $lang->t('LBL_ACTION_ADD'),
                'icon'  => 'ui-icon-plus',
            );
            
        }

        $data = $param[0];
        $primaryKey = $param[1];
        if (isset($param[0][$param[1]])) {
            if (!in_array('edit', $forbiddenAction)) {
                $link['edit'] = array(
                    'url'   => $url->url($asc, array('action' => 'Edit', $primaryKey => $data[$primaryKey])),
                    'title' => $lang->t('LBL_ACTION_EDIT'),
                    'icon'  => 'ui-icon-tag',
                );
            }

            if (!in_array('view', $forbiddenAction)) {
                $link['view'] = array(
                    'url'   => $url->url($asc, array('action' => 'View', $primaryKey => $data[$primaryKey])),
                    'title' => $lang->t('LBL_ACTION_VIEW'),
                    'icon'  => 'ui-icon-lightbulb',
                );
            }

            if (!in_array('add', $forbiddenAction)) {
                $link['copy'] = array(
                    'url'   => $url->url($asc, array('action' => 'Add', $primaryKey => $data[$primaryKey])),
                    'title' => $lang->t('LBL_ACTION_COPY'),
                    'icon'  => 'ui-icon-transferthick-e-w',
                );
            }

             if (!in_array('delete', $forbiddenAction)) {
                $meta = $manager->getMetadataByAsc($asc);
                if (!isset($meta['page']['useTrash'])) {
                    $icon = 'ui-icon-close';
                    $jsLang = 'MSG_CONFIRM_TO_DELETE';
                } else {
                    $icon = 'ui-icon-trash';
                    $jsLang = 'MSG_CONFIRM_TO_DELETE_TO_TRASH';
                }
                 $link['delete'] = array(
                    'url'   => 'javascript:if(confirm(QWIN_PATH.Lang.' . $jsLang . ')){window.location=\'' . $url->url($asc, array('action' => 'Delete', $primaryKey => $data[$primaryKey])) . '\'};',
                    'title' => $lang->t('LBL_ACTION_DELETE'),
                    'icon'  => $icon,
                );
             }
        }
        $link['return'] = array(
            'url'   => 'javascript:history.go(-1);',
            'title' => $lang->t('LBL_ACTION_RETURN'),
            'icon'  => 'ui-icon-arrowthickstop-1-w',
        );

        // 如果当前行为存在选项卡视图,加载该视图,否则直接输出默认选项卡内容
        $class = $asc['namespace'] . '_' . $asc['module'] . '_Widget_Form_Link';
        if(class_exists($class)) {
            $object = new $class;
            $file = $view->decodePath('<resource><theme>/<defaultNamespace>/element/<module>/<controller>/<action>-formlink<suffix>');
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
            $output .= Qwin_Util_JQuery::link($row['url'], $row['title'], $row['icon']);
        }
        if ($echo) {
            require $view->decodePath('<resource><theme>/<defaultNamespace>/element/basic/output<suffix>');
        } else {
            return $output;
        }
    }
}
