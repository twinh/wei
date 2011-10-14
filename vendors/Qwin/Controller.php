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
     * 模型对象
     * @var Com_Model
     */
    protected $_model;

    /**
     * 元数据对象
     * @var Com_Meta
     */
    protected $_meta;

    /**
     * 用户数据数组
     * @var array
     */
    protected $_member;

    /**
     * 初始化各类和数据
     */
    public function __construct()
    {
        parent::__construct();
        
         /**
         * 访问控制
         */
        //$this->_isAllowVisited();
    }

    /**
     * 根据模块获取控制器对象
     * 
     * @param string $module 模块名称
     * @param bool $instance 是否实例化
     * @param mixed $param 参数
     * @return Qwin_Controller
     */
    public function getByModule($module, $instance = true, $param = null)
    {
        // 初始化模块类
        if (!$module instanceof Qwin_Module) {
            $module = Qwin_Module::instance($module);
        }
        
        // 检查模块控制器文件是否存在
        $found = false;
        foreach ($this->app->option('paths') as $path) {
            $file = $path . $module->toPath() . '/Controller.php';
            if (is_file($file)) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new Qwin_Exception('Module "' . $module . '" not found.');
        }
        
        require_once $file;
        $class = $module->toClass() . '_Controller';
        if (!class_exists($class)) {
            throw new Qwin_Exception('Controller class "' . $class . '" not found.');
        }
        
        return $instance ? $this->qwin($class, $param) : $class;
    }

    /**
     * 获取用户数据
     *
     * @return array
     */
    public function getMember()
    {
        if (!$this->_member) {
            $this->_member = $this->session->get('member');
        }
        return $this->_member;
    }

    /**
     * 获取元数据对象
     *
     * @return Meta_Widget
     */
    public function getMeta()
    {
        if (!$this->_meta) {
            $this->_meta = Qwin_Meta::getByModule($this->_module);
        }
        return $this->_meta;
    }

    /**
     * 获取模型对象
     *
     * @return Com_Model
     */
    public function getModel()
    {
        if (!$this->_model) {
            $this->_model = Com_Model::getByModule($this->_model);
        }
        return $this->_model;
    }

    /**
     * 是否有权限浏览该页面
     *
     * @return boolen
     */
    protected function _isAllowVisited()
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
    }
}
