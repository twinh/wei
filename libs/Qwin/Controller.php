<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * Controller
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2009-11-24 20:45:11
 * @todo        重新实现禁用行为
 */
class Qwin_Controller extends Qwin_Widget
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->init();
    }

    /**
     * 根据模块获取控制器对象
     *
     * @param string $module 模块名称
     * @param bool $instance 是否实例化
     * @param mixed $param 参数
     * @return Qwin_Controller
     */
    public function __invoke($module = null, $instance = true, $param = null)
    {
        $module = ucfirst($module);
        // 检查模块控制器文件是否存在
        $found = false;
        foreach ($this->app->options['dirs'] as $dir) {
            $file = $dir . '/' . $module . '/Controller.php';
            if (is_file($file)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->log('Module "' . $module . '" not found.');
            return false;
        }

        require_once $file;
        $class = $module . '_Controller';
        if (!class_exists($class)) {
            $this->log('Controller ' . $class . ' not found.');
            return false;
        }

        return $instance ? $this->qwin($class, $param) : $class;
    }

    /**
     * 初始化方法,于__construct方法之后调用
     *
     * @return Qwin_Controller 当前对象
     */
    public function init()
    {
        return $this;
    }

    /**
     * Execute action
     *
     * @param mixed $action
     * @return mixed
     */
    public function execute($action)
    {
        $action = (string)$action;
        if ($action) {
            $action2 = $action . 'Action';
            if (method_exists($this, $action2)) {
                return call_user_func(array($this, $action2));
            }
        }

        $this->log(sprintf('Action "%s" not found in controller "%s".', $action, get_class($this)));

        return false;
    }

    /**
     * Set or get options
     *
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     */
    public function option($name = null, $value = null)
    {
        // load options data from controller "options" dir
        if (1 == func_num_args() && (is_string($name) || is_int($name))) {
            if (!isset($this->options[$name])) {
                foreach ($this->app->options['dirs'] as $dir) {
                    $file = $dir . '/' . ucfirst($this->module()) . '/options/'  . $name . '.php';
                    if (is_file($file)) {
                        $this->options[$name] = require $file;
                        break;
                    }
                }
            }
            // get option
            return parent::option($name);
        }
        // other actions
        return parent::option($name, $value);
    }
}
