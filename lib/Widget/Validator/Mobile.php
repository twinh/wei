<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Mobile
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Mobile extends AbstractRule
{
    protected $message = 'This value must be valid mobile number';
    
    public function __invoke($data)
    {
        return (bool) preg_match('/^1[358][\d]{9}$/', $data);
    }
}
