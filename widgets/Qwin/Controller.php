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
 * @package     Qwin
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 * @todo        重新实现禁用行为
 */

class Qwin_Controller extends Qwin_Widget
{
    /**
     * 根据模块获取控制器对象
     * 
     * @param string $module 模块名称
     * @param bool $instance 是否实例化
     * @param mixed $param 参数
     * @return Qwin_Controller
     */
    public function call($module = null, $instance = true, $param = null)
    {
        !$module && $module = $this->module();

        // 检查模块控制器文件是否存在
        $found = false;
        foreach ($this->app->options['dirs'] as $dir) {
            $file = $dir . $module->toPath() . '/Controller.php';
            if (is_file($file)) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $this->exception('Module "%s" not found.', $module);
        }
        
        require_once $file;
        $class = $module->toClass() . '_Controller';
        if (!class_exists($class)) {
            $this->exception('Controller class "%s" not found.', $class);
        }
        
        return $instance ? $this->qwin($class, $param) : $class;
    }
    
    /**
     * 执行行为
     * 
     * @param string $action 行为名称
     * @return mixed 
     */
    public function action($action)
    {
        $action = (string)$action;
        if ($action) {
            $action2 = $action . 'Action';
            if (method_exists($this, $action2)) {
                return call_user_func(array($this, $action2));
            }
        }
        
        $this->exception('Action "%s" not found in controller %s.', $action, get_class($this));
    }

    /**
     * 是否有权限浏览该页面
     *
     * @return boolen
     */
    /*protected function _isAllowVisited()
    {
        $session = $this->getSession();
        $member = $session->get('member');

        // 未登陆则默认使用游客账号
        if (null == $member) {
            $member = Query_Widget::getByModule('member')
                ->where('username = ?', 'guest')
                ->fetchOne()
                ->toArray();

            // 设置登陆信息
            $session
                ->set('member',  $member)
                //->set('permisson', $member['group']['permission'])
                ->set('style', $member['theme'])
                ->set('lang', $member['language']);
        }

        // TODO 修复权限管理
//        if ('guest' == $member['username'] && $this->_module != 'com/member/auth') {
//            $lang = Qwin::call('-lang');
//            return $this->_View->alert($lang->t('MSG_PERMISSION_NOT_ENOUGH'));
//        }
        return true;
    }*/
}
