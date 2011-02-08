<?php
/**
 * Language
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
 * @since       2011-01-18 21:43:58
 */

class Common_Helper_Language
{
    /**
     * 语言的名称
     * @var string
     */
    protected $_name;

    /**
     * 储存语言实例的数组
     * @var array
     */
    protected $_obejct;

    public function getObjectByAsc($asc, $name = null)
    {
        null == $name && $name = $this->getName($asc);
        $class = $this->getClass($asc, $name);
        if (!isset($this->_obejct[$class])) {
            if (!class_exists($class)) {
                //return false;
                $class = 'Common_Language_' . $name;
            }
            $this->_obejct[$class] = new $class;
        }
        return $this->_obejct[$class];
    }

    /**
     * 获取类的名称
     *
     * @param array $asc 应用结构配置
     * @param string $name 语言的名称
     * @return string 类名
     */
    public function getClass($asc, $name)
    {
        return $asc['namespace'] . '_' . $asc['module'] . '_Language_' . $name;
    }

    /**
     * 判断类是否存在
     *
     * @param array $asc 应用结构配置
     * @param string $name 语言的名称
     * @return string|false
     */
    public function isExists($asc, $name)
    {
        $name = ucfirst(strtolower(strtr($name, array('-' => ''))));
        $class = $this->getClass($asc, $name);
        if (class_exists($class)) {
            return $name;
        }
        return false;
    }

    /**
     * 获取语言名称,顺序为用户请求 > 会话中用户的配置 > 全局配置
     *
     * @param array $asc 应用结构配置
     * @return string
     */
    public function getName($asc)
    {
        if (isset($this->_name)) {
            return $this->_name;
        }

        // 用户请求
        $request    = Qwin::call('#request');
        $queryName = $request->getOption('lang');
        $name = $request[$queryName];
        $name = $this->isExists($asc, $name);
        if (false != $name) {
            $this->_name = $name;
            return $name;
        }
        
        // 会话中用户的配置
        $session = Qwin::call('-session');
        $name = $session->$queryName;
        $name = $this->isExists($asc, $name);
        if (false != $name) {
            $this->_name = $name;
            return $name;
        }

        // 全局配置
        $config = Qwin::call('-config');
        $name = $config['interface']['language'];
        $name = $this->isExists($asc, $name);
        if (false != $name) {
            $this->_name = $name;
            return $name;
        }

        throw new Qwin_Exception('Can not find language by name : ' . $config['interface']['language']);
    }
}
