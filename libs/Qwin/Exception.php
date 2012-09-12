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
    public $options = array(
        'code' => 500,
        'message' => null,
    );

    public function __construct($message, $code = 500)
    {
        if (is_array($message)) {
            $options = $message + $this->options;
            $this->message = $options['message'];
            $this->code = $options['code'];
        } else {
            parent::__construct($message, $code);
        }
    }

    public function __invoke($message, $code = 500)
    {
        $this->message = $message;
        $this->code = $code;
        throw $this;
    }

    /**
     * Get the class name of invoker
     * 
     * @return string
     * @todo getInvokeWidget ?
     */
    public function getInvokerClass()
    {
        return $this->options['invoker'] ? get_class($this->options['invoker']) : null;
    }
}