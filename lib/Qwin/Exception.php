<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * The base exception class for all widget
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Exception extends \Exception
{
    public function __construct($message, $code = 500, $file = null, $line = null)
    {
        $this->message = $message;
        $this->code = $code;
        $file && $this->file = $file;
        $line && $this->line = $line;
    }
}