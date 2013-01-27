<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Phone
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Phone extends AbstractRule
{
    protected $message = 'This value must be valid phone number';
    
    public function __invoke($value)
    {
        return (bool) preg_match('/^(\d{3,4}-?)?\d{7,9}$/', $value);
    }
}
