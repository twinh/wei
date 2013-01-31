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
class In
{
    protected $message = 'This value must be in {{ array }}';
    
    protected $strict = false;
    
    protected $array = array();
    
    public function __invoke($data, $array = array(), $strict = null)
    {
        if ($array) {
            if ($array instanceof \ArrayObject) {
                $this->array = $array->getArrayCopy();
            } elseif (is_array($array)) {
                $this->array = $array;
            } else {
                throw new UnexpectedTypeException($data, 'array or \ArrayObject');
            }
        }
        
        
        is_bool($strict) && $this->strict = $strict;
        
        return in_array($data, $this->array, $this->strict);
    }
}
