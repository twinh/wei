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
class MinLength extends AbstractLengthValidator
{
    protected $tooShortMessage = '%name% must have a length greater than %min%';
    
    protected $tooFewMessage = '%name% must contain at least %min% item(s)';
    
    protected $min;

    public function __invoke($input, $min = null)
    {
        $min && $this->min = $min;
        
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
