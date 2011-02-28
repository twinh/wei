<?php
 /**
 * Model
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
 * @since       2010-04-17 15:49:35
 */

/**
 * @see Doctrine_Record
 */
require_once 'Doctrine/Record.php';

abstract class Qwin_Application_Model extends Doctrine_Record
{
    /**
     * 根据应用结构配置获取模型对象
     *
     * @param array $asc 应用结构配置
     * @return Common_Model 模型对象
     * @todo
     */
    public static function getByAsc(array $asc, $instanced = true)
    {
        $class = $asc['namespace'] . '_' . $asc['module'] . '_Model_' . $asc['controller'];
        if (!class_exists($class)) {
            $class = $asc['namespace'] . '_Model';
        }
        return $instanced ? Qwin::call($class) : null;
    }
}
