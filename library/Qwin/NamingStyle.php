<?php
/**
 * NamingStyle
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
 * @since       v0.6.0 2011-01-12 18:25:01
 * @todo        扩展性
 */

class Qwin_NamingStyle
{
    const LOWER_CAMEL   = 1;
    
    const UNDERSCORE    = 2;

    const HYPHEN        = 3;

    /**
     * 转换器对应数组(类名)
     * @var array
     */
    protected $_converter = array(
        self::LOWER_CAMEL   => 'Qwin_NamingStyle_LowerCamel',
        self::UNDERSCORE    => 'Qwin_NamingStyle_Underscore',
        self::HYPHEN        => 'Qwin_NamingStyle_Hyphen',
    );

    /**
     * 类型名(方法名)
     * @var array
     */
    protected $_case = array(
        self::LOWER_CAMEL   => 'LowerCamel',
        self::UNDERSCORE    => 'Underscore',
        self::HYPHEN        => 'Hyphen',
    );

    public function convert($data, $from, $to)
    {
        if (isset($this->_converter[$from]) && isset($this->_case[$to])) {
            return call_user_func(array($this->_converter[$from], 'convertTo' . $this->_case[$to]), $data);
        }

        if (!isset($this->_converter[$from])) {
            throw new Qwin_NamingStyle_Exception('The converter "' . $from . '" is not defined.');
        }

        if (!isset($this->_case[$to])) {
            throw new Qwin_NamingStyle_Exception('The case "' . $to . '" is not defined.');
        }
    }
}