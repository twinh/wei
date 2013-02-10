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
     * Returns the invalid messages
     * 
     * @return string
     */
    public function getMessages();
    
    /**
     * Returns whether the $input value is valid
     */
    public function isValid($input);
}
