<?php
/**
 * Callback
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
 * @subpackage  Struct
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       v0.5.1 2010-12-10 12:53:14
 */

class Qwin_Struct_Callback extends Qwin_Struct_Abstract
{
    /**
     * 结构体状态为空,一般是刚初始化,或者清空后
     * @var int
     */
    const STATE_EMPTY   = 1;

    /**
     * 结构体状态为满,有具体值
     * @var int
     */
    const STATE_FULL    = 2;

    /**
     * 回调结构的例子
     * @var array
     */
    public $_sample = array(
        array('class-name', 'method-name', 'operator'),
        array('param-1', 'param-2', 'param-3'),
    );

    /**
     * 回调结构的例子2
     * @var array
     */
    public $_sample2 = array(
        'function-name',
        array('param-1', 'param-2', 'param-3')
    );

    /**
     * 操作符的选项值
     * 其中"->"表示动态调用,"::"表示静态调用
     * @var array
     */
    protected $_operatorOption = array('->', '::');

    /**
     * 存储回调结构的数组
     * @var array
     */
    protected $_data = array();

    /**
     * 结构体状态,可能取值参照本类以STATE_开头的常量
     * @var int
     */
    protected $_state;

    /**
     * 初始化类,如果没有参数,则设置状态为空,
     * 如果有参数,利用参数创建回调结构体
     *
     * @return Qwin_Struct_Callback
     */
    public function  __construct()
    {
        $arg = func_get_arg(1);
        if (isset($arg)) {
            $this->addElement($arg);
        } else {
            $this->_state = self::STATE_EMPTY;
        }
        parent::__construct();
        return $this;
    }

    /**
     * 创建一个回调结构体
     * 该方法会尽可能的将$element参数转换为回调结构体,
     * 并不验证回调结构体是否可被调用,是否有正常的回调值
     *
     * @param mixed $element 元素
     * @param array $option 附加选项
     */
    public function addElement($element, array $option = null)
    {
        // 因为结构体只能有一个,所以得清空重建
        if (self::STATE_FULL == $this->_state) {
            $this->clear();
        }

        if (!is_array($element)) {
            $element = array($element);
        }

        // 转换首层结构
        /**
         * 首层包含两个值,第一个表示回调的结构,可能是字符串,表示函数,也可能是数组,表示对象
         * 第二个是参数的集合的数组,可能为空
         */
        $tempElement = array();
        reset($element);
        for ($i = 0; $i < 2; $i++) {
            $key = key($element);
            if(array_key_exists($key, $element)) {
                $tempElement[$i] = $element[key($element)];
            } else {
                $tempElement[$i] = array();
            }
            next($element);
        }
        
        // 转换第二层第一个键的数据
        if (is_array($tempElement[0])) {
            for ($i = 0; $i < 3; $i++) {
                if (isset($tempElement[0][$i])) {
                    $this->_data[0][$i] = $tempElement[0][$i];
                } else {
                    $this->_data[0][$i] = null;
                }
                if (2 == $i && !in_array($this->_data[0][$i], $this->_operatorOption)) {
                    $this->_data[0][$i] = $this->_operatorOption[0];
                }
            }
        } else {
            $this->_data[0] = $tempElement[0];
        }

        // 转换第二层的第二个键的数据
        $this->_data[1] = $tempElement[1];

        $this->_state = self::STATE_FULL;
        return $this;
    }

    public function removeElement($name)
    {
        $this->clear();
        $this->_state = self::STATE_EMPTY;
        return $this;
    }

    public function clear()
    {
        $this->_data = array();
        $this->_state = self::STATE_EMPTY;
        return $this;
    }

    public function valid($element)
    {
        if (!is_array($element)) {
            $this->_validMessage = 'not array';
            return false;
        }

        if (empty($element) || count($element) > 2) {
            $this->_validMessage = 'array number';
            return false;
        }
    }
}
