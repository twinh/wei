<?php
/**
 * Package
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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 15:45:02
 */

abstract class Qwin_Application_Package
{
    /**
     * 合法的命名空间
     * @var array
     */
    protected static $_validList;

    /**
     * 根据应用结构配置获取命名空间
     *
     * @param array $asc 应用结构配置
     * @return Qwin_Application_Package 命名空间对象
     */
    public static function getByAsc(array $asc, $instanced = true)
    {
        $class = $asc['package'] . '_Package';
        return $instanced ? Qwin::call($class) : null;
    }

    /**
     * 获取合法的命名空间列表
     *
     * @param array $appPaths 应用根目录
     * @return array
     */
    public static function getList($appPaths = array())
    {
        if (!isset(self::$_validList)) {
            self::$_validList = array();
            foreach ((array)$appPaths as $path) {
                if (!is_dir($path)) {
                    continue;
                }
                foreach (scandir($path) as $file) {
                    if ('.' != $file[0] && is_dir($path . $file)) {
                        self::$_validList[$file] = $path . $file;
                    }
                }
            }
        }
        return self::$_validList;
    }
}
