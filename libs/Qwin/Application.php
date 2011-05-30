<?php
/**
 * 应用程序启动类,通过该类指定应用程序需要加载的结构(如命名空间,控制器等),同时初始化最基本的对象
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

class Qwin_Application
{
    /**
     * 启动器的实例化对象
     *
     * @var Qwin_Application_Setup
     */
    protected static $_instance;

    /**
     * 应用是否已加载,即调用过startup方法
     * @var boolen
     */
    protected $_isLoad = false;
    
    /**
     * 选项
     * @var array
     */
    protected $_options = array();

    /**
     * 视图的实例化对象
     * @var Qwin_Application_View
     */
    protected $_view;
    
    /**
     * 模块的实例化对象
     * @var Qwin_Application_Module
     */
    protected $_module;

    /**
     * 控制器的实例化对象
     * @var Qwin_Application_Controller
     */
    protected $_controller;

    /**
     * 启动时间
     * @var string
     */
    protected $_startTime;
    
    /**
     * 构造方法,不允许继承,也不允许通过外部实例化
     */
    final protected function __construct()
    {
    }

    /**
     * 获取当前类的实例化对象(单例模式)
     *
     * @return Qwin_Application
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 启动应用程序
     * 
     * @param array $config 配置
     * @return Qwin_Application 当前对象
     */
    public function startup(array $config)
    {
        // 设置加载标识,防止二次加载
        if ($this->_isLoad) {
            return false;
        }
        $this->_isLoad = true;

        // 设置启动时间
        $this->_startTime = microtime(true);

        // 合并配置
        $globalConfig = require_once $config['root'] . 'config/global.php';
        $config = $config + $globalConfig;

        // 设置错误提示的输出等级
        error_reporting($config['errorType']);

        // 默认时区
        date_default_timezone_set($config['timezone']);

        // 加载框架主类,设置自动加载类
        require_once $config['resource'] . 'libs/Qwin.php';
        Qwin::setOption($config['Qwin']);
        $config = Qwin::config($config);

        // 注册当前类
        Qwin::set('-app', $this);

        // 设置应用启动钩子
        Qwin::hook('AppStartup');
        
        // 加载视图
        $this->_view = Qwin::call('Qwin_Application_View');
        //$this->_view = Qwin::call('-widget')->call('View');

        // 初始化请求
        $request = Qwin::call('-request');

        // 获取模块和行为
        $module = (string)$request->get('module');
        $action = (string)$request->get('action');
        /* @var $module Qwin_Module */
        $module = Qwin::call('-module', $module);
        Qwin::config('action', $action);

        // 逐层加载模块类
        if (false === Qwin_Application_Module::load($module)) {
            throw new Qwin_Application_Exception('Module "' . $module . '" not found.');
        }

        // 加载最终模块的控制器
        $params = array($config, $module, $action);
        $controller = Qwin_Application_Controller::getByModule($module, true, $params);
        if (!$controller) {
            throw new Qwin_Application_Exception('Controller in module "' . $module . '" not found.');
        }

        // 执行行为
        $actionName = 'action' . $action;
        if (method_exists($controller, $actionName)
            && !in_array(strtolower($action), $controller->getForbiddenActions())) {
            call_user_func_array(array($controller, $actionName), $params);
        } else {
            throw new Qwin_Application_Exception('Action "' . $action . '" not found in controller "' . get_class($controller) .  '"');
        }
        
        // 展示视图,视图对象可能已被更改,需进行辨别
        $this->_view->display();
        //Qwin::call('-widget')->call('View')->display();

        // 设置应用结束钩子
        Qwin::hook('AppTermination');
        
        return $this;
    }

    /**
     * 设置视图对象,方便第三方扩展,如Samrty
     *
     * @param object $view 视图对象
     */
    public function setView($view)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * 获取页面运行时间
     *
     * @return string
     */
    public function getEndTime()
    {
        return str_pad(round((microtime(true) - $this->_startTime), 4), 6, 0);
    }
}