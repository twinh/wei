<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * IsEmail
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Email extends AbstractRule
{
    protected $message = 'The value should be valid email address';

    public function __invoke($value)
    {
        return (bool) preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/i', $value);
    }
}
