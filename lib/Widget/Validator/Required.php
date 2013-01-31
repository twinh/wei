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
class Required extends AbstractRule
{
    protected $message = 'This value is required';
    
    public function __invoke($data, $required = true)
    {
        return !$required || $data;
    }
}
