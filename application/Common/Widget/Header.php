<?php
/**
 * Header
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
 * @since       2011-01-04 15:51:59
 */

class Common_Widget_Header extends Common_Widget
{
    public function render($param, $view)
    {
        // 构建页眉导航
        $set = $view['set'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');

        // 图标 > 模块 > 控制器 > 行为
        $header = '';
        
        $icon = QWIN_RESOURCE_PATH . '/image/' . $view['meta']['page']['icon'] . '_32.png';
        !file_exists($icon) && $icon = null;

        // 如果模块和控制器相同,不显示模块
        if ($set['module'] != $set['controller']) {
            $header .= '<a href="' . $url->createUrl($set) . '">' . $lang->t('LBL_MODULE_' . strtoupper($set['module'])) . '</a>&nbsp;&raquo;&nbsp;';
        }

        // 控制器
        $header .= '<a href="' . $url->createUrl(array_diff_key($set, array('action' => ''))) . '">' . $lang->t('LBL_CONTROLLER_' . strtoupper($set['controller'])) . '</a>&nbsp;&raquo;&nbsp;';

        // 行为
        $header .= '<a href="' . $url->createUrl($_GET) . '">' . $lang->t('LBL_ACTION_' . strtoupper($set['action'])) . '</a>';
        
        require $view->decodePath('<resource><theme>/<namespace>/element/widget/header<suffix>');
    }
}