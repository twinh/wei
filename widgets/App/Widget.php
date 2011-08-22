<?php
/**
 * 应用程序启动类
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

class App_Widget extends Qwin_Widget_Abstract
{
    /**
     * @var array           默认选项
     * 
     *      -- path         应用的路径
     * 
     *      -- errorType    报错类型
     *                      E_ALL|E_STRICT      32767
     *                      E_ALL & ~E_NOTICE   30711
     */
    protected $_defaults = array(
        'path'          => null,
        'errorType'     => 32767,
    );
    
    /**
     * 应用是否已加载,即调用过startup方法
     * @var boolen
     */
    protected $_loaded = false;

    /**
     * 启动时间
     * @var string
     */
    protected $_startTime;
    
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        // 设置错误提示的输出等级
        error_reporting($this->_options['errorType']);
        
        // 设置默认的目录
        if (null == $this->_options['path']) {
            $this->_options['path'] = dirname($this->_widget->getPath()) . '/apps/';
        }
    }

    /**
     * 启动应用
     * 
     * @param array $config 配置
     * @return App_Widget 当前对象
     */
    public function render($config = null)
    {
        // 设置加载标识,防止二次加载
        if ($this->_loaded) {
            return false;
        }
        $this->_loaded = true;

        // 设置启动时间
        $this->_startTime = microtime(true);

        // 默认时区
        date_default_timezone_set($config['timezone']);

        // 设置应用启动钩子
        Qwin::hook('AppStartup');

        // 初始化请求
        $request = Qwin::call('-request');

        // 获取模块和行为
        $module = (string)$request->get('module');
        $action = (string)$request->get('action');
        $module = Qwin_Module::instance($module);

        // 加入到配置中
        $config['action'] = $action;
        $config['module'] = $module;

        // 加载控制器
        $params = array($config, $module, $action);
        $controller = Controller_Widget::getByModule($module, true, $params);

        // 执行行为
        $actionName = 'action' . $action;
        if (method_exists($controller, $actionName)
            && !in_array(strtolower($action), $controller->getForbiddenActions())) {
            call_user_func_array(array($controller, $actionName), $params);
        } else {
            throw new Qwin_Widget_Exception('Action "' . $action . '" not found in controller "' . get_class($controller) .  '"');
        }

        // 展示视图
        $this->_view->display();

        // 设置应用结束钩子
        Qwin::hook('AppTermination');

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