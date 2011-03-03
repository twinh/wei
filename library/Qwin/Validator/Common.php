<?php
/**
 * JQuery
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
 * @since       2010-09-14 09:30:39
 */

class Qwin_Validator_Common extends Qwin_Validator_Abstract
{
    /**
     * 验证不能为空
     *
     * @param string $value 待验证的字符串
     * @return boolen 是否通过验证
     */
    public static function required($value)
    {
        return '' != trim($value);
    }

    /**
     * 验证字符串长度应该大于$param
     *
     * @param string $value 待验证的字符串
     * @param int $param 最小长度
     * @return boolen 是否通过验证
     */
    public function minlength($value, $param)
    {
        return strlen($value) >= $param;
    }

    /**
     * 验证字符串长度应小于$param
     *
     * @param string $value 待验证的字符串
     * @param int $param 最大长度
     * @return boolen 是否通过验证
     */
    public function maxlength($value, $param)
    {
        return strlen($value) <= $param;
    }

    /**
     * 验证长度应该在$param1和$param2之间
     *
     * @param string $value
     * @param int $param1 最小长度
     * @param int $param2 最大长度
     * @return boolen 是否通过验证
     */
    public static function rangelength($value, $param)
    {
        $len = strlen($value);
        return $len >= $param[0] && $len <= $param[1];
    }

    /**
     * 验证长度应该在$param1和$param2之间
     *
     * @param string $value
     * @param int $param1 最小长度
     * @param int $param2 最大长度
     * @return boolen 是否通过验证
     */
    public function byteRangeLength($value, $param)
    {
        $len = strlen($value);
        return $len >= $param[0] && $len <= $param[1];
    }

    /**
     * 验证是否等于某一个值
     *
     * @param string $value
     * @param string $param 表单的name
     * @return boolen 是否通过验证
     */
    public function equalTo($value, $param)
    {
        $param = strtr($param, array('#' => '', '.' => ''));
        if(isset($_POST[$param]))
        {
            return $_POST[$param] == $value;
        }
        return true;
    }

    /**
     * 验证邮箱
     * 
     * @param string $value
     * @return boolen 是否通过验证
     * @todo ereg已经废弃
     */
    public function email($value)
    {
        return @ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $value);
    }
}
