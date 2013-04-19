<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Exception;

/**
 * Exception thrown if a requested operation is not supported
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class UnsupportedException extends \InvalidArgumentException implements ExceptionInterface
{
}
