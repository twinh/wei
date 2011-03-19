<?php
/**
 * Service
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
 * @package     QWIN_PATH
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 15:30:37
 */

class Common_Service
{
    /**
     * 内置的的操作结果
     *
     * @var array
     */
    protected $_commonResult = array(
        -1  => 'Unknown error.',
        0   => 'Unknown error.',
        1   => 'Operation success.',
        2   => 'Class "%s" not found.',
        3   => '"%s" not defined.',
        4   => 'View Class "%s" no found.',
    );

    /**
     * 自定义的操作结果
     *
     * @var array
     */
    protected $_result = array();

    /**
     * 合并内置的和自定义的操作结果
     */
    public function  __construct()
    {
        $this->_result = $this->_result + $this->_commonResult;
    }

    /**
     * 将第二个数组合并到第一个上
     *
     * @param array $array1
     * @param array $array2
     * @return array 合并的数组
     */
    /*protected function _multiArrayMerge(array $array1 = null, array $array2 = null)
    {
        
        if(null == $array2)
        {
            return $array1;
        }
        foreach($array2 as $key => $val)
        {
            if(is_array($val))
            {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->_multiArrayMerge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }*/

    /**
     * 返回服务的操作结果
     *
     * @param int $code 操作结果代码
     * @param array $data 操作结果数据
     * @return array 操作结果
     */
    public function result($code = 1, $data = null)
    {
        !isset($this->_result[$code]) && $code = -1;
        $result = array();

        if (1 == $code) {
            return array(
                'result'    => true,
                'data'      => $data,
            );
        } else {
            return array(
                'result'    => false,
                'data'      => sprintf($this->_result[$code], $data),
            );
        }
    }
}
