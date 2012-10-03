<?php

/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin\App;

use Qwin\Exception;

/**
 * WorkFlowBreakNotifyException
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class WorkFlowBreakNotifyException extends Exception
{
    public function __construct ($message, $code, $file, $line)
    {
        $this->message = $message;
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
    }
}