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
class Chinese extends Regex
{
    protected $message = 'This value must contain only Chinese characters';
    
    protected $pattern = '/^[\x{4e00}-\x{9fa5}]+$/u';
}
