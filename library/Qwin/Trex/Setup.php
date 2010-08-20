<?php
/**
 * Init
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
 * @subpackage  Trex
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 * @todo        只运行一次
 */

class Qwin_Trex_Setup
{
    /**
     * 指定加载的命名空间的名称
     */
    //const DEFAULT_NAMESPACE = 'Trex';

    /**
     * 由命名空间,模块,控制器,行为组成的配置数组
     * @var array
     */
    protected $_set = array();

    /**
     * 控制器父类的实例化对象
     * @var object
     */
    protected $_parentController;

    /**
     * 命名空间的实例化对象
     * @var object
     */
    protected  $_namespace;

    /**
     * 模块的实例化对象
     * @var object
     */
    protected $_module;

    /**
     * 控制器的实例化对象
     * @var object
     */
    protected $_controller;

    /**
     * 配置数组
     * @var array
     */
    protected $_config;

    /**
     * 编码后的配置数组
     */
    protected $_encodedConfig;

    /**
     * Qwin_Request对象
     */
    protected $_request;

    /**
     * 解析配置,初始化并构建程序
     *
     * @param array $config
     * @param array $set
     * @todo 配置
     */
    public function __construct($config, $set = null)
    {
        $this->_config = $config;

        // 加载Qwin函数库
        require_once QWIN_LIB_PATH . '/function/qwin.php';

        // 加载框架主类,设置自动加载类
        require_once QWIN_LIB_PATH . '/Qwin.php';
        Qwin::setAutoload();
        Qwin::setCacheFile(QWIN_ROOT_PATH . '/cache/php/class.php');
        
        $this->_request = Qwin::run('Qwin_Request');

        // 注册初始化类
        Qwin::addClass(__CLASS__, $this);

        /**
         * 根据配置数组设置初始化各设置
         */
        // 设置错误提示输出等级
        error_reporting($config['errorType']);

        // 设置会话类型及启动
        if($config['session']['setup'])
        {
            session_cache_limiter($config['session']['type']);
            session_start();
        }
        
        // 默认时区
        date_default_timezone_set($config['interface']['timezone']);

        // 关闭魔术引用
        ini_set('magic_quotes_runtime', 0);
        
        // 初始化 url 参数,必须在转义后
        //Qwin::run('Qwin_Url');

        /**
         * 通过配置数据和Url参数初始化系统配置
         * 系统配置包括命名空间,模块,控制器,行为
         */
        $this->_set = &$set;
        $defaultSet = array(
            'namespace',
            'module',
            'controller',
            'action',
        );
        $urlSet = $this->_request->get($defaultSet);
        foreach($defaultSet as $field)
        {
            if(!isset($set[$field]))
            {
                $set[$field] = null != $urlSet[$field] ? $urlSet[$field] : 'Default';
            }
        }
        
        /**
         * 加载命名空间
         * 如果是配置文件中不允许的命名空间,则抛出异常
         */
        if(!in_array($set['namespace'], $this->_config['allowedNamespace']))
        {
            require_once 'Qwin/Trex/Setup/Exception.php';
            throw new Qwin_Trex_Setup_Exception('The namespace ' . $set['namespace'] . ' is not allowed.');
        }

        /**
         * 初始命名空间类,并加入类管理器中
         */
        $namespaceClass = $set['namespace'] . '_Namespace';
        $this->_namespace = Qwin::run($namespaceClass);
        null == $this->_namespace && $this->_onNamespaceNotExists($set['namespace']);
        Qwin::addMap('-namespace', $namespaceClass);
        $this->_onNamespaceLoad($this->_namespace);

        /**
         * 初始模块类,并加入类管理器中
         */
        $moduleClass = $set['namespace'] . '_' . $set['module'] . '_Module';
        $this->_module = Qwin::run($moduleClass);
        if(null == $this->_module)
        {
            $moduleClass = 'Qwin_Trex_Module';
            $this->_module = Qwin::run('Qwin_Trex_Module');
            $this->_onModuleNotExists($set['module']);
        }
        Qwin::addMap('-module', $moduleClass);
        $this->_onModuleLoad($this->_module);

        /**
         * 初始控制器类,并加入类管理器中
         */
        $controllerClass = $this->getClassName('Controller', $set);
        $this->_controller = Qwin::run($controllerClass);
        if(null == $this->_controller)
        {
            $controllerClass = 'Qwin_Trex_Controller';
            $this->_controller = Qwin::run('Qwin_Trex_Controller');
            $this->_onControllerNotExists($set['controller']);
        }
        Qwin::addMap('-controller', $controllerClass);
        $this->_onControllerLoad($this->_controller);

        /**
         * 执行指定的行为
         */
        $action = 'action' . $set['action'];
        if(method_exists($this->_controller, $action))
        {
            call_user_func_array(
                array($this->_controller, $action),
                array(&$set, &$this->_config)
            );
        } else {
            $this->_onActionNotExists($set['action']);
        }

        /**
         * 加载视图
         */
        return $this->_controller->loadView()->display();
    }

    /**
     * 获取网站配置数组
     * 
     * @return array 网站配置数组
     */
    public function getConfig($name = null)
    {
        if(isset($this->_encodedConfig[$name]))
        {
            return $this->_encodedConfig[$name];
        }

        $keyArray = explode('.', $name);
        $tempConfig = $this->_config;
        foreach($keyArray as $key)
        {
            if(isset($tempConfig[$key]))
            {
                $tempConfig = $tempConfig[$key];
            }
        }
        return $this->_encodedConfig[$name] = $tempConfig;
    }

    /**
     * 设置网站配置数组
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * 获取配置数组
     *
     * @return array 配置数组
     */
    public function getSet()
    {
        return $this->_set;
    }

    /**
     * 获取标准的类名
     *
     * @param string $addition 附加的字符串
     * @param array $set 配置数组
     * @return string 类名
     */
    public function getClassName($addition, $set)
    {
        return $set['namespace'] . '_' . $set['module'] . '_' . $addition . '_' . $set['controller'];
    }

    /**
     * 当找不到命名空间类时,执行改方法
     *
     * @param string|null $name 命名空间名称
     * @return boolen 方法执行情况
     */
    protected function _onNamespaceNotExists($name = null)
    {
        return false;
    }

    /**
     * 当找不到模块类时,执行该方法
     *
     * @param string|null $name 模块名称
     * @return boolen 方法执行情况
     */
    protected function _onModuleNotExists($name = null)
    {
        return false;
    }

    /**
     * 当找不到控制器时,执行该方法
     *
     * @param string|null $name 控制器名称
     * @return boolen 方法执行情况
     */
    protected function _onControllerNotExists($name = null)
    {
        return false;
    }

    /**
     * 当找不到行为时,执行该方法
     *
     * @param string|null $name 行为名称
     * @return boolen 方法执行情况
     */
    protected function _onActionNotExists($name = null)
    {
        return false;
    }

    /**
     * 命名空间加载时执行该方法
     *
     * @param object|null $namespace
     * @return boolen
     */
    protected function _onNamespaceLoad($namespace)
    {
        return false;
    }

    /**
     * 模块加载时执行该方法
     *
     * @param object|null $module
     * @return boolen
     */
    protected function _onModuleLoad($module)
    {
        return false;
    }

    /**
     * 控制器加载时,执行该方法
     *
     * @param object|null $controller
     * @return boolen
     */
    protected function _onControllerLoad(Qwin_Trex_Controller $controller)
    {
        return false;
    }

    /**
     * 输出调试信息
     */
    public function debug()
    {
        $namesapce = null == $this->_namespace ? 'null' : get_class($this->_namespace);
        $module = null == $this->_module ? 'null' : get_class($this->_module);
        $controller = null == $this->_controller ? 'null' : get_class($this->_controller);

        echo '<p>The Namespace is <strong>' . $namesapce . '</strong></p>';
        echo '<p>The Module is <strong>' . $module . '</strong></p>';
        echo '<p>The Controller is <strong>' . $controller . '</strong></p>';
        echo '<p>The Action is <strong>' . $this->_set['action'] . '</strong></p>';
    }
}
