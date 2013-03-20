<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input could be divisible by specified divisor
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DivisibleBy extends AbstractValidator
{
    protected $notDivisibleMessage = '%name% must be divisible by %divisor%';
    
    protected $negativeMessage = '%name% must not be divisible by %divisor%';
    
    /**
     * Set divisor to 1 to avoid "division by zero" error
     * 
     * @var int|float
     */
    protected $divisor = 1;
    
    public function __invoke($input, $divisor = null)
    {
        $divisor && $this->storeOption('divisor', $divisor);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notDivisible');
            return false;
        }
        
        if (is_float($this->divisor)) {
            $result = fmod($input, $this->divisor);
        } else {
            $result = $input % $this->divisor;
        }
        
        if (0 != $result) {
            $this->addError('notDivisible');
            return false;
        }
        return true;
    }    
}
