<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * IsDir
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Dir extends AbstractRule
{
    /**
     * Determine the object source is a file path, check with the include_path.
     *
     * @param  bool        $abs return file path or true
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

        $full = stream_resolve_include_path($value);
        if ($full) {
            return $abs ? $full : true;
        }

        return false;
    }
}
