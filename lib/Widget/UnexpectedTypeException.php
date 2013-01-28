<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class UnexpectedTypeException extends Exception
{
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf(
            'Expected argument of type %s, "%s" given', 
            $expectedType, 
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}