<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is between the specified minimum and maximum value
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Range extends AbstractValidator
{
    protected $rangeMessage = '%name% must between %min% and %max%';
    
    protected $notMessage = '%name% must not between %min% and %max%';
    
    protected $min;
    
    protected $max;
    
    public function __invoke($input, $min = null, $max = null)
    {
        // Allows not numeric parameter like 2000-01-01, 10:03, etc 
        if ($min && $max) {
            $this->setOption('min', $min);
            $this->setOption('max', $max);
        }
        
        return $this->isValid($input);
    }
     
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if ($this->min > $input || $this->max < $input) {
            $this->addError('range');
            return false;
        }
        return true;
    }
}
