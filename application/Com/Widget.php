<?php
/**
 * Widget
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
 * @package     Com
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-02 02:32:02
 */

class Com_Widget extends Qwin_Widget
{
    public static function getByModule($module, $name = null, $instanced = true)
    {
        if ($module instanceof Qwin_Module) {
            $class = $module->toClass();
        } else {
            $class = preg_split('/([^A-Za-z0-9])/', $module);
            $class = implode('_', array_map('ucfirst', $class));
        }
        if ($name) {
            $class = $class . '_Widget_' . ucfirst($name);
        } else {
            $class = $class . '_Widget';
        }

        return $instanced ? Qwin::call('-widget')->getByClass($class) : $class;
    }
}