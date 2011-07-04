<?php
/**
 * Controller
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
 * @since       2011-6-24 11:00:44
 */

class Member_Tab_Controller extends Controller_Widget
{    
    public function actionAdd()
    {
        $request = $this->getRequest();
        
        // 未定义Url,返回失败结果
        if (!$request->post('url')) {
            return $this->_view->displayJson(array(
                'result' => false,
                'message' => '',
            ));
        }

        // 未定义标题,使用"无标题"
        $title = $request->post('title') ? $request->post('title') : $this->_lang['UNTITLED'];
        
        $session = $this->getSession();
        $tabs = (array)$session['tabs'];
        // 将Url和标题加入Session中
        $tabs[$request->post('url')] = array(
            'url' => $request->post('url'),
            'title' => $title,
        );
        $session['tabs'] = $tabs;
        
        // 返回结果
        return $this->_view->displayJson(array(
            'result' => true,
        ));
    }
    
    public function actionRemove()
    {
        $request = $this->getRequest();
        $url = $request->post('url');

        // 未定义Url,返回失败结果
        if (!$url) {
            return $this->_view->displayJson(array(
                'result' => false,
                'message' => '',
            ));
        }
        
        // 如果存在指定选项卡则删除
        $session = $this->getSession();
        $tabs = (array)$session['tabs'];
        if (isset($tabs[$url])) {
            unset($tabs[$url]);
        }
        $session['tabs'] = $tabs;
        
        // 返回结果
        return $this->_view->displayJson(array(
            'result' => true,
        ));
    }
}