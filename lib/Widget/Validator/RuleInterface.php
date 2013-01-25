<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

interface RuleInterface
{
    /**
     * Returns the invalid message
     * 
     * @return string
     */
    public function getMessage();

    /**
     * Set the invalid message
     * 
     * @param type $message
     * @return \Widget\Validator\AbstractRule
     */
    public function setMessage($message);
}
