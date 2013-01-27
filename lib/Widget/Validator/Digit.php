<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * IsDigit
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Digit extends AbstractRule
{
    protected $message = 'This value must contain only digits (0-9)';
    
    public function __invoke($data)
    {
        return (bool) preg_match('/^([0-9]+)$/', $data);
    }
}
