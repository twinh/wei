<?php
/**
 * Common
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
 * @since       2010-5-21 7:14:48
 */

class Qwin_Validator_Common extends Qwin_Validator_Abstract
{
    public function required($value)
    {
        return '' != trim($value);
    }

    public function minlength($value, $param)
    {
        return strlen($value) >= $param;
    }

    public function maxlength($value, $param)
    {
        echo $value . $param;
        return strlen($value) <= $param;
    }

    public function rangelength($value, $param1, $param2)
    {
        $len = strlen($value);
        return $len >= $param1 && $len <= $param2;
    }

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
     *
     * @param <type> $value
     * @return <type>
     * @todo ereg
     */
    public function email($value)
    {
        return @ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $value);
    }
}
