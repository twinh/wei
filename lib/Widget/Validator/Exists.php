<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Exists extends AbstractValidator
{
    protected $notFoundMessage = '%name% must be an existing directory';
    
    public function __invoke($input)
    {
        if (!is_string($input)) {
            return false;
        }
        
        $dir = stream_resolve_include_path($input);
        if (!$dir || !file_exists($dir)) {
            $this->addError('notFound');
            return false;
        }
        
        return true;
    }
}
