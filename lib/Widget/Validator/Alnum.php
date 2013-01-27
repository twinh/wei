<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * IsAlnum
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Alnum extends AbstractRule
{
    protected $message = 'This value must contain letters (a-z) and digits (0-9)';
    
    public function __invoke($value)
    {
        return (bool) preg_match('/^([a-z0-9]+)$/i', $value);
    }
}
