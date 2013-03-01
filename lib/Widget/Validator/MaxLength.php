<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the length (or size) of input is lower than specified length
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class MaxLength extends AbstractLengthValidator
{
    protected $tooLongMessage = '%name% must have a length lower than %max%';
    
    protected $tooManayMessage = '%name% must contain no more than %max% items';
    
    protected $max;
    
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
        if (false === ($len = $this->getLength($input))) {
            $this->addError('notDetectd');
            return false;
        }
        
        if ($this->max < $len) {
            $this->addError(is_scalar($input) ? 'tooLong' : 'tooManay');
            return false;
        }
        
        return true;
    }
}
