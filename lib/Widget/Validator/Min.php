<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is greater or equal than specified value
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Min extends AbstractValidator
{        
    protected $minMessage = '%name% must be greater or equal than %min%';
    
    protected $negativeMessage = '%name% must not be greater or equal than %min%';

    protected $min;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $min = null)
    {
        $min && $this->storeOption('min', $min);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if ($this->min > $input) {
            $this->addError('min');
            return false;
        }
        
        return true;
    }
}
