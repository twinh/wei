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
class Number extends AbstractValidator
{
    protected $notNumberMessage = '%name% must be valid number';
    
    protected $notMessage = '%name% must not be number';
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!is_numeric($input)) {
            $this->addError('notNumber');
            return false;
        }
        
        return true;
    }
}
