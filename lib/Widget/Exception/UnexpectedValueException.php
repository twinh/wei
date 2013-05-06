<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Exception;

/**
 * Exception thrown if a value does not match with a set of values. Typically
 * this happens when a function calls another function and expects the return
 * value to be of a certain type or value not including arithmetic or buffer
 * related errors.
 * 
 * @link http://php.net/manual/en/class.unexpectedvalueexception.php
 * @author      Twin Huang <twinhuang@qq.com>
 */
class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{
}
