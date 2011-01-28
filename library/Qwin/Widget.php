<?php
/**
 * Widget
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
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-20 15:26:25
 * @todo        形成一个规范的体系
 */

abstract class Qwin_Widget
{
    /**
     * 配置选项
     *
     * @var array
     */
    protected $_option = array();

    abstract public function render($option);

    /**
     * 获取配置选项
     *
     * @param string $name 配置名称
     * @return mixed
     */
    public function getOption($name = null)
    {
        if (null == $name) {
            return $this->_option;
        }
        return isset($this->_option[$name]) ? $this->_option[$name] : null;
    }

    /**
     * 生成属性字符串
     *
     * @param array $option 属性数组,键名表示属性名称,值表示属性值
     * @return string 属性字符串
     */
    public function renderAttr($option)
    {
        $attr = '';
        foreach ($option as $name => $value) {
            $attr .= $name . '="' . htmlspecialchars($value) . '" ';
        }
        return $attr;
    }
}
