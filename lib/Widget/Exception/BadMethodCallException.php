<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
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

