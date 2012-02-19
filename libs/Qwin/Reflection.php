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
 * Reflection
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-09-16 13:35:13
 */
class Qwin_Reflection extends Qwin_Widget
{
    /**
     * Export variable for better output
     *
     * @param mixed $value
     * @return string
     */
    public static function exportValue($value)
    {
        if (is_array($value)) {
            if (empty($value)) {
                $value = 'array( )';
            } else {
                $value = 'array(..)';
            }
        } elseif (is_null($value)) {
            $value = 'null';
        } else {
            $value = var_export($value, true);
        }
        return $value;
    }

    /**
     * Get invoker's reflection
     *
     * @return Qwin_Reflection_Class
     */
    public function __invoke()
    {
        return new Qwin_Reflection_Class($this->__invoker);
    }
}
