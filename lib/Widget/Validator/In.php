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
    
    /**
     * @param boolean $strict
     */
    public function __invoke($data, $array, $strict = false)
    {
        if ($array instanceof \ArrayObject) {
            $array = $array->getArrayCopy();
        } elseif (!is_array($array)) {
            throw new UnexpectedTypeException($data, 'array or \ArrayObject');
        }

        return in_array($data, $array, $strict);
    }
}
