<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Exception;

/**
 * Exception thrown if a callback refers to an undefined function or if some arguments are missing
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface
{
}

