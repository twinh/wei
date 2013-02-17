<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\UnexpectedTypeException;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class In extends AbstractValidator
{
    protected $notInMessage = '%name% must be in %array%';
    
    protected $notMessage = '%name% must not be in %array%';
    
    protected $strict = false;
    
    protected $array = array();
    
    public function __invoke($input, $array = array(), $strict = null)
    {
        if ($array) {
            if ($array instanceof \ArrayObject) {
                $this->array = $array->getArrayCopy();
            } elseif (is_array($array)) {
                $this->array = $array;
            } else {
                throw new UnexpectedTypeException($input, 'array or \ArrayObject');
            }
        }
        
        is_bool($strict) && $this->strict = $strict;
        
        if (!in_array($input, $this->array, $this->strict)) {
            $this->addError('notIn');
            return false;
        }
        
        return true;
    }
}
