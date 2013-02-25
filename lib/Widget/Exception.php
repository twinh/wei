<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The base exception class for all widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Exception extends \Exception
{
    /**
     * Constructor
     * 
     * @param string    $message    The Exception message
     * @param int       $code       The exception code
     * @param string    $file       The filename in which the exception was created
     * @param int       $line       The file line in which the exception occurred
     */
    public function __construct($message, $code = 500, $file = null, $line = null)
    {
        $this->message = $message;
        $this->code = $code;
        $file && $this->file = $file;
        $line && $this->line = $line;
    }
}
