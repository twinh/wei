<?php
/**
 * log4php
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-02-01 16:29:46
 */

class Log4php_Widget extends Qwin_Widget_Abstract
{
    /**
     * 配置文件名称
     * @var string
     */
    protected $_configFile = 'appender_dailyfile.properties';

    /**
     * 日志对象
     * @var LoggerRoot
     */
    protected $_logger;

    /**
     * 初始化对象
     *
     * @param string|null $file 配置文件
     */
    public function __construct($file = null)
    {
        $this->_rootPath = QWIN . 'widget/log4php/';
        // 加载配置文件
        require_once $this->_rootPath . 'source/Logger.php';
        null == $file && $file = $this->_rootPath . $this->_configFile;
        Logger::configure($file);

        // 初始化日志对象
        $this->_logger = Logger::getRootLogger();
        $this->_logger->debug('** START **');
        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * 当程序结束时,为日志增加结束标志
     */
    public function shutdown()
    {
        $this->_logger->debug('** END ****' . PHP_EOL);
    }

    /**
     * 渲染微件
     *
     * @param mixed $option 配置选项
     */
    public function render($option)
    {
        throw new Qwin_Widget_Exception('The "render" method should not be called in this widget.');
    }

    /**
     * 通过魔术方法将微件的方法映射到日志对象的方法上.
     *
     * @param string $name 调用的方法名称
     * @param array $arguments 参数数组
     * @return mixed
     */
    public function  __call($name, $arguments) {
        return call_user_func_array(array($this->_logger, $name), $arguments);
    }
}