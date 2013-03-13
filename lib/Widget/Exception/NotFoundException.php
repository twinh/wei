<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Exception;

/**
 * Exception thrown if any resource or record not found
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class NotFoundException extends \RuntimeException implements ExceptionInterface
{
    public function __construct($message, $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
