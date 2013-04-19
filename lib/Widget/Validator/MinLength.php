<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the length (or size) of input is greater than specified length
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MinLength extends AbstractLengthValidator
{
    protected $tooShortMessage = '%name% must have a length greater than %min%';
    
    protected $tooFewMessage = '%name% must contain at least %min% item(s)';
    
    protected $min;

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
        if (false === ($len = $this->getLength($input))) {
            $this->addError('notDetectd');
            return false;
        }
        
        if ($this->min > $len) {
            $this->addError(is_scalar($input) ? 'tooShort' : 'tooFew');
            return false;
        }
        
        return true;
    }
}
