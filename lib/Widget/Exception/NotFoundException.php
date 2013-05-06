<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Exception;

/**
 * Exception thrown if any resource or record not found
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 */
class NotFoundException extends \RuntimeException implements ExceptionInterface
{
    public function __construct($message, $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
