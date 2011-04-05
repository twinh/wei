<?php
/**
 * Controller
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @package     Com
 * @subpackage  Controller
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-28 15:19:18
 */

/**
 * @see Qwin_Application_Controller
 */
require_once 'Qwin/Application/Controller.php';

class Com_Controller extends Qwin_Application_Controller
{
    /**
     * 模型对象
     * @var Com_Model
     */
    protected $_model;

    /**
     * 元数据对象
     * @var Com_Metadata
     */
    protected $_meta;

    /**
     * 语言对象
     * @var Qwin_Application_Language
     */
    protected $_lang;

    /**
     * 请求对象
     * @var Com_Request
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
     * 初始化各类和数据
     */
    public function __construct($config = array(), $module = null, $action = null)
    {
        $this->_module  = $module;
        $this->_action  = $action;

        $this->_view = Qwin::call('Com_View');
        $this->getRequest();
         /**
         * 访问控制
         */
        //$this->_isAllowVisited();
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
     * 是否有权限浏览该页面
     *
     * @return boolen
     */
    protected function _isAllowVisited()
    {
        $session = $this->session;
        $member = $session->get('member');
        $metaHelper = Qwin::call('Qwin_Application_Metadata');

        // 未登陆则默认使用游客账号
        if(null == $member)
        {
            $asc = array(
                'package' => 'Common',
                'module' => 'Member',
                'controller' => 'Member',
            );
            $result = $metaHelper
                ->getQueryByAsc($asc, array('db', 'view'))
                ->where('username = ?', 'guest')
                ->fetchOne();
            $member = $result->toArray();
            
            $session
                ->set('member',  $member)
                ->set('permisson', $member['group']['permission'])
                ->set('style', $member['theme'])
                ->set('language', $member['language']);
        }

        // 逐层权限判断
        $asc = $this->config['asc'];
        $permission = @unserialize($member['group']['permission']);
        if(isset($permission[$asc['package']]))
        {
            return true;
        }
        if(isset($permission[$asc['package'] . '|' . $asc['module']]))
        {
            return true;
        }
        if(isset($permission[$asc['package'] . '|' . $asc['module'] . '|' . $asc['controller']]))
        {
            return true;
        }
        if(isset($permission[$asc['package'] . '|' . $asc['module'] . '|' . $asc['controller'] . '|' . $asc['action']]))
        {
            return true;
        }

        if('guest' == $member['username'])
        {
            Qwin::call('#view')->jump($this->url->url(array(
                'module' => 'Member',
                'controller' => 'Log',
                'action' => 'Login',
            )));
        } else {
            $this->view->alert($this->_lang->t('MSG_PERMISSION_NOT_ENOUGH'));
        }
        return false;
    }
}
