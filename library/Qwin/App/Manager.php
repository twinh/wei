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

class Qwin_App_Manager
{
    /**
     * 启动器的实例化对象
     *
     * @var Qwin_App_Setup
     */
    protected static $_instance;

    /**
     * 应用是否已加载,即调用过startup方法
     * @var boolen
     */
    protected $_isLoad = false;
    
    /**
     * 全局配置
     * @var Qwin_Config
     */
    protected $_config;

    /**
     * 配置选项
     * @var array
     */
    protected $_option = array(
        'viewClass' => 'Qwin_App_View',
    );

    /**
     * 视图的实例化对象
     * @var Qwin_App_View
     */
    protected $_view;
    
    /**
     * 命名空间的实例化对象
     * @var Qwin_App_Namespace
     */
    protected $_namespace;

    /**
     * 模块的实例化对象
     * @var Qwin_App_Module
     */
    protected $_module;

    /**
     * 控制器的实例化对象
     * @var Qwin_App_Controller
     */
    protected $_controller;

    /**
     * 合法的命名空间数组
     * @var array
     */
    protected $_validNamespace;
    

    /**
     * 构造方法,不允许继承,也不允许实例化
     */
    final protected function __construct()
    {
    }

    /**
     * 获取当前类的实例化对象(单例模式)
     *
     * @return Qwin_App_Manager
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
     * @return Qwin_App_Startup 当前对象
     */
    public function startup($config)
    {
        // 设置加载标识
        if ($this->_isLoad) {
            return false;
        }
        $this->_isLoad = true;

        // 合并配置
        $globalConfig = require_once QWIN_ROOT_PATH . '/config/global.php';
        $config = array_merge($config, $globalConfig);

        // 设置错误提示的输出等级
        error_reporting($config['errorType']);

        // 加载框架主类,设置自动加载类
        require_once QWIN_LIB_PATH . '/Qwin/Class.php';
        Qwin_Class::setAutoload($config['appPath']);
        Qwin_Class::setCacheFile(QWIN_ROOT_PATH . '/cache/php/class.php');
        // 加载Qwin函数库
        require_once QWIN_LIB_PATH . '/function/qwin.php';

        Qwin::setShortTag('@', 'Qwin_App_');
        
        $config = Qwin::run('@config', array($config));
        Qwin::set('-config', $config);
        $this->_config = &$config;

        // 注册当前类
        Qwin::set('-manager', $this);
        
        // 启动Url路由
        $router = null;
        if ($config['router']['enable']) {
            $router = Qwin::run('Qwin_Url_Router');
            $router->add($config['router']['list']);
        }
        $url = Qwin::run('-url', $router);

        // 加载视图
        $this->_view = Qwin::run($this->_option['viewClass']);
        Qwin::set('-view', $this->_view);
        
        // 通过配置数据和Url参数初始化系统配置(包括命名空间,模块,控制器,行为等)
        if (empty($_SERVER['QUERY_STRING'])) {
            $_GET = $url->parse($config['index']['url']);
        }
        foreach ($config['defaultAsc'] as $name => $value) {
            $asc[$name] = isset($_GET[$name]) ? $_GET[$name] :  $value;
            $asc[$name] = basename(str_replace('_', '', $asc[$name]));
        }
        empty($asc['module']) && $asc['module'] = $asc['controller'];
        empty($asc['controller']) && $asc['controller'] = $asc['module'];
        
        $config['asc'] = $asc;

        // 检查命名空间是否存在
        if (!in_array($asc['namespace'], $this->getValidNamespace())) {
            exit('The namespace "' . $asc['namespace'] . '" is not exists.');
        }
        $this->_namespace = $this->getNamespace($asc, $config);

        // 加载模块
        $this->_module = $this->getModule($asc);

        // 加载控制器
        $controller = $this->getController($asc);
        if (null == $controller) {
            exit('The controller "' . $asc['controller'] . '" is not exists.');
        }
        $this->_controller = $controller;

        // 执行行为
        $action = 'action' . $asc['action'];
        if (method_exists($controller, $action)
            && !in_array(strtolower($asc['action']), $controller->getForbiddenAction())) {
            call_user_func_array(
                array($controller, $action),
                array(&$asc, &$this->_config)
            );
        } else {
            exit('The action "' . $asc['action'] .  '" is not exists');
        }

        // 使用Qwin获取视图对象并展示(因为此时视图对象可能已改变)
        Qwin::run('-view')->display();

        return $this;
    }

    /**
     * 从项目目录中获取合法的命名空间名称
     *
     * @return array
     */
    public function getValidNamespace()
    {
        if (isset($this->_validNamespace)) {
            return $this->_validNamespace;
        }
        $this->_validNamespace = array();
        foreach ($this->_config['appPath'] as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            foreach (scandir($dir) as $file) {
                if ('.' != $file[0] && is_dir($dir . '/' . $file)) {
                    $this->_validNamespace[] = $file;
                }
            }
        }
        return $this->_validNamespace;
    }

    /**
     * 获取命名空间对象
     *
     * @param array $asc 应用结构配置
     * @return Qwin_App_Namespace
     */
    public function getNamespace(array $asc = null)
    {
        if (null == $asc) {
            return $this->_namespace;
        }
        return Qwin::run($this->getClass('namespace', $asc));
    }

    /**
     * 获取模块对象
     *
     * @param array $asc 应用结构配置
     * @return Qwin_App_Module
     */
    public function getModule(array $asc = null)
    {
        if (null == $asc) {
            return $this->_module;
        }
        return Qwin::run($this->getClass('module', $asc));
    }

    /**
     * 获取控制器
     *
     * @param array $asc 应用结构配置
     * @return Qwin_App_Controller
     */
    public function getController(array $asc = null)
    {
        if (null == $asc) {
            return $this->_controller;
        }
        return Qwin::run($this->getClass('controller', $asc));
    }

    public function getView(array $asc = null)
    {
        if (null == $asc) {
            return $this->_module;
        }
        return Qwin::run($this->getClass('view', $asc));
    }

    public function getModel(array $asc = null)
    {
        
    }
    
    /**
     * 获取视图类名
     *
     * @param string $type 应用结构配置类型
     * @param array $asc 应用结构配置
     * @return string 类名
     */
    public function getClass($type, $asc)
    {
        switch ($type) {
            case 'namespace' :
                return $asc['namespace'] . '_Namespace';
            case 'view' :
                return $asc['namespace'] . '_View';
            case 'controller' :
                return $asc['namespace'] . '_' . $asc['module'] . '_Controller_' . $asc['controller'];
            case 'module' :
                return $asc['namespace'] . '_' . $asc['module'] . '_Module';
            default:
                return false;
        }
    }    

    public function getMetadata()
    {

    }

    /**
     * 获取助手类
     *
     * @param string $name 助手类名称
     * @param string $namespace 助手类所在的命名空间
     * @return object 助手类对象
     */
    public function getHelper($name, $namespace = null)
    {
        if (null == $namespace) {
            $namespace = $this->config['asc']['namespace'];
        }
        $class = $namespace . '_Helper_' . $name;
        return Qwin::run($class);
    }

    public function getWidget()
    {
        
    }

    public function getService()
    {
        
    }
}
