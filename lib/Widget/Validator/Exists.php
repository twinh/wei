<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Exists extends AbstractValidator
{
    protected $notFoundMessage = '%name% must be an existing file or directory';
    
    protected $notMessage = '%name% must be a non-existing file or directory';
            
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        $file = stream_resolve_include_path($input);
        if (!$file) {
            $this->addError('notFound');
            return false;
        }
        
        return true;
    }
}
