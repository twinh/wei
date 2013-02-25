<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class UnexpectedTypeException extends Exception
{
    public function __construct($value, $expectedType, $argument = '')
    {
        parent::__construct(sprintf(
            'Expected argument%s of type %s, "%s" given', 
            ' ' . $argument,
            $expectedType, 
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}