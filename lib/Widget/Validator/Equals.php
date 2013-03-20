<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is equals to specified value
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Equals extends AbstractValidator
{
    protected $notEqualsMessage = '%name% must be equals %equals%';
    
    protected $negativeMessage = '%name% must not be equals %equals%';
    
    protected $equals;
    
    protected $strict = false;
    
    public function __invoke($input, $equals = null, $strict = null)
    {
        // Sets $this->equals only when the second argument provided
        func_num_args() > 1 && $this->storeOption('equals', $equals);
        is_bool($strict) && $this->storeOption('strict', $strict);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!($this->strict ? $input === $this->equals : $input == $this->equals)) {
            $this->addError('notEquals');
            return false;
        }
        
        return true;
    }
}
