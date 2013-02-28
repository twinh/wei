<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Exception;

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link http://php.net/manual/en/class.runtimeexception.php
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}

