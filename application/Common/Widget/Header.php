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
        $asc = $view['asc'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');

        // 图标 > 模块 > 控制器 > 行为
        $header = '';
        
        $icon = Qwin::config('resource') . 'image/' . $view['meta']['page']['icon'] . '_32.png';
        !is_file($icon) && $icon = null;

        $header .= '<a href="' . $url->url(array_diff_key($asc, array('action' => ''))) . '">' . $lang->t($view['meta']['page']['title']) . '</a>';

        /*// 如果模块和控制器相同,不显示模块
        if ($asc['module'] != $asc['controller']) {
            $header .= '<a href="' . $url->url($asc) . '">' . $lang->t('LBL_MODULE_' . strtoupper($asc['module'])) . '</a>&nbsp;&raquo;&nbsp;';
        }

        // 控制器
        $header .= '<a href="' . $url->url(array_diff_key($asc, array('action' => ''))) . '">' . $lang->t('LBL_CONTROLLER_' . strtoupper($asc['controller'])) . '</a>&nbsp;&raquo;&nbsp;';
        */
        // 行为 todo 允许自定义
        $actionLabel = 'ACT_' . strtoupper($asc['action']);
        $actionHeader = $lang->t($actionLabel);
        if ($actionLabel != $actionHeader) {
            $header .= '&nbsp;&raquo;&nbsp;<a href="' . $url->url($_GET) . '">' . $lang->t('ACT_' . strtoupper($asc['action'])) . '</a>';
        }
        
        require $view->decodePath('<resource><theme>/<defaultNamespace>/element/widget/header<suffix>');
    }
}
