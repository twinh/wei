<?php
/**
 * qinit 的名称
 *
 * qinit 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-10-31 01:19:04 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 */


class Qwin_Miku_Init
{
    /**
     * 存放加载了的命名空间的名称,用于检查是否加载过
     * @var array
     */
    private $_is_namespace_loaded = array();

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
    private $_is_load_404 = false;
    
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
        // 增加框架目录作为加载路径
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(QWIN_PATH));
        
        // 加载类管理类,并初始化
        require_once QWIN_PATH . '/Class.php';
        spl_autoload_register(array('Qwin_Class', 'autoload'));
        Qwin_Class::init();
        // 更新类名及文件的对应关系
        //if(isset($_GET['_update']))
        {
            Qwin_Class::update();
        }
        // 注册初始化类
        Qwin_Class::addMap(array(
            '-ini'  =>  __CLASS__,
            '-url'  => 'Qwin_Url',
            '-gpc'  => 'Qwin_Request',
            '-arr'  => 'Qwin_Helper_Array',
            '-gpc'  => 'Qwin_Request',
            // 当前命名空间类
            '-n'    => '',
            // 当前控制器类
            '-c'    => '',
            // 当前模型类
            '-m'    => '',
        ));
        Qwin_Class::addClass(__CLASS__, $this);
        
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
        /*if (!get_magic_quotes_gpc())
        {
            Qwin_Class::run('-arr')->multiMap($_POST, 'addslashes');
            Qwin_Class::run('-arr')->multiMap($_GET, 'addslashes');
            Qwin_Class::run('-arr')->multiMap($_COOKIE, 'addslashes');
        }*/
        //Qwin_Class::run('-arr')->multiMap($_FILES, 'addslashes');

        // 初始化 url 参数,必须在转义后
        Qwin_Class::run('-url');
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
        $url_set = Qwin_Class::run('-url')->getNca(array('namespace', 'module', 'controller', 'action'));
        foreach($url_set as $key => $val)
        {
            !isset($set[$key]) && $set[$key] = $val ? $val : 'Default';
            $set[$key] = $this->secureFileName(ucfirst($set[$key]));
        }
        if('Qwin' == $set['namespace'])
        {
            require_once 'Qwin/Miku/Init/Exception.php';
            throw new Qwin_Miku_Init_Exception('Namespace should not be "Qwin".');
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
        $namespace_name = $set['namespace'] . '_Namespace';
        if(!isset($this->_is_namespace_loaded[$set['namespace']]))
        {
            //if('main' == $type)
            //{
                Qwin_Class::addMap('-n', $namespace_name);
                $this->_namespace = Qwin_Class::run('-n');
            //}
            // 执行 beforeLoad 方法,使程序可自由扩展
            // 比如,在该函数中加入 acl 访问控制, 缓存控制等
            $this->_loadNamespaceMethod('beforeLoad');
            $this->_is_namespace_loaded[$set['namespace']] = true;
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
        return Qwin_Class::run($class);
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
        Qwin_Class::load('Qwin_Miku_Controller');
        Qwin_Class::load('Default_Controller');
        Qwin_Class::load('Default_Metadata');
        $controller_name = $this->getClassName('Controller', $set);
        $action = 'action' . $set['action'];
        $controller = Qwin_Class::run($controller_name);
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
                if(false == $this->_is_load_404)
                {
                    $this->_is_load_404 = true;
                } else {
                    $set['namespace'] = 'Default';;
                }
                $this->_loadNmcv($set);
                return true;
            } else {
                $controller_name = 'Qwin_Miku_Controller';
                $action = '__error';
                $controller = Qwin_Class::run($controller_name);
            }
        }
        
        /**
         * 控制器初始化完毕,加载命名空间类的 onLoad 方法,可在其中对控制器进行管理
         */
        // 方便外部调用
        Qwin_Class::addMap('-c', $controller_name);
        Qwin_Class::addClass($controller_name, $controller);
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
        NULL == $controller && $controller = Qwin_Class::run('-c');
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
        $controller = Qwin_Class::run($controller_name);
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
    return Qwin_Class::run($class, $param);
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
