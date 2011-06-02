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
 * @since       2011-03-27 01:42:40
 */

class ContentHeader_Widget extends Qwin_Widget_Abstract
{
    public function render($view = null)
    {
        // 构建页眉导航
        $module = $view['module'];
        $action = Qwin::config('action');
        $url = Qwin::call('-url');
        $lang = $this->_Lang;

        // 可能没有元数据
        if (!isset($view['meta'])) {
            return false;
        }

        // 图标 > 模块 > 控制器 > 行为
        $header = '';

        $icon = Qwin::config('resource') . 'view/default/icons/' . $view['meta']['page']['icon'] . '_32.png';
        !is_file($icon) && $icon = null;

        $header .= '<a href="' . $url->url($module->toUrl(), 'index') . '">' . $lang->t($view['meta']['page']['title']) . '</a>';
      
        $actionLabel = 'ACT_' . $module->toLang() . '_' . strtoupper($action);
        if (!isset($lang[$actionLabel])) {
            $actionLabel = 'ACT_' . strtoupper($action);
        }

        if (isset($lang[$actionLabel])) {
            $header .= '&nbsp;&raquo;&nbsp;<a href="' . $url->build() . '">' . $lang[$actionLabel] . '</a>';
        }

        require $this->_path . 'view/default.php';
    }
}
