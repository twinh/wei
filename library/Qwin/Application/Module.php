<?php
 /**
 * Module
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
 * @since       2010-04-17 15:50:02
 */

class Qwin_Application_Module
{
    /**
     * @var array           默认选项
     * 
     *  -- rootPaths        模块根目录
     *
     *  -- rootModule       默认的根模块
     */
    protected $_defaults = array(
        'rootPaths'          => array(),
        'rootModule'        => 'Common',
    );

    /**
     * 当前选项
     * @var array
     */
    protected $_options = array();

    /**
     * 根模块名称缓存,键名为名称,值为所在路径
     * @var array
     */
    protected $_rootsCache = array();

    /**
     *
     * @param array $options 选项
     * @return Qwin_Application_Module 当前对象
     */
    public function  __construct(array $options = array())
    {
        $this->_options = $options + $this->_defaults;
        return $this;
    }

    /**
     * 检查根模块是否存在
     *
     * @param string $module 模块名称
     * @param array|null $paths 模块根路径,可选
     * @return string|false 模块根路径|模块不存在
     * @todo 是否应该缓存结果
     */
    public function isExists($module, $paths = array())
    {
        !$paths && $paths = $this->_options['rootPaths'];
        $root = $this->getRoot($module);
        foreach ($paths as $path) {
            if (is_dir($path . $root)) {
                return $path;
            }
        }
        return false;
    }

    /**
     * isExists的别名
     *
     * @param string $module 模块名称
     * @param array|null $paths 模块根路径,可选
     * @return string|false 模块根路径|模块不存在
     * @see Qwin_Application_Module isExists
     */
    public function getPath($module, $paths = array())
    {
        return $this->isExists($module, $paths);
    }

    /**
     * 加载模块
     *
     * @param string $module 模块标识
     * @param mixed $paths 根目录,可选
     * @return Qwin_Application_Module 最终模块对象存在
     *         null 最终模块不存在
     *         false 根模块不存在
     */
    public static function load($module, $paths = array())
    {
        $self = Qwin::call(__CLASS__);
        !$paths && $paths = $self->_options['rootPaths'];

        // 检查模块是否存在
        if (!$self->isExists($module, $paths)) {
            return false;
        }

        // 逐层加载模块
        $modules = explode('/', $module);
        $part = '';
        foreach ($modules as $module) {
            $part .= $module . '_';
            $object = Qwin::call($part . 'Module');
        }

        return $object;
    }

    /**
     * 根据提供的模块字符串获取根模块名称
     *
     * @param string $module 模块字符串
     * @todo 是否应该缓存结果
     */
    public function getRoot($module)
    {
        $module = (string)$module;
        if (isset($module[0]) && '/' == $module[0]) {
            return $this->_options['rootModule'];
        }
        if (false !== ($pos = strpos($module, '/'))) {
            $module = substr($module, 0, $pos);
        }
        return $module;
    }

    /**
     * 获取根模块
     *
     * @param array $paths
     * @return string 
     */
    protected function _getRoots($paths)
    {
        $roots = array();
        foreach ((array)$paths as $path) {
            '/' != substr($path, 0, -1) && $path .= '/';
            if (!is_dir($path)) {
                continue;
            }
            foreach (scandir($path) as $file) {
                if ('.' != $file[0] && '_' != $file[0] && is_dir($path . $file)) {
                    $roots[$file] = $path . $file;
                }
            }
        }
        return $roots;
    }
}
