<?php
/**
 * Model
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
 * @since       2011-04-17 13:48:42
 */

class Ide_Module_Model
{
    const IS_AVAILABLE = 1;
    

    /**
     * 模块数据
     * @var array
     */
    protected $_modules = array(
        'root' => array(
            'name' => '',
            'path' => '',
        ),
    );

    /**
     * 模块根目录
     * @var array
     */
    protected $_paths = array();

    public function  __construct($path = null)
    {
        if (!$path) {
            $this->_paths = Qwin::call('Qwin_Application_Module')->getRootPaths();
        } else {
            $this->setRootPath($path);
        }
    }

    /**
     * 设置模块根目录
     *
     * @param string|array $path 目录
     * @return Ide_Module_Model 当前对象
     */
    public function setRootPath($path)
    {
        $this->_paths = array_unique((array)$path);
        return $this;
    }

    /**
     * 添加模块根目录
     *
     * @param string|array $path 目录
     * @return Ide_Module_Model 当前对象
     */
    public function addRootPath($path)
    {
        $this->_path = array_unique(array_merge((array)$path + $this->_paths));
        return $this;
    }

    /**
     * 根据过滤条件获取模块
     *
     * @param int $filter 过滤条件
     * @return array
     */
    public function getModules($filter = null)
    {
        $modules = array();
        // 取得所有的模块
        foreach ($this->_paths as $path) {
            if (!is_dir($path)) {
                continue;
            }
            $modules = array_merge($modules, $this->_getModules($path));
        }
        return $modules;
    }

    public function _getModules($path, $parent = null)
    {
        $modules = array();
        null !== $parent && $parent .= '/';
        
        $files = scandir($path);
        foreach ($files as $file) {
            if (!isset($file[0]) || '.' == $file[0]) {
                continue;
            }

            // 如果存在下级目录，继续查找
            if (is_dir($path . $file . '/')) {
                $name = strtolower($parent . $file);
                $modules = array_merge($modules, $this->_getModules($path . $file . '/', $name));
                
                // 判断是否存在控制器文件
                if (null != $parent && is_file($path . $file . '/Controller.php')) {
                    $modules[$name] = array(
                        'name' => $name,
                        'path' => $path . $file . '/',
                    );
                }
            }
        }
        return $modules;
    }

    public function getAllModules()
    {
        
    }

    public function getAvailableModules()
    {

    }

    public function getUnAvailableModules()
    {

    }

    public function getParentModule()
    {
        
    }

    public function getSubModule()
    {
        
    }

    /**
     * 检查一个模块是否为根模块
     *
     * @param string $module 模块名称
     * @return bool
     */
    public function isRoot($module)
    {
        // TODO
        return true;
    }

    /**
     * 检查一个模块是否为最终模块
     *
     * @param string $module 模块名称
     * @return bool
     */
    public function isFinal($module)
    {
        // TODO
        return true;
    }
}