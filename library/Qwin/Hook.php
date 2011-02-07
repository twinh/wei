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
    protected $_defaultOption = array(
        'path' => '../widget/',
        'cachePath' => '../cache/',
        /*'file' => 'hook.php',
        'depth' => 2,
        'class' => 'classPattern',
        'method' => 'hookPattern',*/
    );

    /**
     * 当前配置
     * @var array
     */
    protected $_option = array();

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
        $cacheFile = $this->_option['cachePath'] . '/hook.php';
        if (is_file($cacheFile)) {
            $this->_data = (array) require $cacheFile;
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
        $this->_option = array_merge($this->_defaultOption, $option);
        if (!is_dir($this->_option['path'])) {
            throw new Qwin_Hook_Exception('The path "' . $this->_option['path'] . '" can not be found.');
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
        if (!is_dir($this->_option['cachePath'])) {
            throw new Qwin_Hook_Exception('The cache path "' . $this->_option['cachePath'] . '" can not be found.');
        }

        // 清空原有钩子脚本
        $this->_data = array();
        $pathList = scandir($this->_option['path']);
        foreach ($pathList as $path) {
            // 是否存在钩子文件
            $file = $this->_option['path'] . '/' . $path . '/hook.php';
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
            if ('Qwin_Hook_Abstract' != get_parent_class($class)) {
                continue;
            }

            // 获取类中的所有钩子方法,并加入到数据中
            $methodList = get_class_methods($class);
            foreach ($methodList as $method) {
                if ('hook' == substr($method, 0, 4)) {
                    $name = strtolower(substr($method, 4));
                    // 效率或空间?
                    $this->_data[$name][] = array(
                        'file' => $file,
                        'class' => strtolower($class),
                    );
                }
            }
        }
        Qwin_Util_File::writeArray($this->_option['cachePath'] . '/hook.php', $this->_data);
        return $this;
    }

    /**
     * 调用钩子脚本
     *
     * @param string $name 钩子名称
     * @param mixed $param 参数
     * @return Qwin_Hook 
     */
    public function call($name, $param = array())
    {
        if (isset($this->_data[$name])) {
            foreach ($this->_data[$name] as $callback) {
                // 默认文件形式
                if (isset($callback['file'])) {
                    require_once $callback['file'];
                    call_user_func_array(
                        array(Qwin::call($callback['class']), 'hook' . $name),
                        (array)$param
                    );
                // 自定义回调结构形式
                } else {
                    call_user_func_array($callback[0], array_merge($callback[1], $param));
                }
            }
            
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
