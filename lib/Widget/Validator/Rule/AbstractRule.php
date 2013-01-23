<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

use Widget\WidgetProvider;

abstract class AbstractRule extends WidgetProvider
{
    /**
     * The invalid message
     * 
     * @var string
     */
    protected $message;

    /**
     * Returns the invalid message
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the invalid message
     * 
     * @param type $message
     * @return \Widget\Validator\Rule\AbstractRule
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
