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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-09 15:35:03
 */

class Com_Trash_Widget_ListTabs extends Qwin_Widget_Abstract
{
    public function render($options)
    {
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');

        $listTabs = Qwin::call('-widget')->get('ListTabs');
        $tabs = $listTabs->getTabs($options['module'], $options['options']['url']);
        $tabs = array(
            'restore' => array(
                'url' => $url->url($options['module']->toUrl(), 'restore'),
                'title' => $lang->t('ACT_RESTORE'),
                'icon' => 'ui-icon-arrowreturnthick-1-w',
                'target' => null,
                'id' => 'action-restore',
                'class' => null,
             ),
            'empty' => array(
                'url' => $url->url($options['module']->toUrl(), 'empty'),
                'title' => $lang->t('ACT_EMPTY'),
                'icon' => 'ui-icon-closethick',
                'target' => null,
                'id' => 'action-empty',
                'class' => null,
            ),
        ) + $tabs;

        // 替换添加,删除的链接
        $tabs = array(
            'list' => $tabs['list'],
            'restore' => $tabs['restore'],
            'delete' => $tabs['delete'],
            'empty' => $tabs['empty'],
        );

        $listTabs->renderTabs($tabs);
    }
}
