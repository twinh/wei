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
class Number extends AbstractValidator
{
    protected $notNumberMessage = '%name% must be valid number';
    
    public function __invoke($input)
    {
        if (!is_numeric($input)) {
            $this->addError('notNumber');
            return false;
        }
        
        return true;
    }
}
