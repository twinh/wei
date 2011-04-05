<?php
/**
 * Flow
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
 * @since       v0.6.9 2011-02-03 11:50:37
 */

class Qwin_Flow
{
    /**
     * 操作符,其中"->"表示动态调用,"::"表示静态调用
     * @var array
     */
    protected $_operator = array('->', '::');

    public function call(array $callbackList, $value)
    {
        foreach ($callbackList as $callback) {
            $callback = $this->filter($callback, $value);
            if (3 == count($callback[0])) {
                $value = $this->_callClassMethod($callback, $value);
            } else {
                $value = $this->_callFunction($callback, $value);
            }
        }
        return $value;
    }

    public function callOne($callback)
    {
        $callback = $this->filter($callback, $value);
        if (3 == count($callback[0])) {
            $value = $this->_callClassMethod($callback);
        } else {
            $value = $this->_callFunction($callback);
        }
        return $value;
    }

    /**
     * 调用函数型回调结构
     *
     * @param array $callback 回调结构
     * @return mixed
     */
    protected function _callFunction($callback)
    {
        if(!is_callable($callback[0])) {
            throw new Exception('The function ' . $callback[0] . ' can not be called.');
        }
        return call_user_func_array($callback[0], $callback[1]);
    }

    /**
     * 调用方法型回调结构
     *
     * @param array $callback 回调结构
     * @return mixed
     */
    protected function _callClassMethod($callback)
    {
        if (!is_callable(array($callback[0][0], $callback[0][1]))) {
            is_object($callback[0][0]) && $callback[0][0] = get_class($callback[0][0]);
            throw new Exception('The method "' . $callback[0][0] . $callback[0][2] . $callback[0][1] . '" can not be called.');
        }
        // 根据操作符进行转换
        if ('->' == $callback[0][2]) {
            !is_object($callback[0][0]) && $callback[0][0] = Qwin::call($callback[0][0]);
        } else {
            is_object($callback[0][0]) && $callback[0][0] = get_class($callback[0][0]);
        }
        return call_user_func_array(array(
            $callback[0][0], $callback[0][1],
        ), $callback[1]);
    }

    /**
     * 转换回调结构
     *
     * @param string|array $callback 回调结构
     * @param mixed $value
     * @return int
     * @todo 简化
     * @todo 没有value的情况
     */
    public function filter($callback, $value)
    {
        if (!is_array($callback)) {
            $callback = array($callback);
        }

        // 转换首层结构
        /**
         * 首层包含三个值
         * 第一个是回调的结构,可能是字符串,表示函数,也可能是数组,表示对象(类)和方法
         * 第二个是参数的集合的数组,可能为空
         * 第三个是值($value)在参数中的位置,默认是0
         */
        $temp = array();
        reset($callback);
        for ($i = 0; $i < 3; $i++) {
            $key = key($callback);
            if(array_key_exists($key, $callback)) {
                $temp[$i] = $callback[key($callback)];
            } else {
                $temp[$i] = array();
            }
            next($callback);
        }
        $callback = array();

        // 转换第二层第一个键的数据
        if (is_array($temp[0])) {
            // 类名/对象
            if (!isset($temp[0][0])) {
                throw new Exception('The class should not be empty in callback ' . var_export($temp, true) . '.');
            }
            $callback[0][0] = $temp[0][0];

            // 方法名
            if (!isset($temp[0][1])) {
                throw new Exception('The method should not be empty in callback ' . var_export($temp, true) . '.');
            }
            $callback[0][1] = $temp[0][1];

            // 操作符
            if (isset($temp[0][2]) && in_array($temp[0][2], $this->_operator)) {
                $callback[0][2] = $temp[0][2];
            } else {
                $callback[0][2] = $this->_operator[0];
            }
        } else {
            $callback[0] = $temp[0];
        }

        // 转换第二层的第二个键的数据
        $callback[1] = (array)$temp[1];

        $temp[2] = (int)$temp[2];
        if (0 > $temp[2] || count($temp[1]) < $temp[2]) {
            $temp[2] = 0;
        }

        // 加入到参数中
        array_splice($callback[1], $temp[2], 0, array($value));

        return $callback;
    }
}
