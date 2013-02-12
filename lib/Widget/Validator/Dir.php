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
class Dir extends AbstractValidator
{
    protected $notFoundMessage = '%name% must be an existing directory';
    
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
        
        $dir = stream_resolve_include_path($input);
        if (!$dir || !is_dir($dir)) {
            $this->addError('notFound');
        }
        
        return !$this->errors;
    }
}
