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
class Email extends AbstractValidator
{
    protected $formatMessage = '%name% must be valid email address';
    
    public function __invoke($input)
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $this->addError('format');
            return false;
        }
        
        return true;
    }
}
