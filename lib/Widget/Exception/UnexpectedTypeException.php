<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Exception;

/**
 * Exception thrown if an argument type does not match with the expected type
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class UnexpectedTypeException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * Constructor
     * 
     * @param mixed $value The value to be detected
     * @param string $expectedType The expected type string
     * @param int $argument The index of argument
     */
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
