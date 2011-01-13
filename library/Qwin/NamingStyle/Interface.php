<?php
/**
 * Interface
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
 * @since       2011-01-12 19:19:42
 */

interface Qwin_NamingStyle_Interface
{
    /**
     * 将字符串以当前风格解码为源
     *
     * @param string $name 名称
     * @return array 源
     */
    public static function decode($name);

    /**
     * 将源编码为当前风格的字符串
     *
     * @param array $source 源
     * @return string 名称
     */
    public static function encode(array $source);
}