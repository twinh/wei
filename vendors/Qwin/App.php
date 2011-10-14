<?php
/**
 * Qwin Framework
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * App
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-11 15:10:05
 */
class Qwin_App extends Qwin_Widget
{
    /**
     * @var array           默认选项
     * 
     *       paths          应用的路径
     */
    public $options = array(
        'paths'         => null,
        'module'        => null,
        'action'        => null,
        'timezone'      => null,
        'startTime'     => null,
    );
    
    /**
     * 应用是否已加载,即调用过startup方法
     * @var boolen
     */
    protected $_loaded = false;

    /**
     * 启动应用
     * 
     * @param array $config 配置
     * @return App_Widget 当前对象
     */
    public function call(array $options = array())
    {
        // 设置加载标识,防止二次加载
        if ($this->_loaded) {
            return false;
        }
        $this->_loaded = true;
        
        // 合并选项
        $options = $options + $this->options;
        $this->options = &$options;
        
        // 设置启动时间
        !$options['startTime'] && $options['startTime'] = microtime(true);
        
        // 设置应用目录
        if (!is_array($options['paths'])) {
            $options['paths'] = (array)$options['paths'];
        }
        if (empty($options['paths'])) {
            $options['paths'][] = dirname(dirname(dirname(__FILE__))) . '/apps/';
        }

        // 默认时区
        date_default_timezone_set($options['timezone']);
        
        // 设置应用启动钩子
        //$this->trigger('appStartup');
        //Qwin::hook('AppStartup');

        // 获取模块名称并转换为模块对象
        $module = $this->get('module')->module();
        $action = $this->get('action');

        $controller = $this->controller->getByModule($module);
        $actionName = $action . 'Action';
        if (method_exists($controller, $actionName)) {
            call_user_func(array($controller, $actionName));
        } else {
            throw new Qwin_Exception('Action "' . $action . '" not found in controller "' . get_class($controller) .  '"');
        }
        
        // 展示视图
        $this->view();
        
        // 设置应用结束钩子
        //Qwin::hook('AppTermination');
        
        // 返回当前对象
        return $this;
    }

    /**
     * 获取页面运行时间
     *
     * @return string
     */
    public function getEndTime()
    {
        return str_pad(round((microtime(true) - $this->options['startTime']), 4), 6, 0);
    }
}