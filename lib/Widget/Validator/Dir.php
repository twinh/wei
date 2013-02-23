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
    
    protected $notMessage = '%name% must be a non-existing directory';
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        $dir = stream_resolve_include_path($input);
        if (!$dir || !is_dir($dir)) {
            $this->addError('notFound');
            return false;
        }
        
        return true;
    }
}
