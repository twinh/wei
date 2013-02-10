<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Dir extends AbstractRule
{
    protected $message = '%name% must be an existing directory';
    
    /**
     * Returns directory path or true
     * 
     * @var bool 
     */
    protected $abs = true;
    
    /**
     * Determine the object source is a directory path, check with the include_path.
     *
     * @return string|bool
     */
    public function __invoke($input)
    {
        if (!is_string($input)) {
            return false;
        }

        // check directly if it's absolute path
        if ('/' == $input[0] || '\\' == $input[0] || ':' == $input[1]) {
            if (is_dir($input)) {
                return $this->abs ? $input : true;
            }
        }

        $full = stream_resolve_include_path($input);
        if ($full) {
            return $this->abs ? $full : true;
        }

        return false;
    }
}
