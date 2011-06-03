<?php
/**
 * Controller
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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
 */

class Controller_Widget extends Qwin_Widget_Abstract
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
     * 语言对象
     * @var Qwin_Application_Language
     */
    protected $_lang;

    /**
     * 请求对象
     * @var Qwin_Request
     */
    protected $_request;

    /**
     * 视图对象
     * @var Com_View
     */
    protected $_view;

    /**
     * Url对象
     * @var Qwin_Url
     */
    protected $_url;

    /**
     * 会话对象
     * @var Qwin_Session
     */
    protected $_session;
    
    /**
     * 微件管理对象
     * @var Qwin_Widget
     */
    protected $_widget;

    /**
     * 用户数据数组
     * @var array
     */
    protected $_member;

    /**
     * 模块标识
     * @var string
     */
    protected $_module;

    /**
     * 操作名称
     * @var string
     */
    protected $_action;

    /**
     * 禁用的行为列表
     * 当行为被禁用时,无法通过外部进行访问
     * 通过禁用行为,可以用于精确的访问控制
     *
     * @var array
     */
    protected $_unableActions = array();

    /**
     * 根据模块获取控制器对象
     *
     * @param string $module 模块标识
     * @return Qwin_Application_Controller 控制器对象
     * @todo 当类不存在时,是否需要用父类?
     * @todo 是否应该考虑存在性,安全性
     */
    public static function getByModule($module, $instanced = true, $param = null)
    {
        if ($module instanceof Qwin_Module) {
            $class = $module->toClass();
        } else {
            $class = Qwin_Module::instance($module)->toClass();
        }
        $class .= '_Controller';
        return $instanced ? Qwin::call($class, $param) : $class;
    }

    /**
     * 获取禁用的行为列表
     *
     * @return array
     */
    public function getForbiddenActions()
    {
        return $this->_unableActions;
    }

    /**
     * 设置禁用行为
     *
     * @param string $action 行为名称,即方法名去除'action'标识
     * @return Qwin_Application_Controller 当前对象
     */
    public function setForbiddenActions($action)
    {
        if (method_exists($this, 'action' . $action)) {
            $this->_unableActions[] = strtolower($action);
        }
        return $this;
    }

    /**
     * 获取视图对象
     *
     * @param string $class 新的视图类名,可选
     * @return Qwin_Application_View
     */
    public function getView($class = null)
    {
        return $this->getWidget()->call('View');
    }
    
    /**
     * 初始化各类和数据
     */
    public function __construct($config = array(), $module = null, $action = null)
    {
        $this->_module  = $module;
        $this->_action  = $action;

        $this->_view = $this->getWidget()->call('View');
        //$this->_view = Qwin::call('Com_View');
        $this->getRequest();
         /**
         * 访问控制
         */
        $this->_isAllowVisited();
    }

    /**
     * 获取请求对象
     *
     * @return Qwin_Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = Qwin::call('-request');
        }
        return $this->_request;
    }
    
    /**
     * 获取Url对象
     * @return Qwin_Url
     */
    public function getUrl()
    {
        if (!$this->_url) {
            $this->_url = Qwin::call('-url');
        }
        return $this->_url;
    }

    /**
     * 获取会话对象
     * @return Qwin_Session
     */
    public function getSession()
    {
        if (!$this->_session) {
            $this->_session = Qwin::call('-session');
        }
        return $this->_session;
    }

    /**
     * 获取用户数据
     *
     * @return array
     */
    public function getMember()
    {
        if (!$this->_member) {
            $this->_member = $this->getSession()->get('member');
        }
        return $this->_member;
    }

    /**
     * 获取元数据对象
     *
     * @return Com_Meta
     */
    public function getMeta()
    {
        if (!$this->_meta) {
            $this->_meta = Com_Meta::getByModule($this->_module);
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
     * 获取微件管理对象
     * 
     * @return Qwin_Widget
     */
    public function getWidget()
    {
        if (!$this->_widget) {
            $this->_widget = Qwin::call('-widget');
        }
        return $this->_widget;
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
            $member = Com_Meta::getByModule('com/member')->get('db')->getQuery()
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