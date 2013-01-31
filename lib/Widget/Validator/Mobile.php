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
class Mobile extends Regex
{
    protected $message = 'This value must be valid mobile number';
    
    protected $pattern = '/^1[358][\d]{9}$/';
}
