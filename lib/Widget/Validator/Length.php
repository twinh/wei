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
class Length extends AbstractLengthValidator
{
    protected $lengthMessage = '%name% must have a length between %min% and %max%';
    
    protected $lengthItemMessage = '%name% must contain %min% to %max% item(s)';

    protected $min;
    
    protected $max;
    
    public function __invoke($input, $min = null, $max = null)
    {
        if (is_numeric($min) && is_numeric($max)) {
            $this->min = $min;
            $this->max = $max;
        }
        
        if (false === ($len = $this->getLength($input))) {
            $this->addError('notDetectd');
            return false;
        }
        
        if ($this->min > $len || $this->max < $len) {
            $this->addError(is_scalar($input) ? 'length' : 'lengthItem');
            return false;
        }
        
        return true;
    }
}
