<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
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
