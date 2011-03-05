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
     * 配置选项
     * @var array
     */
    protected $_option = array(
        
    );

    /**
     * 视图的实例化对象
     * @var Qwin_Application_View
     */
    protected $_view;
    
    /**
     * 命名空间的实例化对象
     * @var Qwin_Application_Package
     */
    protected $_package;

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
     * 本方法定义了应用程序的加载流程,按顺序为命名空间,模块,控制器,行为.
     * 
     * @param array $config 配置选项
     * @return Qwin_Application_Startup 当前对象
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
        require_once $config['resource'] . '/library/Qwin.php';
        Qwin::setOption($config['Qwin']);
        Qwin::config($config);
        
        // 加载Qwin函数库
        require_once $config['resource'] . '/library/function/qwin.php';

        // 注册当前类
        Qwin::set('-app', $this);

        // 跳转到默认首页
        if (empty($_SERVER['QUERY_STRING'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('location: ' . $config['index']);
        }
        // 启动Url路由
        /*$router = null;
        if ($config['router']['enable']) {
            $router = Qwin::call('Qwin_Url_Router');
            $router->add($config['router']['list']);
        }
        $url = Qwin::call('-url', $router);*/

        // 加载视图
        $this->_view = new Qwin_Application_View();
        Qwin::set('-view', $this->_view);
        
        // 通过配置数据和Url参数初始化系统配置(包括命名空间,模块,控制器,行为等)
        if (empty($_SERVER['QUERY_STRING'])) {
            //$_GET = $url->parse($config['index']['url']);
        }
        foreach ($config['defaultAsc'] as $name => $value) {
            $asc[$name] = isset($_GET[$name]) ? $_GET[$name] :  $value;
            $asc[$name] = basename(str_replace('_', '', $asc[$name]));
        }
        
        Qwin::config('asc', $asc);

        // 检查命名空间是否存在,存在则加载
        $packageList = Qwin_Application_Package::getList($config['Qwin']['autoloadPath']);
        if (!isset($packageList[$asc['package']])) {
            return $this->_onPackageNotExists($asc);
        }
        Qwin_Application_Package::getByAsc($asc);

        // 加载模块
        Qwin_Application_Module::getByAsc($asc);

        // 加载控制器
        $controller = Qwin_Application_Controller::getByAsc($asc);
        if (null == $controller) {
            return $this->_onControllerNotExists($asc);
        }

        // 执行行为
        $action = 'action' . $asc['action'];
        if (method_exists($controller, $action)
            && !in_array(strtolower($asc['action']), $controller->getForbiddenAction())) {
            call_user_func_array(
                array($controller, $action),
                array(&$asc, &$config)
            );
        } else {
            return $this->_onActionNotExists($asc);
        }

        // 展示视图,视图对象可能在行为操作中已被更改,需进行辨别
        if (is_subclass_of($this->_view, 'Qwin_Application_View')) {
            $this->_view->display();
        }
        
        return $this;
    }

    public function getView(array $asc = null)
    {
        if (null == $asc) {
            return $this->_module;
        }
        return Qwin::call($this->getClass('view', $asc));
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

    /**
     * 命名空间不存在
     *
     * @param array $asc 应用结构配置
     */
    public function _onPackageNotExists($asc)
    {
        exit('The package "' . $asc['package'] . '" is not exists.');
    }

    /**
     * 控制器不存在
     *
     * @param array $asc 应用结构配置
     */
    public function _onControllerNotExists($asc)
    {
        exit('The controller "' . $asc['controller'] . '" is not exists.');
    }

    /**
     * 行为不存在
     *
     * @param array $asc 应用结构配置
     */
    public function _onActionNotExists($asc)
    {
        exit('The action "' . $asc['action'] .  '" is not exists');
    }
}
