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
 * @since       2011-05-23 23:46:16
 */

class Qwin_Meta_Module extends Qwin_Meta_Common
{
    /**
     * @var array           默认选项
     * 
     *      -- source       模块标识源字符串
     * 
     *      -- url          Url形式,即小写,横杆
     * 
     *      -- url          路径形式,即首字母大写,斜杠
     * 
     *      -- class        类名形式,即首字母大写,下划线
     * 
     *      -- id           编号形式,即小写,横杠
     * 
     *      -- lang         语言形式,即大写,下划线
     */
    protected $_defaults = array(
        'source'    => null,
        'url'       => null,
        'path'      => null,
        'class'     => null,
        'id'        => null,
        'lang'      => null,
    );
    
    /**
     * 格式化数据
     * 
     * @param string $source 模块标识
     * @param array $options 选项
     * @return Qwin_Meta_Module 当前对象
     */
    public function merge($source, array $options = array())
    {
        $data = $this->_defaults;
        $data['source'] = preg_split('/([^A-Za-z0-9])/', (string)$source);
        $this->exchangeArray($data);
        return $this;
    }
    
    /**
     * 获取模块编号形式名称
     *
     * @return string
     */
    public function getId()
    {
        if (!$this['id']) {
            $this['id'] = strtolower(implode('-', $this['source']));
        }
        return $this['id'];
    }

    /**
     * 获取模块路径形式名称
     *
     * @return string
     */
    public function getPath()
    {
        if (!$this['path']) {
            $this['path'] = implode('/', array_map('ucfirst', $this['source'])) . '/';
        }
        return $this['path'];
    }

    /**
     * 获取模块Url形式名称
     *
     * @return string
     */
    public function getUrl()
    {
        if (!$this['url']) {
            $this['url'] = strtolower(implode('/', $this['source']));
        }
        return $this['url'];
    }

    /**
     * 获取模块类名化名称
     * 
     * @return string
     */
    public function getClass()
    {
        if (!$this['class']) {
            $this['class'] = implode('_', array_map('ucfirst', $this['source']));
        }
        return $this['class'];
    }
    
    /**
     * 获取语言形式名称
     * 
     * @return string
     */
    public function getLang()
    {
        if (!$this['lang']) {
            $this['lang'] = strtoupper(implode('_', $this['source']));
        }
        return $this['lang'];
    }
}