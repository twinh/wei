<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is less or equal than specified value
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Max extends AbstractValidator
{
    protected $maxMessage = '%name% must be less or equal than %max%';
    
    protected $negativeMessage = '%name% must not be less or equal than %max%';
    
    protected $max;
    
    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $max = null)
    {
        $max && $this->storeOption('max', $max);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if ($this->max < $input) {
            $this->addError('max');
            return false;
        }
        
        return true;
    }
}
