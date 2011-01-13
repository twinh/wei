<?php
/**
 * Hyphen
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
 * @since       2011-01-12 19:32:26
 */

class Qwin_NamingStyle_Hyphen implements Qwin_NamingStyle_Interface
{
    /**
     * 将字符串解码为源
     *
     * @param string $name 名称
     * @return array 源
     */
    public static function decode($name)
    {
        return array_map('strtolower', explode('-', $name));
    }

    /**
     * 将源编码为字符串
     *
     * @param array $source 源
     * @return string 名称
     */
    public static function encode(array $source)
    {
        return implode('-', $source);
    }

    /**
     * 转换为驼峰命名风格
     * 
     * @param string $name 名称
     * @return string 驼峰命名风格名称
     */
    public static function convertToLowerCamel($name)
    {
        $name = implode('', array_map('ucfirst', explode('-', $name)));
        $name[0] = strtolower($name[0]);
        return $name;
    }

    /**
     * 转换为下划线风格
     * 
     * @param string $name 名称
     * @return string 下划线命名风格名称
     */
    public static function convertToUnderscore($name)
    {
        return strtr($name, '-', '_');
    }
}