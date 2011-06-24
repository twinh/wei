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
    /**
     * 默认选项
     * 
     * @var array
     */
    protected $_defaults = array(
        'max' => 8,
        'more' => array(
            'id' => 'more',
            'url' => 'javascript:;',
            'target' => '_self',
            'title' => 'ACT_MORE',
        ),
    );

    public function render($options = null)
    {
        // 增加Qwin链接
        $url = Qwin::call('-url');
        $menus['qwin'] = array(
            'title' => $this->_Lang['LBL_QWIN'],
            'url' => $url->url('com/home', 'index'),
        );
        
        // 页面名称
        $queryString = empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING'];
        $pageUrl = basename($_SERVER['PHP_SELF']) . $queryString;

        $minify = $this->_widget->get('Minify');
        $minify->addArray(array(
            $this->_path . 'view/style.css',
            $this->_path . 'view/js.js',
            $this->_jQuery->loadPlugin('tmpl', false),
        ));
        
        $smarty = $this->_widget->get('Smarty')->getObject();
        $smarty->assign('menus', $menus);
        $smarty->display($this->_path . 'view/default.tpl');
    }
}
