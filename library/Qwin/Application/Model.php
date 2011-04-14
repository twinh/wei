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
     * 根据模块获取模型对象
     *
     * @param Qwin_Module|string $module 模块对象/标识
     * @param bool $instanced 是否实例化
     * @return Com_Model|string 实例化对象|类名
     */
    public static function getByModule($module, $instanced = true)
    {
        if ($module instanceof Qwin_Module) {
            $class = $module->toClass();
        } else {
            $class = Qwin_Module::instance($module)->toClass();
        }
        $class .= '_Model';
        if (!class_exists($class)) {
            throw new Qwin_Application_Model_Exception('Model Class "' . $class . '" not found.');
        }
        return $instanced ? new $class : $class;
    }
}
