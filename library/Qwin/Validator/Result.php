<?php
/**
 * Result
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
 * @subpackage  Validator
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-14 19:31:44
 */

class Qwin_Validator_Result
{
    /**
     * 验证结果
     * @var boolen
     */
    protected $_result = false;

    /**
     * 错误域
     * @var string
     */
    protected $_field;

    /**
     * 错误信息
     * @var string
     */
    protected $_message;

    /**
     * 错误的代码
     * @var string/int
     */
    protected $_code;

    /**
     * 验证方法的参数,主要用于对验证信息的动态转换
     * @var mixed
     */
    protected $_param;

    /**
     * 键名列表
     * @var array
     */
    protected $_keyList = array(
        'result', 'field', 'message', 'code', 'param',
    );


    public function __construct($result, $field, $message, $code = null, $param = null)
    {
        $this->_result = (bool)$result;
        $this->_field = $field;
        $this->_message = $message;
        $this->_code = $code;
        $this->_param = $param;
    }

    public function  __get($name)
    {
        if(in_array($name, $this->_keyList))
        {
            $name = '_' . $name;
            return $this->$name;
        }
        return null;
    }

    public function __set($name, $value)
    {
        if(in_array($name, $this->_keyList))
        {
            $name = '_' . $name;
            $this->$name = $value;
        }
        return $this;
    }
}
