<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

namespace Widget;

/**
 * BeInArray
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class BeInArray extends AbstractWidget
{
    /**
     * while $value is not find in the specify $array, use the first element of array instead
     *
     * @param  mixed $value
     * @param  array $array
     * @return mixed
     */
    public function __invoke($value, array $array)
    {
        !in_array($value, $array) && $value = $array[key($array)];

        return $value;
    }
}
