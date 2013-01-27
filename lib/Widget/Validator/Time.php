<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Time
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Time extends AbstractRule
{
    protected $message = 'This value is not a valid time';
    
    public function __invoke($value)
    {
        return (bool) preg_match('/^([01]\d|2[0-3])(:[0-5]\d){0,2}$/', $value);
    }
}
