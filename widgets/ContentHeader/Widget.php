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

class ContentHeader_Widget extends Qwin_Widget
{
    public function render($options = null)
    {
        // 通过配置模块获取元数据对象
        $module = Qwin::config('module');
        $meta = Meta_Widget::getByModule($module, false);
        if (!class_exists($meta)) {
            return $this;
        }
        
        $meta = Qwin_Meta::getInstance()->get($meta);
        $view = $this->_view;
        // 构建页眉导航
        $action = Qwin::config('action');
        $url = $this->_url;
        $lang = $this->_lang;

        // 图标 > 模块 > 控制器 > 行为
        $header = '';

        $icon = Qwin::config('resource') . 'view/default/icons/' . $meta['page']['icon'] . '_32.png';
        !is_file($icon) && $icon = null;

        $header .= '<a href="' . $url->url($module->getUrl(), 'index') . '">' . $lang->t($meta['page']['title']) . '</a>';
      
        $actionLabel = 'ACT_' . $module->getLang() . '_' . strtoupper($action);
        if (!isset($lang[$actionLabel])) {
            $actionLabel = 'ACT_' . strtoupper($action);
        }

        if (isset($lang[$actionLabel])) {
            $header .= '&nbsp;&raquo;&nbsp;<a href="' . $url->build() . '">' . $lang[$actionLabel] . '</a>';
        }
        
        $this->_minify->add($this->_path . 'view/default.css');

        require $this->_path . 'view/default.php';
    }
}
