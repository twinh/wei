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

class Common_RecycleBin_Widget_ListTab extends Common_Widget
{
    public function render($param, $view)
    {
        $asc = $view['asc'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');

        // 替换添加,删除的链接
        $param['tab'][0] = array(
            'url' => $url->createUrl($asc, array('action' => 'Restore')),
            'title' => $lang->t('LBL_ACTION_RESTORE'),
            'icon' => 'ui-icon-arrowreturnthick-1-w',
            'target' => null,
            'id' => 'action-restore',
            'class' => null,
        );
        $param['tab'][1]['icon'] = 'ui-icon-close';
        $param['tab'][3] = $param['tab'][2];
        $param['tab'][2] = array(
            'url' => $url->createUrl($asc, array('action' => 'Empty')),
            'title' => $lang->t('LBL_ACTION_EMPTY'),
            'icon' => 'ui-icon-closethick',
            'target' => null,
            'id' => 'action-empty',
            'class' => null,
        );

        $param['object']->renderTab($param['tab'], $view);
    }
}