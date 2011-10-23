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
 * @since       2011-06-20 18:25:53
 */

class Qwin_Menu extends Qwin_Widget
{
    /**
     * 默认选项
     * 
     * @var array
     */
    public $options = array(
        'max' => 8,
        'more' => array(
            'id' => 'more',
            'url' => 'javascript:;',
            'target' => '_self',
            'title' => 'ACT_MORE',
        ),
    );
    
    public function triggerHeaderRight()
    {
        // 加载页眉导航的缓存
        $menus = require $this->cache->options['dir'] . 'menu.php';

        // 将超出的菜单附加到“更多"的菜单下
        if ($this->options['max'] < count($menus[0])) {
            // "更多"菜单的子菜单
            $moreMenus = array_splice($menus[0], $this->options['max']);

            // 根菜单
            $menus[0] = array_splice($menus[0], 0, $this->options['max']);

            // 附加“更多”的菜单
            $this->options['more']['title'] = $this->lang[$this->_defaults['more']['title']];
            $menus[0] += array(
                'more' => $this->options['more']
            );
            $menus[1]['more'] = $moreMenus;
        }

        // 增加Qwin链接
        $menus['qwin'] = array(
            'title' => $this->lang['LBL_QWIN'],
            'url' => $this->url->url('home', 'index'),
        );

        // 页面名称
        $queryString = empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING'];
        $pageUrl = basename($_SERVER['PHP_SELF']) . $queryString;

        // 获取用户信息
        $member = $this->session->get('member');
        
        // 加载样式和脚本
        $view = $this->view;
        $this->minify->add(array(
            $view->getFile('widgets/menu/default.css'),
            $view->getFile('widgets/menu/default.js'),
        ));
        
        $smarty = $this->smarty->getObject();
        $smarty->assign('lang', $this->lang);
        $smarty->assign('member', $member);
        $smarty->assign('menus', $menus);
        $smarty->display($view->getFile('widgets/menu/default.tpl'));
    }
}