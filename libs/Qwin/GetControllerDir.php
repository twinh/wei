<?php

/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * GetControllerDir
 * 
 * @package     Qwin
 * @subpackage  Appliaction
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-11-10 15:16:48
 */
class Qwin_GetControllerDir extends Qwin_Widget
{
    public function call($module = null)
    {
        $module = $module ? $module : $this->module();
        foreach ($this->app->options['dirs'] as $dir) {
            $file = $dir . $module->toPath() . '/Controller.php';
            if (is_file($file)) {
                return $dir . $module->toPath();
            }
        }
        
        return false;
    }
}
