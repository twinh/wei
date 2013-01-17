<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

use InvalidArgumentException;

/**
 * Check if the data in array
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class In
{
    /**
     * @param boolean $strict
     */
    public function __invoke($data, $array, $strict = false)
    {
        if ($array instanceof \ArrayObject) {
            $array = $array->getArrayCopy();
        } elseif (!is_array($array)) {
            throw new InvalidArgumentException(sprintf('Expected argument of type array or ArrayObject, %s given', gettype($array)));
        }

        return in_array($data, $array, $strict);
    }
}
