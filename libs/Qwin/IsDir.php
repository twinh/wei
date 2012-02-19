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
 * IsDir
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-02 00:37:42
 * @todo        加载路径等各种路径的检查
 */
class Qwin_IsDir extends Qwin_Widget
{
    /**
     * whether function "stream_resolve_include_path" exists
     *
     * @var bool
     */
    protected  $_fn;

    public function __construct(array $options = array())
    {
        $this->_fn = function_exists('stream_resolve_include_path');
    }

    /**
     * Determine the object source is a file path, check with the include_path.
     *
     * @param bool $abs return file path or true
     * @return string|bool
     */
    public function __invoke($value, $abs = true)
    {
        if (!is_string($value)) {
            return false;
        }

        // check directly if it's absolute path
        if ('/' == $value[0] || '\\' == $value[0] || ':' == $value[1]) {
            if (is_dir($value)) {
                return $abs ? $value : true;
            }
        }

        if (function_exists('stream_resolve_include_path')) {
            $full = stream_resolve_include_path($value);
            if ($full) {
                return $abs ? $full : true;
            }
            return false;
        }

        // check if in include path
        foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $path) {
            $full = rtrim($path, '\\/') . DIRECTORY_SEPARATOR . $value;
            if (is_dir($full)) {
                return $abs ? $full : true;
            }
        }

        // not found
        return false;
    }
}
