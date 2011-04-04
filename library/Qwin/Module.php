<?php
/**
 * Module
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
 * @since       2011-03-22 15:36:41
 */

class Qwin_Module
{
    /**
     * 模块名称
     * @var string
     */
    protected $_data;

    /**
     * 模块编号化名称,如 com-member
     * @var string
     */
    protected $_id;

    /**
     * 模块路径化名称,如 Com/Member
     * @var string
     */
    protected $_path;

    /**
     * 模块类名化名称,如Com_Member
     * @var string
     */
    protected $_class;

    /**
     * 模块Url化名称
     * @var string
     */
    protected $_url;

    /**
     * 默认配置
     * @var array
     */
    protected $_defaults = array(
        'rootModule'    => 'Com',
    );

    public function __construct($module = null)
    {
        // 耦合?
        if (!$module) {
            $this->_data = Qwin::call('-request')->getModule();
        } else {
            $this->_data = $module;
        }
    }

    public function toString()
    {
        return $this->_data;
    }

    /**
     * 获取模块编号化名称
     *
     * @return string
     */
    public function toId()
    {
        if (!$this->_id) {
            $this->_id = strtolower(strtr($this->_data, '/', '-'));
        }
        return $this->_id;
    }

    public function toPath()
    {
        
    }

    /**
     * 获取模块Url化名称
     *
     * @return string
     */
    public function toUrl()
    {
        if (!$this->_url) {
            $this->_url = strtolower($this->_data);
        }
        return $this->_url;
    }

    /**
     * 获取模块类名化名称
     * @return string
     */
    public function toClass()
    {
        if (!$this->_class) {
            // 转换为标准格式的类名
            $class = preg_split('/([^A-Za-z0-9])/', $this->_data);
            $this->_class = implode('_', array_map('ucfirst', $class));
        }
        return $this->_class;
    }

    public function isValid()
    {
        
    }

    public function __toString()
    {
        return $this->_data;
    }

    public function set($data)
    {
        $this->_data = $data;
    }
}
