<?php
/**
 * Hook
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
 * @since       2011-02-05 21:01:08
 * @todo        适配器模式?
 */

class Qwin_Hook
{
    /**
     * 默认配置
     * @var array
     */
    protected $_defaults = array(
        'path' => '../widget/',
        'cachePath' => '../cache/',
        'lifetime' => 86400,
        /*'file' => 'hook.php',
        'depth' => 2,
        'class' => 'classPattern',
        'method' => 'hookPattern',*/
    );

    /**
     * 当前配置
     * @var array
     */
    protected $_options = array();

    /**
     * 钩子数据
     * @var array
     */
    protected $_data = array();

    /**
     * 已调用过的钩子
     * @var array
     */
    protected $_called = array();

    /**
     * 初始化,设置配置
     * @param array $option 配置,参照默认配置
     */
    public function  __construct(array $option = array())
    {
        !empty($option) && $this->setOption($option);
        $cacheFile = $this->_options['cachePath'] . 'hook.php';

        if (!is_file($cacheFile)) {
            file_put_contents($cacheFile, null);
        }
        if ($this->_options['lifetime'] < $_SERVER['REQUEST_TIME'] - filemtime($cacheFile)) {
            $this->update();
        } else {
            $this->_data = (array)require $cacheFile;
        }
    }

    /**
     * 设置配置
     *
     * @param array $option 配置,参照默认配置
     * @return Qwin_Hook 
     */
    public function setOption($option)
    {
        $this->_options = array_merge($this->_defaults, $option);
        if (!is_dir($this->_options['path'])) {
            throw new Qwin_Hook_Exception('The path "' . $this->_options['path'] . '" can not be found.');
        }
        return $this;
    }

    /**
     * 动态添加一个钩子
     *
     * @param string $name 钩子名称
     * @param array $callback 简单回调结构
     * @return Qwin_Hook 当前对象
     */
    public function set($name, array $callback)
    {
        if (!isset($callback[0]) || !is_callable($callback[0])) {
            throw new Qwin_Hook_Exception('The callback param is not callable.');
        }
        $this->_data[$name] = $callback;
        return $this;
    }

    /**
     * 更新钩子缓存数据
     *
     * @return Qwin_Hook 当前对象
     * @uses Qwin_Util_File::writeArray 写入钩子数据
     */
    public function update()
    {
        if (!is_dir($this->_options['cachePath'])) {
            throw new Qwin_Hook_Exception('The cache path "' . $this->_options['cachePath'] . '" can not be found.');
        }

        // 清空原有钩子脚本
        $this->_data = array();
        $pathList = scandir($this->_options['path']);
        foreach ($pathList as $path) {
            if ('.' == $path || '..' == $path) {
                continue;
            }
            
            // 是否存在钩子文件
            $file = $this->_options['path'] . $path . '/Hook.php';
            if (!is_file($file)) {
                continue;
            }

            // 是否存在钩子类
            require_once $file;
            $class = $path . '_Hook';
            if (!class_exists($class)) {
                continue;
            }

            // 是否继承父类
            /*if ('Qwin_Hook_Abstract' != get_parent_class($class)) {
                continue;
            }*/

            $reflection = new ReflectionClass($class);
            $properties = $reflection->getDefaultProperties();
            $priorities = array_change_key_case($properties['_priorities']);
            $methods    = get_class_methods($class);

            foreach ($methods as $method) {
                if ('hook' == substr($method, 0, 4)) {
                    $name = strtolower(substr($method, 4));

                    // 以优先级作为键名,当优先级相同时,往后递增
                    // 当数量庞大时,应进行优化
                    !isset($priorities[$name]) && $priorities[$name] = 50;
                    while (isset($this->_data[$name][$priorities[$name]])) {
                        $priorities[$name]++;
                    }
                    
                    $this->_data[$name][$priorities[$name]] = array(
                        'file' => $file,
                        'class' => strtolower($class),
                    );
                }
            }
        }

        // 根据优先级排序
        foreach ($this->_data as &$value) {
            ksort($value);
        }

        Qwin_Util_File::writeArray($this->_options['cachePath'] . '/hook.php', $this->_data);
        return $this;
    }

    /**
     * 调用钩子脚本
     *
     * @param string $name 钩子名称
     * @param mixed $param 参数
     * @return Qwin_Hook|string 当前对象|字符串
     */
    public function call($name, $param = null)
    {
        $return = '';
        $name = strtolower($name);
        !is_array($param) && $param = array($param);
        if (isset($this->_data[$name])) {
            foreach ($this->_data[$name] as $callback) {
                // 默认文件形式
                if (isset($callback['file'])) {
                    require_once $callback['file'];
                    $result = call_user_func_array(
                        array(Qwin::call($callback['class']), 'hook' . $name),
                        $param
                    );
                // 自定义回调结构形式
                } else {
                    $callback[1] = isset($callback[1]) ? array_merge($callback[1], $param) : $param;
                    $result = call_user_func_array($callback[0], $callback[1]);
                }
                if (is_string($result)) {
                    $return .= $result;
                }
            }
        }
        if ($return) {
            return $return;
        }
        return $this;
    }

    /**
     * 是否调用过某一个钩子
     *
     * @param string $name 钩子名称
     * @return bool
     */
    public function isCalled($name)
    {
        return isset($this->_called[$name]);
    }

    /**
     * 清空钩子脚本
     *
     * @param string|null $name 不存在时,清空所有的钩子;存在时,清空指定名称的钩子
     * @return Qwin_Hook 当前对象
     */
    public function clear($name = null)
    {
        if (!$name) {
            $this->_data = array();
        }
        if (isset($this->_data[$name])) {
            $this->_data[$name] = array();
        }
        return $this;
    }
}
