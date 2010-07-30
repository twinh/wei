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
 * @subpackage  Miku
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */


class Qwin_Miku_Init
{
    /**
     * 存放加载了的命名空间的名称,用于检查是否加载过
     * @var array
     */
    private $_isNamespaceLoaded = array();

    /**
     * 由命名空间,模块,控制器,动作组成的配置数组
     * @var array
     */
    private $_set = array();

    /**
     * 命名空间的实例化对象,方便引用
     * @var object
     */
    public  $_namespace;

    /**
     * 是否加载了404页面的标识
     * @var bool
     */
    private $_isLoad404 = false;
    
    /**
     * 配置数组
     * @var array
     */
    private $_config;

    /**
     * 初始化
     *
     * @param array $config
     * @param array $set
     * @todo 配置
     */
    public function __construct($config, $set = NULL)
    {
        $this->_config = $config;

        // 加载框架主类,设置自动加载类
        require_once QWIN_LIB_PATH . '/Qwin.php';
        Qwin::setAutoload();

        // 注册初始化类
        Qwin::addMap(array(
            '-ini'  =>  __CLASS__,
            '-url'  => 'Qwin_Url',
            '-arr'  => 'Qwin_Helper_Array',
            // 当前命名空间类
            '-n'    => '',
            // 当前控制器类
            '-c'    => '',
            // 当前模型类
            '-m'    => '',
        ));
        Qwin::addClass(__CLASS__, $this);
        
        // 设置错误提示输出等级
        error_reporting($config['error_type']);

        // 设置会话类型及启动
        if($config['session']['setup'])
        {
            session_cache_limiter($config['session']['type']);
            session_start();
        }
        
        // 默认时区
        date_default_timezone_set($config['i18n']['timezone']);

        // 关闭魔术引用
        ini_set('magic_quotes_runtime', 0);
        
        // 初始化 url 参数,必须在转义后
        Qwin::run('-url');
        $this->_loadNmcv($set);
    }

    /**
     * 加载命名空间(n), 控制器(c), 视图(v)
     * 
     * @param array $set 配置数组
     */
    private function _loadNmcv($set = NULL)
    {
        // TODO
        // 转换配置数组
        $url_set = Qwin::run('-url')->getNca(array('namespace', 'module', 'controller', 'action'));
        foreach($url_set as $key => $val)
        {
            !isset($set[$key]) && $set[$key] = $val ? $val : 'Default';
            $set[$key] = $this->secureFileName(ucfirst($set[$key]));
        }

        $this->_set = &$set;
        // 加载命名空间
        $this->_loadNamespace($set);
        // 加载模块
        $this->_loadModule($set);
        // 加载控制器
        $this->_loadController($set);
        // 加载视图
        $this->_loadView($set);
        $this->_loadNamespaceMethod('afterLoad');
    }

    /**
     * 加载命名空间文件
     *
     * @param array $set 配置数组
     * @return null
     */
    private function _loadNamespace($set, $type = 'main')
    {
        if(!in_array($set['namespace'], $this->_config['allowedNamespace']))

        $namespace_name = $set['namespace'] . '_Namespace';
        if(!isset($this->_isNamespaceLoaded[$set['namespace']]))
        {
            //if('main' == $type)
            //{
                Qwin::addMap('-n', $namespace_name);
                $this->_namespace = Qwin::run('-n');
            //}
            // 执行 beforeLoad 方法,使程序可自由扩展
            // 比如,在该函数中加入 acl 访问控制, 缓存控制等
            $this->_loadNamespaceMethod('beforeLoad');
            $this->_isNamespaceLoaded[$set['namespace']] = true;
        }
    }

    /**
     * 加载命名空间类中的方法
     *
     * @param string $name 方法的名称
     */
    private function _loadNamespaceMethod($name)
    {
        method_exists($this->_namespace, $name) && 
        call_user_func_array(
            array($this->_namespace, $name),
            array(&$this->_set, &$this->_config)
        );
    }

    /**
     * 加载模块
     *
     * @param array $set 配置数组
     * @return <type>
     */
    private function _loadModule($set)
    {
        $class = $set['namespace'] . '_' . $set['module'] . '_Module';
        return Qwin::run($class);
    }

    /**
     * 加载控制器文件
     *
     * @param array $set 配置数组
     * @return null
     */
    private function _loadController($set)
    {
        /**
         * 构建控制器的文件并加载
         */
        Qwin::load('Qwin_Miku_Controller');
        Qwin::load('Default_Controller');
        Qwin::load('Default_Metadata');
        $controller_name = $this->getClassName('Controller', $set);
        $action = 'action' . $set['action'];
        $controller = Qwin::run($controller_name);
        /**
         * 控制器和方法均不存在
         */
        if(null == $controller || !method_exists($controller, $action))
        {
            // 加载当前命名空间的HttpError模块
            if('Default' != $set['namespace'])
            {
                $set['module'] = 'HttpError';
                $set['controller'] = 'Default';
                $set['action'] = '404';
                if(false == $this->_isLoad404)
                {
                    $this->_isLoad404 = true;
                } else {
                    $set['namespace'] = 'Default';;
                }
                $this->_loadNmcv($set);
                return true;
            } else {
                $controller_name = 'Qwin_Miku_Controller';
                $action = '__error';
                $controller = Qwin::run($controller_name);
            }
        }
        
        /**
         * 控制器初始化完毕,加载命名空间类的 onLoad 方法,可在其中对控制器进行管理
         */
        // 方便外部调用
        Qwin::addMap('-c', $controller_name);
        Qwin::addClass($controller_name, $controller);
        $controller->__query = $set;
        $this->_loadNamespaceMethod('onLoad');
        call_user_func(array($controller, $action));
    }

    /**
     * 加载视图文件
     *
     * @param array $set 配置数组
     * @param null/object $class 控制器类
     * @return null
     */
    private function _loadView($set, $controller = NULL)
    {
        // 加载视图
        NULL == $controller && $controller = Qwin::run('-c');
        $controller->loadView($set);
    }
    
    /**
     * 加载 php 文件
     * @param string $path 加载文件的伪路径
     * @param bool $is_load 是否加载,是则直接加载,否则返回文件路径
     * @todo '*'通配符. 路径加解码方法 encodePath & decodePath
     */
    public static function load($path, $is_loaded = true)
    {
        $path = explode('/', $path);
        switch($path[0])
        {
            case '' :
            case 'Framework' :
                $path[0] = QWIN_PATH . '/library';
                break;
            case 'Resource' :
                $path[0] = RESOURCE_PATH . '/php';
                break;
            case 'App' :
                $path[0] = ROOT_PATH;
                break;
        }
        foreach($path as &$val)
        {
            $val == '' && $val = 'default';
        }
        $file = implode('/', $path) . '.php';
        if(true == $is_loaded)
        {
            return require $file;
        }
        return $file;
    }
    
    public function loadSubNcsv($set)
    {
        //echo '加载子控制器';
        //var_dump($set);
        $set = array(
            'namespace' => $set[0] == '' ? 'Default' : $set[0],
            'controller' => $set[1] == '' ? 'Default' : $set[1],
            'action' => $set[2] == '' ? 'Default' : $set[2],
        );

        // 加载命名空间
        //$this->_loadNamespace($set, 'sub');

        // 加载控制器
        $controller_name = $set['namespace'] . '_Controller_' . $set['controller'];
        $action = 'subAction' . $set['action'];
        $controller = Qwin::run($controller_name);
        if(NULL != $controller)
        {
            call_user_func(array($controller, $action));
            // 加载视图
            $this->_loadView($set, $controller);
        }
    }

    /**
    * 将提供的字符串转化为安全文件名称
    *
    * @param string $path 文件名称
    * @return string 安全的文件名称
    * @todo 只允许字母,数字
    */
    public function secureFileName($path)
    {
        return str_replace(array('-', '\\', '/', ':', '*', '<', '>', '?', '|'), '', $path);
    }

    /**
     * 获取配置数组
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * 获取应用中类名
     * @param string $addition 附加的字符串
     * @param array $set 配置数组
     * @return string 类名
     */
    public function getClassName($addition, $set)
    {
        return $set['namespace'] . '_' . $set['module']
                . '_' . $addition . '_' . $set['controller'];
    }
}

function qw($class, $param = NULL)
{
    return Qwin::run($class, $param);
}

function qwForm($param, $param_2 = NULL)
{
    return qw('-form')->auto($param, $param_2);
}

function p($a)
{
    echo '<p><pre>';
    qw('Qwin_Debug')->p($a);
    echo '</pre><p>';
}

function e($msg = '')
{
    qw('Qwin_Debug')->e($msg);
}
