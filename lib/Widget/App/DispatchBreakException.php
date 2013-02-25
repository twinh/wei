<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\App;

use Widget\Exception;

/**
 * DispatchBreakException
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DispatchBreakException extends Exception
{
    public function __construct ($message, $code, $file, $line)
    {
        $this->message = $message;
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
    }
}
