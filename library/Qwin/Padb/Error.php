<?php
/**
 * Error
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
 * @subpackage  Padb
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-27 11:08:27
 * @todo        错误信息的多语言支持
 */

class Qwin_Padb_Error
{
    protected static $_errorMap = array(
        0 => null,
        -1 => 'Unknown error.',
        100 => 'Unknown query: "%s".',
        102 => 'Unknown query type: "%s".',
        110 => 'Can not find database "%s".',
        111 => 'No database selected.',
        112 => 'The table "%s" is not exists.',
        113 => 'Unknown field "%s" in table "%s".',
        114 => 'The primary key\'s value "%s" is exists.',
        115 => 'The database "%s" is exists.',
        116 => 'Can not create the databse: "%s"',
        117 => 'The table "%s" is exists.',
    );

    /**
     * 根据错误代号获取错误信息,当错误信息不存在时,返回未知错误
     *
     * @param int $key
     */
    public static function getError($key)
    {
        if(isset(self::$_errorMap[$key]))
        {
            $result = self::$_errorMap[$key];
        } else {
            $result = self::$_errorMap[-1];
        }
        return $result;
    }
}
