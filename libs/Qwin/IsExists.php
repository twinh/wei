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
 * IsExists
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-05 11:44:13
 */
class Qwin_IsExists extends Qwin_Widget
{
    /**
     * whether function "stream_resolve_include_path" exists
     *
     * @var bool
     */
    protected  $_fn;

    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->_fn = function_exists('stream_resolve_include_path');
    }

    /**
     * Determine the object source is a file path, check with the include_path.
     *
     * @param bool $abs return file path or true
     * @return string|bool
     */
    public function call($file, $abs = true)
    {
        if (!is_string($file)) {
            return false;
        }

        // check directly if it's absolute path
        if ('/' == $file[0] || '\\' == $file[0] || ':' == $file[1]) {
            if (file_exists($file)) {
                return $abs ? $file : true;
            }
        // @codeCoverageIgnoreStart
        // code would be tested in Qwin_IsDir
        }

        if (function_exists('stream_resolve_include_path')) {
            $full = stream_resolve_include_path($file);
            if ($full) {
                return $abs ? $full : true;
            }
            return false;
        }

        // check if in include path
        foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $path) {
            $full = rtrim($path, '\\/') . DIRECTORY_SEPARATOR . $file;
            if (file_exists($full)) {
                return $abs ? $full : true;
            }
        }

        // not found
        return false;
        // @codeCoverageIgnoreEnd
    }
}
