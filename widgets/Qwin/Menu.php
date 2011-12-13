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
    public function triggerHeaderRight()
    {
        // 获取用户信息
        $member = $this->session->get('member');
        
        // 加载样式和脚本
        $this->_dir = $this->getDir();
        $view = $this->view;
        $this->minify->add(array(
            $view->getFile($this->_dir . '/default.css'),
            $view->getFile($this->_dir . '/default.js'),
        ));
        
        $smarty = $this->smarty->getObject();
        $smarty->assign('lang', $this->lang);
        $smarty->assign('member', $member);
        $smarty->display($this->_dir . '/default.tpl');
    }
    
    public function getDir()
    {
        return dirname(__FILE__) . '/Menu';
    }
}