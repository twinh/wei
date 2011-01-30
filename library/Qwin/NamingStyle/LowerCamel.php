<?php
/**
 * LowerCamelCase
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
 * @since       2011-01-12 19:31:22
 */

class Qwin_NamingStyle_LowerCamel implements Qwin_NamingStyle_Interface
{
    /**
     * 将字符串解码为源
     *
     * @param string $name 名称
     * @return array 源
     */
    public static function decode($name)
    {
        return array_map('strtolower', preg_split('/(?<!^)(?=[A-Z])/', $name));
    }

    /**
     * 将源编码为字符串
     *
     * @param array $source 源
     * @return string 名称
     */
    public static function encode(array $source)
    {
        if (empty($source) || 1 == count($source)) {
            return $source;
        }
        $name = implode('', array_map('ucfirst', $source));
        /**
         * lcfirst 需5.3.0以上版本支持
         * @see http://php.net/manual/en/function.lcfirst.php
         */
        if(!function_exists('lcfirst')) {
            $name[0] = strtolower($name[0]);
        } else {
            lcfirst($name);
        }
        return $name;
    }

    /**
     * 转换为连接符风格
     *
     * @param string $name 名称
     * @return string 下划线命名风格名称
     */
    public static function filterToHyphen($name)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $name));
    }

    /**
     * 转换为下划线风格
     *
     * @param string $name 名称
     * @return string 下划线命名风格名称
     */
    public static function filterToUnderscore($name)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }
}
