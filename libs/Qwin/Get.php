<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * Get
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-02 00:44:51
 */
class Qwin_Get extends Qwin_Widget
{
    public function call($name, $default = null /*, $type='string' ?*/)
    {
        if (is_string($name)) {
            return Qwin::variable(isset($_GET[$name]) ? $_GET[$name] : $default);
        } elseif (is_int($name)) {
            if (!is_int($default)) {
                return Qwin::variable(isset($this->source[$name]) ? $this->source[$name] : null);
            } else {
                if (is_string($this->source)) {
                    return Qwin::variable(substr($this->source, $name, $default));
                } elseif (is_array($this->source)) {
                    return Qwin::variable(array_slice($this->source, $name, $default));
                }
            }
        }
        return $this;
    }
}