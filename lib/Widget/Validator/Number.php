<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Number extends AbstractRule
{
    protected $message = 'This value must be valid number';
    
    public function __invoke($data)
    {
        return (bool) is_numeric($data);
    }
}
