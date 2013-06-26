<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

interface ValidatorInterface
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
