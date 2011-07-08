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
 * @since       2011-03-26 11:39:36
 * @todo        改为Tabs
 */

class Tabs_Widget extends Qwin_Widget_Abstract
{
    public function render($options = null)
    {
        // 
        $request = Qwin::call('-request');
        if ('content' != $request['view']) {
            $this->_view->setElement('content', array(
                $this->_path . '/view/empty.php',
            ));
        }

        $session = Qwin::call('-session');
        $tabs = (array)$session->get('tabs');
        $url = '?' . $_SERVER['QUERY_STRING'];
        $lastTab = $session['lastTab'] ? $session['lastTab'] : $url;
        
        // 检查当前页是否在选项卡中,没有则加入
        if (!isset($tabs[$url])) {
            $tabs = array(
                $url => array(
                    'url' => $url,
                    'title' => $this->_lang['MOD_' . Qwin::config('module')->getLang()],
                ),
            ) + $tabs;
        }
        $session['tabs'] = $tabs;

        // 增加Qwin链接
        $url = $this->_url;
        
        $minify = $this->_widget->get('Minify');
        $minify->addArray(array(
            $this->_path . 'view/default.css',
            $this->_path . 'view/default.js',
            $this->_jQuery->loadPlugin('tmpl', false),
            $this->_jQuery->loadUi('mouse'),
            $this->_jQuery->loadUi('sortable'),
        ));
        
        $smarty = $this->_widget->get('Smarty')->getObject();
        $smarty->assign('tabs', $tabs);
        $smarty->assign('lastTab', $lastTab);
        $smarty->display($this->_path . 'view/default.tpl');
    }
    
    public function renderContent($options = null)
    {
        $this->_minify->addArray(array(
            $this->_path . 'view/content.js',
            $this->_path . 'view/content.css',
        ));
    }
}
