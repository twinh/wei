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
 * @since       2011-6-20 18:25:53
 */

class Menu_Widget extends Qwin_Widget_Abstract
{
    public function render($options = null)
    {
        // 加载菜单的缓存
        $menus = require Qwin::config('root') . 'cache/menu.php';
        
        // 加载样式和脚本
        $minify = $this->_widget->get('minify');
        $minify->addArray(array(
            $this->_path . 'view/default.css',
            $this->_path . 'view/default.js',
        ));
        
        $smarty = $this->_widget->get('smarty')->getObject();
        $smarty->assign('menus', $menus);
        $smarty->assign('lang', $this->_lang);
        $smarty->display($this->_path . 'view/default.tpl');
    }
    
    public function renderNavi($options = null)
    {
        // 获取用户信息
        $member = Qwin::call('-session')->get('member');
        
        // 加载样式和脚本
        $minify = $this->_widget->get('minify');
        $minify->addArray(array(
            $this->_path . 'view/navi.css',
            $this->_path . 'view/navi.js',
        ));
        
        $smarty = $this->_widget->get('smarty')->getObject();
        $smarty->assign('lang', $this->_lang);
        $smarty->assign('member', $member);
        $smarty->display($this->_path . 'view/navi.tpl');
    }
}